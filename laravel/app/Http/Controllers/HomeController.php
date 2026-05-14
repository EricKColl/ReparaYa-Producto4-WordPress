<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Tecnico;
use App\Models\Especialidad;
use App\Models\Incidencia;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $usuario = null;
        $tipoInicio = 'visitante';

        if (session()->has('usuario_id')) {
            $usuario = [
                'id' => session('usuario_id'),
                'nombre' => session('usuario_nombre'),
                'rol' => session('usuario_rol')
            ];

            $tipoInicio = session('usuario_rol');
        }

        $dashboard = $this->obtenerDashboardGeneral();
        $homeData = $this->obtenerHomePorRol($usuario, $tipoInicio);
        $panelUsuario = $homeData;
        $calendarData = $this->obtenerCalendarioPorRol($usuario, $tipoInicio);

        return view('home', compact(
            'usuario',
            'tipoInicio',
            'dashboard',
            'homeData',
            'panelUsuario',
            'calendarData'
        ));
    }

    private function obtenerDashboardGeneral(): array
    {
        $totalUsuarios = Usuario::count();
        $totalParticulares = Usuario::where('rol', 'particular')->count();
        $totalUsuariosTecnicos = Usuario::where('rol', 'tecnico')->count();
        $totalAdmins = Usuario::where('rol', 'admin')->count();

        $totalTecnicos = Tecnico::count();
        $tecnicosDisponibles = Tecnico::where('disponible', 1)->count();
        $tecnicosNoDisponibles = Tecnico::where('disponible', 0)->count();

        $totalEspecialidades = Especialidad::count();

        $totalIncidencias = Incidencia::count();
        $pendientes = Incidencia::where('estado', 'Pendiente')->count();
        $asignadas = Incidencia::where('estado', 'Asignada')->count();
        $finalizadas = Incidencia::where('estado', 'Finalizada')->count();
        $canceladas = Incidencia::where('estado', 'Cancelada')->count();

        $urgentes = Incidencia::where('tipo_urgencia', 'Urgente')->count();
        $estandar = Incidencia::where('tipo_urgencia', 'Estandar')->count();
        $abiertas = $pendientes + $asignadas;

        return [
            'totales' => [
                'usuarios' => $totalUsuarios,
                'particulares' => $totalParticulares,
                'usuarios_tecnicos' => $totalUsuariosTecnicos,
                'admins' => $totalAdmins,
                'tecnicos' => $totalTecnicos,
                'tecnicos_disponibles' => $tecnicosDisponibles,
                'tecnicos_no_disponibles' => $tecnicosNoDisponibles,
                'especialidades' => $totalEspecialidades,
                'incidencias' => $totalIncidencias,
                'incidencias_abiertas' => $abiertas,
                'pendientes' => $pendientes,
                'asignadas' => $asignadas,
                'finalizadas' => $finalizadas,
                'canceladas' => $canceladas,
                'urgentes' => $urgentes,
                'estandar' => $estandar,
            ],
            'porcentajes' => [
                'tecnicos_disponibles' => $this->porcentaje($tecnicosDisponibles, $totalTecnicos),
                'pendientes' => $this->porcentaje($pendientes, $totalIncidencias),
                'asignadas' => $this->porcentaje($asignadas, $totalIncidencias),
                'finalizadas' => $this->porcentaje($finalizadas, $totalIncidencias),
                'canceladas' => $this->porcentaje($canceladas, $totalIncidencias),
                'urgentes' => $this->porcentaje($urgentes, $totalIncidencias),
                'resolucion' => $this->porcentaje($finalizadas, $totalIncidencias),
                'actividad_abierta' => $this->porcentaje($abiertas, $totalIncidencias),
            ]
        ];
    }

    private function obtenerHomePorRol(?array $usuario, string $tipoInicio): array
    {
        if ($usuario === null) {
            return [
                'titulo' => 'Reparaciones bajo control antes de que el caos pida presupuesto',
                'subtitulo' => 'ReparaYa convierte cada aviso en una intervención clara, asignable y medible. Menos llamadas perdidas, menos improvisación y más sensación de empresa seria desde el primer clic.',
                'accion_principal' => 'Acceder al sistema',
                'url_principal' => url('/login'),
                'secundaria' => 'Ver funcionamiento',
                'url_secundaria' => route('home'),
                'metricas' => [],
                'porcentajes' => [],
                'proxima' => null
            ];
        }

        if ($tipoInicio === 'admin') {
            $total = Incidencia::count();
            $abiertas = Incidencia::whereIn('estado', ['Pendiente', 'Asignada'])->count();
            $finalizadas = Incidencia::where('estado', 'Finalizada')->count();
            $urgentes = Incidencia::where('tipo_urgencia', 'Urgente')->count();

            return [
                'titulo' => 'Centro de mando operativo',
                'subtitulo' => 'Vista global para controlar usuarios, técnicos, especialidades e incidencias. Todo el sistema en una lectura clara para decidir rápido y gestionar mejor.',
                'accion_principal' => 'Gestionar incidencias',
                'url_principal' => url('/incidencias'),
                'secundaria' => 'Crear usuario',
                'url_secundaria' => url('/usuarios/create'),
                'metricas' => [
                    'Usuarios' => Usuario::count(),
                    'Técnicos' => Tecnico::count(),
                    'Incidencias' => $total,
                    'Abiertas' => $abiertas,
                    'Urgentes' => $urgentes,
                    'Finalizadas' => $finalizadas,
                ],
                'porcentajes' => [
                    'Resolución' => $this->porcentaje($finalizadas, $total),
                    'Actividad abierta' => $this->porcentaje($abiertas, $total),
                    'Urgencia' => $this->porcentaje($urgentes, $total),
                ],
                'proxima' => Incidencia::with(['cliente', 'tecnico', 'especialidad'])
                    ->whereIn('estado', ['Pendiente', 'Asignada'])
                    ->orderBy('fecha_servicio')
                    ->first()
            ];
        }

        if ($tipoInicio === 'particular') {
            $query = Incidencia::where('cliente_id', $usuario['id']);
            $total = (clone $query)->count();
            $abiertas = (clone $query)->whereIn('estado', ['Pendiente', 'Asignada'])->count();
            $pendientes = (clone $query)->where('estado', 'Pendiente')->count();
            $asignadas = (clone $query)->where('estado', 'Asignada')->count();
            $finalizadas = (clone $query)->where('estado', 'Finalizada')->count();
            $urgentes = (clone $query)->where('tipo_urgencia', 'Urgente')->count();

            return [
                'titulo' => 'Tus reparaciones, sin misterio y sin perseguir a nadie',
                'subtitulo' => 'Crea avisos, consulta el estado de tus solicitudes y sigue cada reparación desde una zona clara, directa y pensada para no perder tiempo.',
                'accion_principal' => 'Crear incidencia',
                'url_principal' => url('/incidencias/create'),
                'secundaria' => 'Ver mis avisos',
                'url_secundaria' => url('/incidencias'),
                'metricas' => [
                    'Total' => $total,
                    'Abiertas' => $abiertas,
                    'Pendientes' => $pendientes,
                    'Asignadas' => $asignadas,
                    'Finalizadas' => $finalizadas,
                    'Urgentes' => $urgentes,
                ],
                'porcentajes' => [
                    'Resolución' => $this->porcentaje($finalizadas, $total),
                    'En curso' => $this->porcentaje($abiertas, $total),
                    'Urgentes' => $this->porcentaje($urgentes, $total),
                ],
                'proxima' => Incidencia::with(['tecnico', 'especialidad'])
                    ->where('cliente_id', $usuario['id'])
                    ->whereIn('estado', ['Pendiente', 'Asignada'])
                    ->orderBy('fecha_servicio')
                    ->first()
            ];
        }

        if ($tipoInicio === 'tecnico') {
            $tecnico = Tecnico::with('especialidad')
                ->where('usuario_id', $usuario['id'])
                ->first();

            if (!$tecnico) {
                return [
                    'titulo' => 'Ficha técnica pendiente de vinculación',
                    'subtitulo' => 'Tu usuario existe, pero todavía no está asociado a una ficha técnica. Cuando el administrador complete la vinculación, verás aquí tus servicios asignados.',
                    'accion_principal' => 'Volver al inicio',
                    'url_principal' => route('home'),
                    'secundaria' => 'Cerrar sesión',
                    'url_secundaria' => url('/logout'),
                    'metricas' => [
                        'Asignadas' => 0,
                        'Pendientes' => 0,
                        'Finalizadas' => 0,
                    ],
                    'porcentajes' => [
                        'Resolución' => 0,
                        'En curso' => 0,
                        'Vencidas' => 0,
                    ],
                    'proxima' => null
                ];
            }

            $query = Incidencia::where('tecnico_id', $tecnico->id);
            $total = (clone $query)->count();
            $abiertas = (clone $query)->whereIn('estado', ['Pendiente', 'Asignada'])->count();
            $pendientes = (clone $query)->where('estado', 'Pendiente')->count();
            $asignadas = (clone $query)->where('estado', 'Asignada')->count();
            $finalizadas = (clone $query)->where('estado', 'Finalizada')->count();
            $vencidas = (clone $query)
                ->whereIn('estado', ['Pendiente', 'Asignada'])
                ->where('fecha_servicio', '<', Carbon::now())
                ->count();

            return [
                'titulo' => 'Tu agenda técnica, clara y sin rodeos',
                'subtitulo' => 'Consulta las incidencias asignadas a tu ficha técnica, revisa prioridades y mantén el servicio bajo control sin navegar por zonas administrativas.',
                'accion_principal' => 'Ver mis incidencias',
                'url_principal' => url('/incidencias'),
                'secundaria' => 'Mi especialidad: ' . ($tecnico->especialidad->nombre_especialidad ?? 'Sin especialidad'),
                'url_secundaria' => url('/incidencias'),
                'metricas' => [
                    'Asignadas' => $total,
                    'En curso' => $abiertas,
                    'Pendientes' => $pendientes,
                    'Planificadas' => $asignadas,
                    'Finalizadas' => $finalizadas,
                    'Vencidas' => $vencidas,
                ],
                'porcentajes' => [
                    'Resolución' => $this->porcentaje($finalizadas, $total),
                    'En curso' => $this->porcentaje($abiertas, $total),
                    'Vencidas' => $this->porcentaje($vencidas, $total),
                ],
                'proxima' => Incidencia::with(['cliente', 'especialidad'])
                    ->where('tecnico_id', $tecnico->id)
                    ->whereIn('estado', ['Pendiente', 'Asignada'])
                    ->orderBy('fecha_servicio')
                    ->first()
            ];
        }

        return [
            'titulo' => 'ReparaYa',
            'subtitulo' => 'Entorno de gestión de reparaciones.',
            'accion_principal' => 'Ir al inicio',
            'url_principal' => route('home'),
            'secundaria' => null,
            'url_secundaria' => null,
            'metricas' => [],
            'porcentajes' => [],
            'proxima' => null
        ];
    }

    private function obtenerCalendarioPorRol(?array $usuario, string $tipoInicio): array
    {
        if ($usuario === null) {
            return [
                'calendarTitle' => null,
                'calendarSubtitle' => null,
                'calendarEvents' => [],
            ];
        }

        if ($tipoInicio === 'admin') {
            $incidencias = Incidencia::with(['cliente', 'tecnico', 'especialidad', 'comunidad'])
                ->orderBy('fecha_servicio')
                ->get();

            return [
                'calendarTitle' => 'Calendario global de servicios',
                'calendarSubtitle' => null,
                'calendarEvents' => $this->mapearIncidenciasParaCalendario($incidencias, 'admin'),
            ];
        }

        if ($tipoInicio === 'particular') {
            $incidencias = Incidencia::with(['tecnico', 'especialidad', 'comunidad'])
                ->where('cliente_id', $usuario['id'])
                ->orderBy('fecha_servicio')
                ->get();

            return [
                'calendarTitle' => 'Calendario de mis reparaciones',
                'calendarSubtitle' => null,
                'calendarEvents' => $this->mapearIncidenciasParaCalendario($incidencias, 'particular'),
            ];
        }

        if ($tipoInicio === 'tecnico') {
            $tecnico = Tecnico::where('usuario_id', $usuario['id'])->first();

            if (!$tecnico) {
                return [
                    'calendarTitle' => 'Calendario técnico',
                    'calendarSubtitle' => null,
                    'calendarEvents' => [],
                ];
            }

            $incidencias = Incidencia::with(['cliente', 'especialidad', 'comunidad'])
                ->where('tecnico_id', $tecnico->id)
                ->orderBy('fecha_servicio')
                ->get();

            return [
                'calendarTitle' => 'Calendario de servicios asignados',
                'calendarSubtitle' => null,
                'calendarEvents' => $this->mapearIncidenciasParaCalendario($incidencias, 'tecnico'),
            ];
        }

        return [
            'calendarTitle' => 'Calendario operativo',
            'calendarSubtitle' => null,
            'calendarEvents' => [],
        ];
    }

    private function mapearIncidenciasParaCalendario($incidencias, string $contexto): array
    {
        return $incidencias->map(function ($incidencia) use ($contexto) {
            $especialidad = $incidencia->especialidad->nombre_especialidad ?? 'Servicio';
            $codigo = $incidencia->localizador ?? 'INC-' . $incidencia->id;

            if ($incidencia->comunidad) {
                $titulo = 'B2B · ' . $incidencia->comunidad->nombre;
            } elseif ($contexto === 'tecnico') {
                $titulo = ($incidencia->cliente->nombre ?? 'Cliente') . ' · ' . $especialidad;
            } elseif ($contexto === 'particular') {
                $titulo = $especialidad . ' · ' . ($incidencia->tecnico->nombre_completo ?? 'Sin técnico');
            } else {
                $titulo = $especialidad . ' · ' . ($incidencia->cliente->nombre ?? 'Sin cliente');
            }

            return [
                'fecha' => $incidencia->fecha_servicio,
                'codigo' => $codigo,
                'titulo' => $titulo,
                'estado' => $incidencia->estado,
                'urgencia' => $incidencia->tipo_urgencia,
            ];
        })->toArray();
    }

    private function porcentaje(int $valor, int $total): int
    {
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($valor / $total) * 100);
    }
}