<?php

namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

class DashboardController
{
    public function dashboard()
    {
        $this->requireLogin('buyer');

        if (!empty($_SESSION['user_id'])) {
            $conn = Database::getConnection();
            $user = User::findById($conn, $_SESSION['user_id']);

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

        $this->renderView('buyer/dashboard');
    }

    public function adminDashboard()
    {
        $this->requireLogin('admin');

        try {
            $conn = Database::getConnection();
            $users = User::getUserCount($conn);

            $this->renderView('admin/dashboard', [
                'users' => $users,
                'error' => empty($users) ? "No users found in database" : null
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

        $this->renderView('seller/dashboard');
    }

    protected function countUsers()
    {

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
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }

}
?>