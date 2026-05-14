<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Incidencia.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Usuario.php';

class AdminController extends Controller
{
    private function normalizarTipoUrgencia(string $tipo): string
    {
        return $tipo === 'Urgente' ? 'Urgente' : 'Estandar';
    }

    private function normalizarTelefono(string $telefono): string
    {
        return trim($telefono);
    }

    public function index(): void
    {
        $this->requireAdmin();

        $incidenciaModel = new Incidencia();
        $tecnicoModel = new Tecnico();

        $incidencias = $incidenciaModel->getAll(true);

        $tecnicosPorEspecialidad = [];
        foreach ($incidencias as $inc) {
            $espId = $inc['especialidad_id'];
            if (!isset($tecnicosPorEspecialidad[$espId])) {
                $tecnicosPorEspecialidad[$espId] = $tecnicoModel->getDisponiblesByEspecialidad($espId);
            }
        }

        $this->render('admin/index', [
            'title' => 'Panel de Administración - ReparaYa',
            'incidencias' => $incidencias,
            'tecnicosPorEspecialidad' => $tecnicosPorEspecialidad,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $especialidadModel = new Especialidad();
        $usuarioModel = new Usuario();

        $this->render('admin/create', [
            'title' => 'Nueva Incidencia - ReparaYa',
            'especialidades' => $especialidadModel->getAll(),
            'clientes' => $usuarioModel->getClientes(),
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $especialidad = (int) ($_POST['especialidad_id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefono = $this->normalizarTelefono($_POST['telefono_contacto'] ?? '');
        $fecha = trim($_POST['fecha_servicio'] ?? '');
        $urgencia = $this->normalizarTipoUrgencia($_POST['tipo_urgencia'] ?? 'Estandar');

        if (!$clienteId || !$especialidad || !$descripcion || !$direccion || !$telefono || !$fecha) {
            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            $this->render('admin/create', [
                'title' => 'Nueva Incidencia - ReparaYa',
                'error' => 'Todos los campos son obligatorios.',
                'especialidades' => $especialidadModel->getAll(),
                'clientes' => $usuarioModel->getClientes(),
            ]);
            return;
        }

        $incidenciaModel = new Incidencia();
        $creada = $incidenciaModel->create([
            'cliente_id' => $clienteId,
            'especialidad_id' => $especialidad,
            'descripcion' => $descripcion,
            'direccion' => $direccion,
            'telefono_contacto' => $telefono,
            'fecha_servicio' => $fecha,
            'tipo_urgencia' => $urgencia,
        ]);

        if (!$creada) {
            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            $this->render('admin/create', [
                'title' => 'Nueva Incidencia - ReparaYa',
                'error' => 'No se pudo crear la incidencia.',
                'especialidades' => $especialidadModel->getAll(),
                'clientes' => $usuarioModel->getClientes(),
            ]);
            return;
        }

        $this->redirect('admin?ok=creada');
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('admin');
        }

        $incidenciaModel = new Incidencia();
        $incidencia = $incidenciaModel->getById($id);

        if (!$incidencia) {
            $this->redirect('admin');
        }

        $especialidadModel = new Especialidad();

        $this->render('admin/edit', [
            'title' => 'Editar Incidencia - ReparaYa',
            'incidencia' => $incidencia,
            'especialidades' => $especialidadModel->getAll(),
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('admin');
        }

        $incidenciaModel = new Incidencia();

        $actualizada = $incidenciaModel->update($id, [
            'especialidad_id' => (int) ($_POST['especialidad_id'] ?? 0),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'telefono_contacto' => $this->normalizarTelefono($_POST['telefono_contacto'] ?? ''),
            'fecha_servicio' => trim($_POST['fecha_servicio'] ?? ''),
            'tipo_urgencia' => $this->normalizarTipoUrgencia($_POST['tipo_urgencia'] ?? 'Estandar'),
            'estado' => $_POST['estado'] ?? 'Pendiente',
        ]);

        if (!$actualizada) {
            $this->redirect('admin?ok=error');
        }

        $this->redirect('admin?ok=actualizada');
    }

    public function asignar(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['incidencia_id'] ?? 0);
        $tecnicoId = (int) ($_POST['tecnico_id'] ?? 0);

        if ($id > 0 && $tecnicoId > 0) {
            $incidenciaModel = new Incidencia();
            $incidenciaModel->asignarTecnico($id, $tecnicoId);
        }

        $this->redirect('admin?ok=asignado');
    }

    public function delete(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $incidenciaModel = new Incidencia();
            $incidenciaModel->cancel($id);
        }

        $this->redirect('admin?ok=cancelada');
    }

    public function destroy(): void
    {
        $this->requireAdmin();

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $incidenciaModel = new Incidencia();
            $incidenciaModel->deletePermanent($id);
        }

        $this->redirect('admin?ok=eliminada');
    }

    public function calendario(): void
    {
        $this->requireAdmin();

        $incidenciaModel = new Incidencia();
        $incidencias = $incidenciaModel->getAll(false);

        $this->render('admin/calendario', [
            'title' => 'Calendario - ReparaYa',
            'incidencias' => $incidencias,
        ]);
    }
}