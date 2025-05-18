<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

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

    public function approveKitchen($id)
    {
        // Verify CSRF token first
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Invalid CSRF token";
            $this->redirect("/admin/kitchens");
        }

        try {
            $conn = Database::getConnection();
            Kitchen::approve($conn, $id);
            $_SESSION['success'] = "Kitchen approved successfully";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: /admin/kitchens");
        exit();
    }

    public function rejectKitchen($id)
    {
        // Similar CSRF verification
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Invalid CSRF token";
            header("Location: /admin/kitchens");
            exit();
        }

        try {
            $conn = Database::getConnection();
            Kitchen::reject($conn, $id);
            $_SESSION['success'] = "Kitchen rejected successfully";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: /admin/kitchens");
        exit();
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