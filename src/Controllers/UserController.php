<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

class UserController
{

    // TiffinCraft 
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