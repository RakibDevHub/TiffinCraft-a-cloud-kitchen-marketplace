<?php
namespace App\Controllers;

use Exception;
use App\Models\Category;
use App\Models\Menu;
use App\Core\Database;
use App\Utils\Helper;

use App\Models\Kitchen;
use App\Models\ServiceArea;

class KitchenController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // kitchens
    public function kitchenPage()
    {
        try {
            $filters = [
                'search' => $_GET['search'] ?? null,
                'location' => $_GET['location'] ?? null,
                'sort' => $_GET['sort'] ?? 'newest',
                'page' => isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1,
                'per_page' => 9
            ];

            $kitchens = Kitchen::getFilteredKitchens($this->conn, $filters);
            $totalItems = Kitchen::getTotalFilteredCount($this->conn, $filters);
            $totalPages = ceil($totalItems / $filters['per_page']);

            $serviceAreas = ServiceArea::getAll($this->conn);

            $this->renderView('pages/kitchen', [
                'kitchens' => $kitchens,
                'locations' => $serviceAreas,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'page' => $filters['page']
            ]);

        } catch (Exception $e) {
            $this->renderView('pages/kitchen', [
                'kitchens' => [],
                'locations' => [],
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // kitchens/profile
    public function showKitchenProfile()
    {
        $kitchenId = $_GET['view'] ?? null;

        if (!$kitchenId || !is_numeric($kitchenId)) {
            header("Location: /kitchens");
            exit;
        }

        $kitchen = Kitchen::getKitchenById($this->conn, $kitchenId);
        $menuItems = Menu::getMenuItemsByKitchenId($this->conn, $kitchenId);
        $reviews = Kitchen::getKitchenReviews($this->conn, $kitchenId);
        $categories = Category::getAllCategories($this->conn);

        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['category_id']] = $category['name'];
        }

        foreach ($menuItems as &$item) {
            $item['category_name'] = $categoryMap[$item['category_id']] ?? 'Uncategorized';
        }

        $this->renderView('buyer/kitchenProfile', [
            'reviews' => $reviews,
            'kitchen' => $kitchen,
            'menuItems' => $menuItems,
            'categories' => $categories,
        ]);

    }


    // Admin Kitchen Management
    public function manageKitchens()
    {
        $this->requireLogin('admin');

        try {
            $kitchens = Kitchen::getAll($this->conn);

            $this->renderView('admin/kitchens', [
                'kitchens' => $kitchens,
                'success' => $this->getFlash('success'),
                'error' => $this->getFlash('error')
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('admin/kitchens', [
                'error' => 'Failed to load kitchens: ' . $e->getMessage(),
                'kitchens' => [],
            ]);
        }
    }

    public function approveKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = Kitchen::approve($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'Kitchen approved successfully');
            } else {
                throw new Exception('Failed to approve kitchen');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect("/admin/dashboard/kitchens");
    }

    public function rejectKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = Kitchen::reject($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'Kitchen rejected successfully');
            } else {
                throw new Exception('Failed to reject kitchen');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect("/admin/dashboard/kitchens");
    }

    public function suspendKitchen($id)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $success = Kitchen::suspend($this->conn, $id);
            if ($success) {
                $this->setFlash('success', 'Kitchen suspended successfully');
            } else {
                throw new Exception('Failed to suspend kitchen');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect("/admin/dashboard/kitchens");
    }
    // End Admin Kitchen Management

    // CSRF Token Validation
    protected function validateCsrf(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['csrf_token']) ||
            !Helper::validateCsrfToken($_POST['csrf_token'])
        ) {
            $this->setFlash('error', "Invalid or missing CSRF token.");
            $this->redirect("/admin/kitchens");
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
    // End Flash

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