<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Tecnico.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Incidencia.php';

class TecnicoController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $tecnicoModel = new Tecnico();
        $tecnicos = $tecnicoModel->getAll();

        $this->render('tecnicos/index', [
            'title' => 'Listado de técnicos - ReparaYa',
            'tecnicos' => $tecnicos
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $especialidadModel = new Especialidad();
        $usuarioModel = new Usuario();

        $this->render('tecnicos/create', [
            'title' => 'Crear técnico - ReparaYa',
            'especialidades' => $especialidadModel->getAll(),
            'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles()
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tecnicos');
        }

        $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        if ($usuarioId <= 0 || $nombreCompleto === '' || $especialidadId <= 0) {
            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            $this->render('tecnicos/create', [
                'title' => 'Crear técnico - ReparaYa',
                'error' => 'La cuenta de usuario técnico, el nombre y la especialidad son obligatorios.',
                'especialidades' => $especialidadModel->getAll(),
                'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles()
            ]);
            return;
        }

        $tecnicoModel = new Tecnico();

        try {
            $tecnicoModel->create($usuarioId, $nombreCompleto, $especialidadId, $disponible);
            $this->redirect('tecnicos?ok=creado');
        } catch (PDOException $e) {
            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            $this->render('tecnicos/create', [
                'title' => 'Crear técnico - ReparaYa',
                'error' => 'Ese usuario técnico ya está vinculado a otra ficha de técnico.',
                'especialidades' => $especialidadModel->getAll(),
                'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles()
            ]);
            return;
        }
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('tecnicos');
        }

        $tecnicoModel = new Tecnico();
        $tecnico = $tecnicoModel->getById($id);

        if (!$tecnico) {
            $this->redirect('tecnicos');
        }

        $especialidadModel = new Especialidad();
        $usuarioModel = new Usuario();

        $this->render('tecnicos/edit', [
            'title' => 'Editar técnico - ReparaYa',
            'tecnico' => $tecnico,
            'especialidades' => $especialidadModel->getAll(),
            'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles($id)
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tecnicos');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $usuarioId = (int) ($_POST['usuario_id'] ?? 0);
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        if ($id <= 0) {
            $this->redirect('tecnicos');
        }

        if ($usuarioId <= 0 || $nombreCompleto === '' || $especialidadId <= 0) {
            $tecnicoModel = new Tecnico();
            $tecnico = $tecnicoModel->getById($id);

            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            if ($tecnico) {
                $tecnico['usuario_id'] = $usuarioId;
                $tecnico['nombre_completo'] = $nombreCompleto;
                $tecnico['especialidad_id'] = $especialidadId;
                $tecnico['disponible'] = $disponible;
            }

            $this->render('tecnicos/edit', [
                'title' => 'Editar técnico - ReparaYa',
                'error' => 'La cuenta de usuario técnico, el nombre y la especialidad son obligatorios.',
                'tecnico' => $tecnico,
                'especialidades' => $especialidadModel->getAll(),
                'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles($id)
            ]);
            return;
        }

        $tecnicoModel = new Tecnico();

        try {
            $tecnicoModel->update($id, $usuarioId, $nombreCompleto, $especialidadId, $disponible);
            $this->redirect('tecnicos?ok=actualizado');
        } catch (PDOException $e) {
            $tecnico = $tecnicoModel->getById($id);

            $especialidadModel = new Especialidad();
            $usuarioModel = new Usuario();

            if ($tecnico) {
                $tecnico['usuario_id'] = $usuarioId;
                $tecnico['nombre_completo'] = $nombreCompleto;
                $tecnico['especialidad_id'] = $especialidadId;
                $tecnico['disponible'] = $disponible;
            }

            $this->render('tecnicos/edit', [
                'title' => 'Editar técnico - ReparaYa',
                'error' => 'Ese usuario técnico ya está vinculado a otra ficha de técnico.',
                'tecnico' => $tecnico,
                'especialidades' => $especialidadModel->getAll(),
                'usuariosTecnicos' => $usuarioModel->getUsuariosTecnicosDisponibles($id)
            ]);
            return;
        }
    }

    public function delete(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tecnicos');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('tecnicos');
        }

        $tecnicoModel = new Tecnico();

        try {
            $tecnicoModel->delete($id);
            $this->redirect('tecnicos?ok=eliminado');
        } catch (PDOException $e) {
            $this->redirect('tecnicos?error=en_uso');
        }
    }

    public function agenda(): void
    {
        $this->requireTecnico();

        $usuarioId = (int) ($_SESSION['usuario']['id'] ?? 0);

        $tecnicoModel = new Tecnico();
        $incidenciaModel = new Incidencia();

        $tecnico = $tecnicoModel->getByUsuarioId($usuarioId);

        if (!$tecnico) {
            $this->render('tecnicos/agenda', [
                'title' => 'Mi agenda - ReparaYa',
                'tecnico' => null,
                'incidencias' => [],
                'sinVinculo' => true
            ]);
            return;
        }

        $incidencias = $incidenciaModel->getByTecnicoId((int) $tecnico['id']);

        $this->render('tecnicos/agenda', [
            'title' => 'Mi agenda - ReparaYa',
            'tecnico' => $tecnico,
            'incidencias' => $incidencias,
            'sinVinculo' => false
        ]);
    }
}