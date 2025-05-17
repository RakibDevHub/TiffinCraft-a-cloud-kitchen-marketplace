<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;

class PageController
{
    // TiffinCraft 
    public function buyer()
    {
        $this->renderView('buyer/home');
    }

    // TiffinCraft Business
    public function seller()
    {
        $this->renderView('seller/home');
    }


    public function dashboard()
    {
        $this->requireLogin('buyer');

        $this->renderView('buyer/dashboard');
    }

    public function adminDashboard()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/dashboard');
    }

    public function businessDashboard()
    {
        $this->requireLogin('seller');

        $this->renderView('seller/dashboard');
    }

    public function manageUsers()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/users');
    }

    public function manageOrders()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/orders');
    }

    public function manageReports()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/reports');
    }

    public function manageKitchens()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/kitchens');
    }

    public function manageDishes()
    {
        $this->requireLogin('admin');

        $this->renderView('admin/dishes');
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