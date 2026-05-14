<?php

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();
        require __DIR__ . '/../app/views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../app/views/layouts/main.php';
    }

    protected function url(string $path = ''): string
    {
        return base_url($path);
    }

    protected function redirect(string $path = ''): void
    {
        redirect_to($path);
    }

    protected function requireLogin(): void
    {
        if (!isset($_SESSION['usuario'])) {
            $this->redirect('login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireLogin();

        if (($_SESSION['usuario']['rol'] ?? '') !== 'admin') {
            $this->redirect();
        }
    }

    protected function requireTecnico(): void
    {
        $this->requireLogin();

        if (($_SESSION['usuario']['rol'] ?? '') !== 'tecnico') {
            $this->redirect();
        }
    }

    protected function requireParticular(): void
    {
        $this->requireLogin();

        if (($_SESSION['usuario']['rol'] ?? '') !== 'particular') {
            $this->redirect();
        }
    }

    protected function redirectIfLoggedIn(): void
    {
        if (isset($_SESSION['usuario'])) {
            $this->redirect();
        }
    }
}