<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\Kitchen;

class KitchenController
{
    // For Admin 
    public function fetchKitchens()
    {
        $this->requireLogin('admin');

        try {
            $conn = Database::getConnection();
            $kitchens = Kitchen::getAll($conn);

            $this->renderView('admin/kitchens', [
                'kitchens' => $kitchens,
                'error' => empty($kitchens) ? "No kitchens found" : null
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/kitchens', [
                'kitchens' => [],
                'error' => "Database error: " . $e->getMessage()
            ]);
        }
    }

    public function approveKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrfToken();

        try {
            $conn = Database::getConnection();
            Kitchen::approve($conn, $id);
            $_SESSION['success'] = "Kitchen approved successfully";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect("/admin/kitchens");
    }

    public function rejectKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrfToken();

        try {
            $conn = Database::getConnection();
            Kitchen::reject($conn, $id);
            $_SESSION['success'] = "Kitchen rejected successfully";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect("/admin/kitchens");
    }

    public function suspendKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrfToken();

        try {
            $conn = Database::getConnection();
            Kitchen::suspend($conn, $id);
            $_SESSION['success'] = "Kitchen rejected successfully";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect("/admin/kitchens");
    }

    public function viewKitchen($id)
    {
        $this->requireLogin('admin');

        try {
            $conn = Database::getConnection();
            $kitchen = Kitchen::getById($conn, $id);

            if (!$kitchen) {
                throw new Exception("Kitchen not found");
            }

            $this->renderView('admin/viewKitchen', $kitchen);
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect("/admin/kitchens");
        }
    }


    protected function validateCsrfToken(): void
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Invalid CSRF token";
            $this->redirect("/admin/kitchens");
        }
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function requireLogin(string $requiredRole = null): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }

        if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
            $this->redirect('/unauthorized');
        }
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}