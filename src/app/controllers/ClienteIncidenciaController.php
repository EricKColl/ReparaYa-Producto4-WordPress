<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Incidencia.php';

class ClienteIncidenciaController extends Controller
{
    private function normalizarTipoUrgencia(string $tipo): string
    {
        return $tipo === 'Urgente' ? 'Urgente' : 'Estandar';
    }

    private function obtenerContextoFormulario(array $old = []): array
    {
        $especialidadModel = new Especialidad();
        $usuarioModel = new Usuario();

        $usuario = $usuarioModel->getById((int) $_SESSION['usuario']['id']);

        return [
            'especialidades' => $especialidadModel->getAll(),
            'usuario' => $usuario,
            'old' => $old
        ];
    }

    private function faltan48Horas(string $fechaServicio): bool
    {
        $timestampServicio = strtotime($fechaServicio);

        if ($timestampServicio === false) {
            return true;
        }

        return $timestampServicio < (time() + (48 * 60 * 60));
    }

    private function puedeCancelar(array $incidencia): bool
    {
        if (!in_array($incidencia['estado'], ['Pendiente', 'Asignada'], true)) {
            return false;
        }

        $timestampServicio = strtotime($incidencia['fecha_servicio']);

        if ($timestampServicio === false) {
            return false;
        }

        return $timestampServicio >= (time() + (48 * 60 * 60));
    }

    private function motivoNoCancelable(array $incidencia): string
    {
        if ($incidencia['estado'] === 'Cancelada') {
            return 'Este aviso ya ha sido cancelado.';
        }

        if ($incidencia['estado'] === 'Finalizada') {
            return 'La intervención ya está finalizada.';
        }

        $timestampServicio = strtotime($incidencia['fecha_servicio']);

        if ($timestampServicio !== false && $timestampServicio < (time() + (48 * 60 * 60))) {
            return 'No es posible cancelar avisos cuando faltan menos de 48 horas para la cita.';
        }

        return 'Este aviso no admite cancelación en su estado actual.';
    }

    public function index(): void
    {
        $this->requireParticular();

        $incidenciaModel = new Incidencia();
        $incidencias = $incidenciaModel->getByClienteId((int) $_SESSION['usuario']['id']);

        foreach ($incidencias as &$incidencia) {
            $incidencia['puede_cancelar'] = $this->puedeCancelar($incidencia);
            $incidencia['motivo_no_cancelable'] = $incidencia['puede_cancelar']
                ? ''
                : $this->motivoNoCancelable($incidencia);
        }
        unset($incidencia);

        $this->render('cliente_incidencias/index', [
            'title' => 'Mis avisos - ReparaYa',
            'incidencias' => $incidencias
        ]);
    }

    public function create(): void
    {
        $this->requireParticular();

        $contexto = $this->obtenerContextoFormulario();

        $this->render('cliente_incidencias/create', [
            'title' => 'Nueva solicitud - ReparaYa',
            'especialidades' => $contexto['especialidades'],
            'usuario' => $contexto['usuario'],
            'old' => $contexto['old']
        ]);
    }

    public function store(): void
    {
        $this->requireParticular();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nueva-solicitud');
        }

        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $tipoUrgencia = $this->normalizarTipoUrgencia($_POST['tipo_urgencia'] ?? 'Estandar');
        $fechaServicio = trim($_POST['fecha_servicio'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        $old = [
            'especialidad_id' => $especialidadId,
            'tipo_urgencia' => $tipoUrgencia,
            'fecha_servicio' => $fechaServicio,
            'direccion' => $direccion,
            'telefono_contacto' => $telefonoContacto,
            'descripcion' => $descripcion,
        ];

        $contexto = $this->obtenerContextoFormulario($old);
        $usuario = $contexto['usuario'];

        if (!$usuario) {
            $this->redirect('logout');
        }

        if ($especialidadId <= 0 || $fechaServicio === '' || $direccion === '' || $telefonoContacto === '' || $descripcion === '') {
            $this->render('cliente_incidencias/create', [
                'title' => 'Nueva solicitud - ReparaYa',
                'error' => 'Todos los campos del formulario son obligatorios.',
                'especialidades' => $contexto['especialidades'],
                'usuario' => $usuario,
                'old' => $old
            ]);
            return;
        }

        $timestampServicio = strtotime($fechaServicio);

        if ($timestampServicio === false) {
            $this->render('cliente_incidencias/create', [
                'title' => 'Nueva solicitud - ReparaYa',
                'error' => 'La fecha y hora introducidas no son válidas.',
                'especialidades' => $contexto['especialidades'],
                'usuario' => $usuario,
                'old' => $old
            ]);
            return;
        }

        if ($timestampServicio <= time()) {
            $this->render('cliente_incidencias/create', [
                'title' => 'Nueva solicitud - ReparaYa',
                'error' => 'La fecha del servicio debe ser posterior al momento actual.',
                'especialidades' => $contexto['especialidades'],
                'usuario' => $usuario,
                'old' => $old
            ]);
            return;
        }

        if ($tipoUrgencia === 'Estandar' && $this->faltan48Horas($fechaServicio)) {
            $this->render('cliente_incidencias/create', [
                'title' => 'Nueva solicitud - ReparaYa',
                'error' => 'Las solicitudes de tipo estándar deben pedirse con un mínimo de 48 horas de antelación.',
                'especialidades' => $contexto['especialidades'],
                'usuario' => $usuario,
                'old' => $old
            ]);
            return;
        }

        $incidenciaModel = new Incidencia();

        $creada = $incidenciaModel->create([
            'cliente_id' => (int) $usuario['id'],
            'especialidad_id' => $especialidadId,
            'descripcion' => $descripcion,
            'direccion' => $direccion,
            'telefono_contacto' => $telefonoContacto,
            'fecha_servicio' => $fechaServicio,
            'tipo_urgencia' => $tipoUrgencia,
        ]);

        if (!$creada) {
            $this->render('cliente_incidencias/create', [
                'title' => 'Nueva solicitud - ReparaYa',
                'error' => 'No se pudo registrar la solicitud. Vuelve a intentarlo.',
                'especialidades' => $contexto['especialidades'],
                'usuario' => $usuario,
                'old' => $old
            ]);
            return;
        }

        $this->redirect('mis-avisos?ok=creada');
    }

    public function cancel(): void
    {
        $this->requireParticular();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('mis-avisos');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('mis-avisos?error=aviso_invalido');
        }

        $clienteId = (int) $_SESSION['usuario']['id'];

        $incidenciaModel = new Incidencia();
        $incidencia = $incidenciaModel->getByIdAndClienteId($id, $clienteId);

        if (!$incidencia) {
            $this->redirect('mis-avisos?error=aviso_no_encontrado');
        }

        if (!$this->puedeCancelar($incidencia)) {
            $this->redirect('mis-avisos?error=fuera_de_plazo');
        }

        $cancelada = $incidenciaModel->cancel($id);

        if (!$cancelada) {
            $this->redirect('mis-avisos?error=cancelacion_fallida');
        }

        $this->redirect('mis-avisos?ok=cancelada');
    }
}