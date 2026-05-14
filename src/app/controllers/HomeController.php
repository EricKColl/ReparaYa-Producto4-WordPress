<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Incidencia.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $usuarioSesion = $_SESSION['usuario'] ?? null;
        $rolInicio = $usuarioSesion['rol'] ?? 'public';
        $dashboard = [];

        if ($rolInicio === 'admin') {
            $usuarioModel = new Usuario();
            $tecnicoModel = new Tecnico();
            $especialidadModel = new Especialidad();
            $incidenciaModel = new Incidencia();

            $usuarios = $usuarioModel->getAll();
            $tecnicos = $tecnicoModel->getAll();
            $especialidades = $especialidadModel->getAll();
            $incidencias = $incidenciaModel->getAll(true);

            $activas = 0;
            $pendientes = 0;
            $urgentes = 0;
            $canceladas = 0;
            $tecnicosDisponibles = 0;
            $serviciosHoy = 0;
            $proximas = [];

            foreach ($tecnicos as $tecnico) {
                if (!empty($tecnico['disponible'])) {
                    $tecnicosDisponibles++;
                }
            }

            foreach ($incidencias as $incidencia) {
                $timestamp = strtotime($incidencia['fecha_servicio']);

                if (($incidencia['estado'] ?? '') !== 'Cancelada' && ($incidencia['estado'] ?? '') !== 'Finalizada') {
                    $activas++;
                }

                if (($incidencia['estado'] ?? '') === 'Pendiente') {
                    $pendientes++;
                }

                if (($incidencia['tipo_urgencia'] ?? '') === 'Urgente' && ($incidencia['estado'] ?? '') !== 'Cancelada') {
                    $urgentes++;
                }

                if (($incidencia['estado'] ?? '') === 'Cancelada') {
                    $canceladas++;
                }

                if ($timestamp !== false) {
                    if (date('Y-m-d', $timestamp) === date('Y-m-d')) {
                        $serviciosHoy++;
                    }

                    if ($timestamp >= time() && ($incidencia['estado'] ?? '') !== 'Cancelada') {
                        $proximas[] = $incidencia;
                    }
                }
            }

            usort($proximas, function ($a, $b) {
                return strtotime($a['fecha_servicio']) <=> strtotime($b['fecha_servicio']);
            });

            $dashboard = [
                'stats' => [
                    'usuarios' => count($usuarios),
                    'tecnicos' => count($tecnicos),
                    'especialidades' => count($especialidades),
                    'activas' => $activas,
                    'pendientes' => $pendientes,
                    'urgentes' => $urgentes,
                    'canceladas' => $canceladas,
                    'tecnicos_disponibles' => $tecnicosDisponibles,
                    'servicios_hoy' => $serviciosHoy,
                ],
                'proximas' => array_slice($proximas, 0, 4),
            ];
        }

        if ($rolInicio === 'tecnico') {
            $tecnicoModel = new Tecnico();
            $incidenciaModel = new Incidencia();

            $tecnico = $tecnicoModel->getByUsuarioId((int) $usuarioSesion['id']);
            $intervenciones = [];
            $sinVinculo = false;

            $hoy = 0;
            $proximas = 0;
            $urgentes = 0;
            $finalizadas = 0;
            $siguientes = [];

            if (!$tecnico) {
                $sinVinculo = true;
            } else {
                $intervenciones = $incidenciaModel->getByTecnicoId((int) $tecnico['id']);

                foreach ($intervenciones as $intervencion) {
                    $timestamp = strtotime($intervencion['fecha_servicio']);

                    if (($intervencion['estado'] ?? '') === 'Finalizada') {
                        $finalizadas++;
                    }

                    if (($intervencion['tipo_urgencia'] ?? '') === 'Urgente') {
                        $urgentes++;
                    }

                    if ($timestamp !== false) {
                        if (date('Y-m-d', $timestamp) === date('Y-m-d')) {
                            $hoy++;
                        }

                        if ($timestamp >= time() && ($intervencion['estado'] ?? '') !== 'Finalizada') {
                            $proximas++;
                            $siguientes[] = $intervencion;
                        }
                    }
                }

                usort($siguientes, function ($a, $b) {
                    return strtotime($a['fecha_servicio']) <=> strtotime($b['fecha_servicio']);
                });
            }

            $dashboard = [
                'tecnico' => $tecnico,
                'sin_vinculo' => $sinVinculo,
                'stats' => [
                    'hoy' => $hoy,
                    'proximas' => $proximas,
                    'urgentes' => $urgentes,
                    'finalizadas' => $finalizadas,
                ],
                'siguientes' => array_slice($siguientes, 0, 4),
            ];
        }

        if ($rolInicio === 'particular') {
            $incidenciaModel = new Incidencia();
            $avisos = $incidenciaModel->getByClienteId((int) $usuarioSesion['id']);

            $activas = 0;
            $finalizadas = 0;
            $canceladas = 0;
            $urgentes = 0;

            $proximos = [];
            $recientes = array_slice($avisos, 0, 3);

            foreach ($avisos as $aviso) {
                $timestamp = strtotime($aviso['fecha_servicio']);

                if (in_array(($aviso['estado'] ?? ''), ['Pendiente', 'Asignada'], true)) {
                    $activas++;
                }

                if (($aviso['estado'] ?? '') === 'Finalizada') {
                    $finalizadas++;
                }

                if (($aviso['estado'] ?? '') === 'Cancelada') {
                    $canceladas++;
                }

                if (($aviso['tipo_urgencia'] ?? '') === 'Urgente' && ($aviso['estado'] ?? '') !== 'Cancelada') {
                    $urgentes++;
                }

                if ($timestamp !== false && $timestamp >= time() && ($aviso['estado'] ?? '') !== 'Cancelada') {
                    $proximos[] = $aviso;
                }
            }

            usort($proximos, function ($a, $b) {
                return strtotime($a['fecha_servicio']) <=> strtotime($b['fecha_servicio']);
            });

            $dashboard = [
                'stats' => [
                    'total' => count($avisos),
                    'activas' => $activas,
                    'finalizadas' => $finalizadas,
                    'canceladas' => $canceladas,
                    'urgentes' => $urgentes,
                ],
                'proximos' => array_slice($proximos, 0, 3),
                'recientes' => $recientes,
            ];
        }

        $this->render('home/index', [
            'title' => 'Inicio - ReparaYa',
            'usuarioSesion' => $usuarioSesion,
            'rolInicio' => $rolInicio,
            'dashboard' => $dashboard
        ]);
    }
}