<?php
namespace App\Controllers;

use App\Utils\Helper;
use Exception;
use App\Core\Database;
use App\Models\User;

class UserController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function manageUsers()
    {
        $this->requireLogin('admin');

        try {
            $users = User::getUsers($this->conn);

            $this->renderView('admin/users', [
                'users' => $users,
                'success' => $this->getFlash('success'),
                'error' => $this->getFlash('error')
            ]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/users', [
                'error' => "Failed to load users:" . $e->getMessage(),
                'users' => [],
            ]);
        }
    }

    public function activateUser($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = User::activate($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'User activate successfully');
            } else {
                throw new Exception('Failed to activate user');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }
        $this->redirect("/admin/dashboard/users");
    }

    public function deactivateUser($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = User::deactivate($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'User deactivate successfully');
            } else {
                throw new Exception('Failed to deactivate user');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }
        $this->redirect("/admin/dashboard/users");
    }

    public function suspendUser($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = User::suspend($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'User suspended successfully');
            } else {
                throw new Exception('Failed to suspended user');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }
        $this->redirect("/admin/dashboard/users");
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

    protected function validateCsrf(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['csrf_token']) ||
            !Helper::validateCsrfToken($_POST['csrf_token'])
        ) {
            $this->setFlash('error', "Invalid or missing CSRF token.");
            $this->redirect("/admin/dashboard/users");
        }
    }

    // Flash message
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
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
?>