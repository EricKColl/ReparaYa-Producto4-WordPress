<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Especialidad.php';

class EspecialidadController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $especialidadModel = new Especialidad();
        $especialidades = $especialidadModel->getAll();

        $this->render('especialidades/index', [
            'title' => 'Listado de especialidades - ReparaYa',
            'especialidades' => $especialidades
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->render('especialidades/create', [
            'title' => 'Crear especialidad - ReparaYa'
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('especialidades');
        }

        $nombreEspecialidad = trim($_POST['nombre_especialidad'] ?? '');

        if ($nombreEspecialidad === '') {
            $this->render('especialidades/create', [
                'title' => 'Crear especialidad - ReparaYa',
                'error' => 'El nombre de la especialidad es obligatorio.'
            ]);
            return;
        }

        $especialidadModel = new Especialidad();
        $especialidadModel->create($nombreEspecialidad);

        $this->redirect('especialidades?ok=creada');
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('especialidades');
        }

        $especialidadModel = new Especialidad();
        $especialidad = $especialidadModel->getById($id);

        if (!$especialidad) {
            $this->redirect('especialidades');
        }

        $this->render('especialidades/edit', [
            'title' => 'Editar especialidad - ReparaYa',
            'especialidad' => $especialidad
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('especialidades');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $nombreEspecialidad = trim($_POST['nombre_especialidad'] ?? '');

        if ($id <= 0) {
            $this->redirect('especialidades');
        }

        if ($nombreEspecialidad === '') {
            $especialidadModel = new Especialidad();
            $especialidad = $especialidadModel->getById($id);

            if (!$especialidad) {
                $this->redirect('especialidades');
            }

            $this->render('especialidades/edit', [
                'title' => 'Editar especialidad - ReparaYa',
                'error' => 'El nombre de la especialidad es obligatorio.',
                'especialidad' => $especialidad
            ]);
            return;
        }

        $especialidadModel = new Especialidad();
        $especialidadModel->update($id, $nombreEspecialidad);

        $this->redirect('especialidades?ok=actualizada');
    }

    public function delete(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('especialidades');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('especialidades');
        }

        $especialidadModel = new Especialidad();

        try {
            $especialidadModel->delete($id);
            $this->redirect('especialidades?ok=eliminada');
        } catch (PDOException $e) {
            $this->redirect('especialidades?error=en_uso');
        }
    }
}