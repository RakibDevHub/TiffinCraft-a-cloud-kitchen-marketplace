<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Menu;

class MenuController
{
    public function manageMenu()
    {
        $this->requireLogin('seller');
        try {
            $conn = Database::getConnection();
            $owner_id = $_SESSION['user_id'];
            $menuItems = Menu::getItemsByOwner($conn, $owner_id);

            $this->renderView('seller/menu', [
                'menuItems' => $menuItems,
                'error' => empty($kitchens) ? "No items found" : null
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('seller/menu', [
                'menuItems' => [],
                'error' => "Database error: " . $e->getMessage()
            ]);
        }

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

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
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