<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Category;
use App\Models\Menu;
use App\Models\ServiceArea;
use App\Utils\Helper;
use Exception;

class MenuController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // dishes
    public function MenuItemPage()
    {
        try {
            $perPage = 9;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

            $filters = [
                'category' => isset($_GET['category']) ? $_GET['category'] : null,
                'search' => isset($_GET['search']) ? trim($_GET['search']) : null,
                'location' => isset($_GET['location']) ? $_GET['location'] : null,
                'price' => isset($_GET['price']) ? $_GET['price'] : null,

                'per_page' => $perPage,
                'page' => $page
            ];

            $totalItems = Menu::getTotalFilteredCount($this->conn, $filters);
            $totalPages = max(1, ceil($totalItems / $perPage));

            $page = max(1, min($page, $totalPages));
            $filters['page'] = $page;

            $menuItems = Menu::getFilteredMenuItems($this->conn, $filters);

            $categories = Category::getAllCategories($this->conn);
            $serviceAreas = ServiceArea::getAll($this->conn);

            $categoryMap = [];
            foreach ($categories as $category) {
                $categoryMap[$category['category_id']] = $category['name'];
            }

            foreach ($menuItems as &$item) {
                $item['category_name'] = $categoryMap[$item['category_id']] ?? 'Uncategorized';
            }

            $this->renderView('pages/menu', [
                'menuItems' => $menuItems,
                'categories' => $categories,
                'locations' => $serviceAreas,
                'totalItems' => $totalItems,
                'totalPages' => $totalPages,
                'currentPage' => $page
            ]);

        } catch (Exception $e) {
            $this->renderView('pages/menu', [
                'menuItems' => [],
                'categories' => [],
                'locations' => [],
                'error' => "Error: " . $e->getMessage()
            ]);
        }
    }

    // business/dashboard/menu
    public function manageMenuPage()
    {
        $this->requireLogin('seller');

        $success = $this->getFlash('success');
        $error = $this->getFlash('error');

        try {
            $owner_id = $_SESSION['user_id'];
            $perPage = 6;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

            // Get filter parameters
            $filters = [
                'owner_id' => $owner_id,
                'category' => isset($_GET['category']) ? $_GET['category'] : null,
                'search' => isset($_GET['search']) ? trim($_GET['search']) : null,
                'status' => isset($_GET['status']) ? $_GET['status'] : null,
                'per_page' => $perPage,
                'page' => $page
            ];

            // Get filtered items and counts
            $totalItems = Menu::getFilteredCountForOwner($this->conn, $filters);
            $totalPages = max(1, ceil($totalItems / $perPage));
            $page = max(1, min($page, $totalPages));
            $filters['page'] = $page;

            $menuItems = Menu::getFilteredItemsForOwner($this->conn, $filters);
            $categories = Category::getAllCategories($this->conn);

            // Map category IDs to names
            $categoryMap = [];
            foreach ($categories as $category) {
                $categoryMap[$category['category_id']] = $category['name'];
            }

            foreach ($menuItems as &$item) {
                $item['category_name'] = $categoryMap[$item['category_id']] ?? 'Uncategorized';
            }

            $this->renderView('seller/menu', [
                'menuItems' => $menuItems,
                'categories' => $categories,
                'totalItems' => $totalItems,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'success' => $success,
                'error' => $error
            ]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->renderView('seller/menu', [
                'menuItems' => [],
                'categories' => [],
                'error' => "Failed to load menu: " . $e->getMessage()
            ]);
        }
    }

    public function addMenuItem()
    {
        $this->requireLogin('seller');
        $this->validateCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/business/dashboard/menu');
            return;
        }

        $imagePath = null;
        $uploadedFilesToCleanup = [];

        try {
            $name = $this->validateName($_POST['name'] ?? '');
            $description = $this->validateDescription($_POST['description'] ?? '');
            $price = $this->validatePrice($_POST['price'] ?? 0);
            $categoryId = $this->validateCategoryId($_POST['category_id'] ?? 0);
            $available = isset($_POST['available']) ? 1 : 0;
            $tags = $this->validateTags($_POST['tags'] ?? '');
            $ownerId = $_SESSION['user_id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload('image');
                $uploadedFilesToCleanup[] = $imagePath;
            }

            $itemId = Menu::create($this->conn, [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category_id' => $categoryId,
                'available' => $available,
                'tags' => $tags,
                'item_image' => $imagePath,
                'owner_id' => $ownerId
            ]);

            if (!$itemId) {
                throw new Exception('Failed to add menu item');
            }

            $this->setFlash('success', 'Menu item added successfully');
            $this->redirect('/business/dashboard/menu');

        } catch (Exception $e) {
            if (!empty($uploadedFilesToCleanup)) {
                $this->cleanupUploadedFiles($uploadedFilesToCleanup);
            }

            $this->setFlash('error', $e->getMessage());
            $this->redirect('/business/dashboard/menu?add=true');
        }

    }

    public function editMenuItem($itemId)
    {
        $this->requireLogin('seller');
        $this->validateCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/business/dashboard/menu');
            return;
        }

        $newImagePath = null;
        $oldImagePath = null;

        try {
            $ownerId = $_SESSION['user_id'];

            if (!Menu::isOwner($this->conn, $itemId, $ownerId)) {
                throw new Exception('You are not authorized to edit this item');
            }

            $name = $this->validateName($_POST['name'] ?? '');
            $description = $this->validateDescription($_POST['description'] ?? '');
            $price = $this->validatePrice($_POST['price'] ?? 0);
            $categoryId = $this->validateCategoryId($_POST['category_id'] ?? 0);
            $available = isset($_POST['available']) ? 1 : 0;
            $tags = $this->validateTags($_POST['tags'] ?? '');

            $updateData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'category_id' => $categoryId,
                'available' => $available,
                'tags' => $tags,
                'owner_id' => $ownerId
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $newImagePath = $this->handleImageUpload('image');
                $menuItem = Menu::getItem($this->conn, $ownerId, $itemId);
                $oldImagePath = $menuItem['image'] ?? null;
                $updateData['item_image'] = $newImagePath;
            }

            if (Menu::update($this->conn, $itemId, $updateData)) {
                if ($oldImagePath) {
                    $this->cleanupUploadedFiles([$oldImagePath]);
                }

                $this->setFlash('success', 'Menu updated successfully');
                $this->redirect('/business/dashboard/menu');
                return;
            } else {
                if ($newImagePath) {
                    $this->cleanupUploadedFiles([$newImagePath]);
                }

                throw new Exception('Failed to update menu item');
            }

        } catch (Exception $e) {
            if ($newImagePath) {
                $this->cleanupUploadedFiles([$newImagePath]);
            }

            $this->setFlash('error', $e->getMessage());
            $this->redirect('/business/dashboard/menu?edit=' . $itemId);
        }
    }

    public function deleteMenuItem($itemId)
    {
        $this->requireLogin('seller');
        $this->validateCsrf();

        try {
            $ownerId = $_SESSION['user_id'];
            if (!Menu::isOwner($this->conn, $itemId, $ownerId)) {
                throw new Exception('You are not authorized to delete this item');
            }

            $menuItem = Menu::getItem($this->conn, $ownerId, $itemId);
            $imagePath = $menuItem['item_image'] ?? null;

            if (Menu::delete($this->conn, $itemId)) {

                if ($imagePath) {
                    $this->cleanupUploadedFiles([$imagePath]);
                }

                $this->setFlash('success', 'Menu item deleted successfully');
                $this->redirect('/business/dashboard/menu');
            } else {
                throw new Exception('Failed to delete menu item');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/business/dashboard/menu?delete=' . $itemId);
        }

    }

    protected function validateName(string $name): string
    {
        $name = trim($name);

        if (empty($name)) {
            throw new Exception('Item name is required');
        }

        if (mb_strlen($name) > 100) {
            throw new Exception('Item name must be 100 characters or less');
        }

        if (preg_match('/^[\d\W]+$/', $name)) {
            throw new Exception('Item name cannot be only numbers or symbols');
        }

        if (!preg_match('/^[\p{L}\p{N}\s\-\'"&,.()\p{Sc}]+$/u', $name)) {
            throw new Exception('Only letters, numbers, and common punctuation like - \' " & , . ( ) $ are allowed');
        }

        return $name;
    }

    protected function validateDescription(string $description): string
    {
        $description = trim($description);

        if (mb_strlen($description) > 500) {
            throw new Exception('Description cannot exceed 500 characters');
        }

        if ($description !== strip_tags($description)) {
            throw new Exception('HTML tags are not allowed');
        }

        if (preg_match('/<script|<\/script>|on\w+=|javascript:/i', $description)) {
            throw new Exception('Invalid content detected');
        }

        return $description;
    }

    protected function validatePrice($price): float
    {
        if (!is_numeric($price)) {
            throw new Exception('Price must be a valid number');
        }

        $price = (float) $price;

        if ($price <= 0) {
            throw new Exception('Price must be greater than 0');
        }

        if ($price > 10000) {
            throw new Exception('Maximum price is 10,000');
        }

        if (round($price, 2) != $price) {
            throw new Exception('Price can have maximum 2 decimal places');
        }

        return round($price, 2);
    }

    protected function validateTags(string $tags): string
    {
        $tags = trim($tags);

        if (mb_strlen($tags) > 255) {
            throw new Exception('Tags cannot exceed 255 characters');
        }

        if (!empty($tags)) {
            $tagList = array_map('trim', explode(',', $tags));

            foreach ($tagList as $index => $tag) {
                if (empty($tag)) {
                    unset($tagList[$index]);
                    continue;
                }

                if (mb_strlen($tag) > 30) {
                    throw new Exception('Each tag must be 30 characters or less');
                }

                if (!preg_match('/^[\p{L}\p{N}\s\-]+$/u', $tag)) {
                    throw new Exception('Tags can only contain letters, numbers, spaces and hyphens');
                }

                $tagList[$index] = mb_strtolower($tag);
            }

            $tagList = array_unique($tagList);

            $tagList = array_values($tagList);
            $tags = implode(',', $tagList);
        }

        return $tags;
    }

    protected function validateCategoryId($categoryId): int
    {
        if (!is_numeric($categoryId) || $categoryId <= 0) {
            throw new Exception('Category is required');
        }

        if (!Category::getById($this->conn, $categoryId)) {
            throw new Exception('Selected category does not exist');
        }

        return (int) $categoryId;
    }

    protected function handleImageUpload($inputName): string
    {
        if (!isset($_FILES[$inputName])) {
            throw new Exception('No file was uploaded');
        }

        $file = $_FILES[$inputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error');
        }

        // Validate file size (max 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception("Image exceeds maximum size of 5MB.");
        }

        // Get MIME type safely
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Unsupported image type.");
        }

        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Invalid file extension.");
        }

        $uploadsDir = __DIR__ . '/../../public/assets/uploads/menu';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
            throw new Exception("Could not create upload directory.");
        }

        $newFileName = uniqid('', true) . '.' . $extension;
        $destination = $uploadsDir . '/' . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to save uploaded file.");
        }

        return '/assets/uploads/menu/' . $newFileName;
    }

    protected function cleanupUploadedFiles(array $filePaths): void
    {
        $basePath = realpath(__DIR__ . '/../../public');
        $uploadsDir = realpath($basePath . '/assets/uploads/menu');

        foreach ($filePaths as $file) {
            if (empty($file)) {
                continue;
            }

            try {
                $relativePath = ltrim(str_replace('/assets/uploads/menu/', '', $file), '/');
                $absolutePath = realpath($uploadsDir . '/' . $relativePath);

                if ($absolutePath && strpos($absolutePath, $uploadsDir) === 0) {
                    if (file_exists($absolutePath) && is_writable($absolutePath)) {
                        unlink($absolutePath);
                    }
                }
            } catch (Exception $e) {
                error_log("Error deleting file: " . $e->getMessage());
            }
        }
    }

    protected function validateCsrf(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['csrf_token']) ||
            !Helper::validateCsrfToken($_POST['csrf_token'])
        ) {
            $this->setFlash('error', 'Invalid or missing CSRF token');
            $this->redirect('/business/dashboard/menu');
            exit;
        }
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


    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
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

    protected function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}