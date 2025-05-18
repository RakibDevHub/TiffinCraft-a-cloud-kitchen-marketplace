<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

class PageController
{
    // TiffinCraft 
    public function buyer()
    {
        $this->renderView('buyer/home', );
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

        try {
            $conn = Database::getConnection();
            $users = User::getUsers($conn);

            $this->renderView('admin/users', [
                'users' => $users,
                'error' => empty($users) ? "No users found in database" : null
            ]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/users', [
                'users' => [],
                'error' => "Database error: " . $e->getMessage()
            ]);
        }
    }

    public function manageKitchens()
    {
        $this->requireLogin('admin');

        try {
            $conn = Database::getConnection();
            $kitchens = Kitchen::getAll($conn);

            $this->renderView('admin/kitchens', [
                'kitchens' => $kitchens,
                'error' => empty($kitchens) ? "No kitchens found in database" : null
            ]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/kitchens', [
                'kitchens' => [],
                'error' => "Database error: " . $e->getMessage()
            ]);
        }
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

    protected function renderView(string $viewPath, $data = []): void
    {
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}

?>