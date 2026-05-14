<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->getAll();

        $this->render('usuarios/index', [
            'title' => 'Listado de usuarios - ReparaYa',
            'usuarios' => $usuarios
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->render('usuarios/create', [
            'title' => 'Crear usuario - ReparaYa'
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('usuarios');
        }

        $usuarioModel = new Usuario();

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rol' => $_POST['rol'] ?? 'particular',
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if ($data['nombre'] === '' || $data['email'] === '' || $data['password'] === '') {
            $this->redirect('usuarios/create?error=campos_vacios');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('usuarios/create?error=email_invalido');
        }

        $emailExistente = $usuarioModel->getByEmail($data['email']);

        if ($emailExistente) {
            $this->redirect('usuarios/create?error=email_duplicado');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $usuarioModel->create($data);

        $this->redirect('usuarios');
    }

    public function edit(): void
    {
        $this->requireAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $this->redirect('usuarios');
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);

        if (!$usuario) {
            $this->redirect('usuarios');
        }

        $this->render('usuarios/edit', [
            'title' => 'Editar usuario - ReparaYa',
            'usuario' => $usuario
        ]);
    }

    public function update(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('usuarios');
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id <= 0) {
            $this->redirect('usuarios');
        }

        $usuarioModel = new Usuario();
        $usuarioActual = $usuarioModel->getById($id);

        if (!$usuarioActual) {
            $this->redirect('usuarios');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rol' => $_POST['rol'] ?? 'particular',
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if ($data['nombre'] === '' || $data['email'] === '') {
            $this->redirect('usuarios/edit?id=' . $id . '&error=campos_vacios');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('usuarios/edit?id=' . $id . '&error=email_invalido');
        }

        $emailExistente = $usuarioModel->getByEmailExcludingId($data['email'], $id);

        if ($emailExistente) {
            $this->redirect('usuarios/edit?id=' . $id . '&error=email_duplicado');
        }

        if ($data['password'] === '') {
            $data['password'] = $usuarioActual['password'];
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $usuarioModel->update($id, $data);

        $this->redirect('usuarios');
    }

    public function delete(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('usuarios');
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id <= 0) {
            $this->redirect('usuarios');
        }

        $usuarioModel = new Usuario();
        $usuarioModel->delete($id);

        $this->redirect('usuarios');
    }

    public function loginForm(): void
    {
        $this->redirectIfLoggedIn();

        $this->render('auth/login', [
            'title' => 'Iniciar sesión - ReparaYa'
        ]);
    }

    public function login(): void
    {
        $this->redirectIfLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->redirect('login?error=campos_vacios');
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $this->redirect('login?error=credenciales_invalidas');
        }

        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol']
        ];

        $this->redirect();
    }

    public function logout(): void
    {
        unset($_SESSION['usuario']);
        session_destroy();

        $this->redirect('login');
    }

    public function profile(): void
    {
        $this->requireLogin();

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById((int) $_SESSION['usuario']['id']);

        if (!$usuario) {
            $this->redirect('logout');
        }

        $this->render('usuarios/profile', [
            'title' => 'Mi perfil - ReparaYa',
            'usuario' => $usuario
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('perfil');
        }

        $id = (int) $_SESSION['usuario']['id'];

        $usuarioModel = new Usuario();
        $usuarioActual = $usuarioModel->getById($id);

        if (!$usuarioActual) {
            $this->redirect('logout');
        }

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rol' => $usuarioActual['rol'],
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if ($data['nombre'] === '' || $data['email'] === '') {
            $this->redirect('perfil?error=campos_vacios');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('perfil?error=email_invalido');
        }

        $emailExistente = $usuarioModel->getByEmailExcludingId($data['email'], $id);

        if ($emailExistente) {
            $this->redirect('perfil?error=email_duplicado');
        }

        if ($data['password'] === '') {
            $data['password'] = $usuarioActual['password'];
        } else {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $usuarioModel->update($id, $data);

        $_SESSION['usuario']['nombre'] = $data['nombre'];
        $_SESSION['usuario']['email'] = $data['email'];

        $this->redirect('perfil?ok=perfil_actualizado');
    }

    public function registerForm(): void
    {
        $this->redirectIfLoggedIn();

        $this->render('auth/register', [
            'title' => 'Registro - ReparaYa'
        ]);
    }

    public function register(): void
    {
        $this->redirectIfLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('register');
        }

        $usuarioModel = new Usuario();

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
            'rol' => 'particular',
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if ($data['nombre'] === '' || $data['email'] === '' || $data['password'] === '') {
            $this->redirect('register?error=campos_vacios');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->redirect('register?error=email_invalido');
        }

        $emailExistente = $usuarioModel->getByEmail($data['email']);

        if ($emailExistente) {
            $this->redirect('register?error=email_duplicado');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $usuarioModel->create($data);

        $this->redirect('login');
    }
}