<?php

namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;

class DashboardController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function dashboard()
    {
        $this->requireLogin('buyer');

        if (!empty($_SESSION['user_id'])) {
            $user = User::findById($this->conn, $_SESSION['user_id']);

            $isSuspended = false;
            $suspendedUntil = null;

            if (!empty($user['suspended_until'])) {
                $timestamp = strtotime($user['suspended_until']);
                if ($timestamp > time()) {
                    $isSuspended = true;
                    $suspendedUntil = $user['suspended_until'];
                }
            }

            $_SESSION['is_suspended'] = $isSuspended;
            $_SESSION['suspended_until'] = $suspendedUntil;
        }

        $this->renderView('buyer/dashboard', [
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }

    public function adminDashboard()
    {
        $this->requireLogin('admin');

        try {
            $users = User::getUserCount($this->conn);

            $this->renderView('admin/dashboard', [
                'users' => $users,
                'success' => $this->getFlash('success'),
                'error' => $this->getFlash('error')
            ]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/dashboard', [
                'users' => [],
                'error' => "Database error: " . $e->getMessage()
            ]);
        }
    }

    public function businessDashboard()
    {
        $this->requireLogin('seller');

        $this->renderView('seller/dashboard', [
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ]);
    }

    protected function countUsers()
    {

    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function requireLogin($requiredRoles = null): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            exit;
        }

        if ($requiredRoles) {
            if (is_array($requiredRoles)) {
                if (!in_array($_SESSION['role'], $requiredRoles)) {
                    $this->redirect('/unauthorized');
                    exit;
                }
            } else {
                if ($_SESSION['role'] !== $requiredRoles) {
                    $this->redirect('/unauthorized');
                    exit;
                }
            }
        }
    }


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

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }

}
?>