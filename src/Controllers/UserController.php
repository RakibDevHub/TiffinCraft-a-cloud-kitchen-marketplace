<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;

class UserController
{
    public function adminDashboard()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/dashboard');
    }

    public function businessDashboard()
    {
        $this->requireLogin('seller');

        $this->renderView('business/dashboard');
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function requireLogin(string $requiredRole = null): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            exit;
        }

        if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
            $this->redirect('/unauthorized');
            exit;
        }
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function renderView(string $viewPath): void
    {
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}

?>