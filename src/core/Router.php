<?php

class Router
{
    public function dispatch(string $path): void
    {
        if ($path === '/public' || $path === '/public/' || $path === '/public/index.php') {
            require_once __DIR__ . '/../app/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            return;
        }

        if ($path === '/public/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->loginForm();
            return;
        }

        if ($path === '/public/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->login();
            return;
        }

        if ($path === '/public/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->registerForm();
            return;
        }

        if ($path === '/public/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->register();
            return;
        }

        if ($path === '/public/logout' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->logout();
            return;
        }

        if ($path === '/public/perfil' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->profile();
            return;
        }

        if ($path === '/public/perfil/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->updateProfile();
            return;
        }

        if ($path === '/public/usuarios' || $path === '/public/usuarios/') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->index();
            return;
        }

        if ($path === '/public/usuarios/create' || $path === '/public/usuarios/create/') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->create();
            return;
        }

        if ($path === '/public/usuarios/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->store();
            return;
        }

        if ($path === '/public/usuarios/edit' || $path === '/public/usuarios/edit/') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->edit();
            return;
        }

        if ($path === '/public/usuarios/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->update();
            return;
        }

        if ($path === '/public/usuarios/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/UsuarioController.php';
            $controller = new UsuarioController();
            $controller->delete();
            return;
        }

        if ($path === '/public/especialidades') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->index();
            return;
        }

        if ($path === '/public/especialidades/create') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->create();
            return;
        }

        if ($path === '/public/especialidades/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->store();
            return;
        }

        if ($path === '/public/especialidades/edit') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->edit();
            return;
        }

        if ($path === '/public/especialidades/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->update();
            return;
        }

        if ($path === '/public/especialidades/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/EspecialidadController.php';
            $controller = new EspecialidadController();
            $controller->delete();
            return;
        }

        if ($path === '/public/tecnicos') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->index();
            return;
        }

        if ($path === '/public/tecnicos/create') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->create();
            return;
        }

        if ($path === '/public/tecnicos/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->store();
            return;
        }

        if ($path === '/public/tecnicos/edit') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->edit();
            return;
        }

        if ($path === '/public/tecnicos/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->update();
            return;
        }

        if ($path === '/public/tecnicos/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->delete();
            return;
        }

        if ($path === '/public/mi-agenda' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/TecnicoController.php';
            $controller = new TecnicoController();
            $controller->agenda();
            return;
        }

        if ($path === '/public/mis-avisos' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/ClienteIncidenciaController.php';
            $controller = new ClienteIncidenciaController();
            $controller->index();
            return;
        }

        if ($path === '/public/mis-avisos/cancelar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/ClienteIncidenciaController.php';
            $controller = new ClienteIncidenciaController();
            $controller->cancel();
            return;
        }

        if ($path === '/public/nueva-solicitud' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/ClienteIncidenciaController.php';
            $controller = new ClienteIncidenciaController();
            $controller->create();
            return;
        }

        if ($path === '/public/nueva-solicitud/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/ClienteIncidenciaController.php';
            $controller = new ClienteIncidenciaController();
            $controller->store();
            return;
        }

        if ($path === '/public/admin' || $path === '/public/admin/') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->index();
            return;
        }

        if ($path === '/public/admin/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->create();
            return;
        }

        if ($path === '/public/admin/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->store();
            return;
        }

        if ($path === '/public/admin/edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->edit();
            return;
        }

        if ($path === '/public/admin/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->update();
            return;
        }

        if ($path === '/public/admin/asignar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->asignar();
            return;
        }

        if ($path === '/public/admin/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->delete();
            return;
        }

        if ($path === '/public/admin/destroy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->destroy();
            return;
        }

        if ($path === '/public/admin/calendario' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->calendario();
            return;
        }

        http_response_code(404);
        require __DIR__ . '/../app/views/errors/404.php';
    }
}