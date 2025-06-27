<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Category;
use App\Utils\Helper;
use Exception;

class CategoryController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function manageCategories()
    {
        $this->requireLogin('admin');

        try {
            $this->renderView('admin/categories', [
                'categories' => Category::getAllCategories($this->conn),
                'success' => $this->getFlash('success'),
                'error' => $this->getFlash('error'),
            ]);
        } catch (Exception $e) {
            error_log('Failed to load categories: ' . $e->getMessage());
            $this->renderView('admin/categories', [
                'error' => 'Failed to load categories: ' . $e->getMessage(),
                'categories' => []
            ]);
        }
    }

    public function addCategory()
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard/categories');
            return;
        }

        $imagePath = null;
        $uploadedFilesToCleanup = [];

        try {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                throw new Exception('Category name is required');
            }

            if (preg_match('/^[0-9]+$/', $name)) {
                throw new Exception('Category name cannot be just numbers');
            }

            if (strlen($name) > 100) {
                throw new Exception('Category name must be less than 100 characters');
            }

            if (strlen($description) > 500) {
                throw new Exception('Description must be less than 500 characters');
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload('image');
                $uploadedFilesToCleanup[] = $imagePath;
            }

            $categoryId =
                Category::create($this->conn, [
                    'name' => $name,
                    'description' => $description,
                    'image' => $imagePath
                ]);

            if (!$categoryId) {
                throw new Exception('Failed to create category');
            }

            $this->setFlash('success', 'Category created successfully');
            $this->redirect('/admin/dashboard/categories');

        } catch (Exception $e) {
            if (!empty($uploadedFilesToCleanup)) {
                $this->cleanupUploadedFiles($uploadedFilesToCleanup);
            }

            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/dashboard/categories?add=true');
        }

    }

    public function editCategory($categoryId)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard/categories');
            return;
        }

        $imagePath = null;
        $newImagePath = null;

        try {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                throw new Exception('Category name is required');
            }

            if (preg_match('/^[0-9]+$/', $name)) {
                throw new Exception('Category name cannot be just numbers');
            }

            if (strlen($name) > 100) {
                throw new Exception('Category name must be less than 100 characters');
            }

            if (strlen($description) > 500) {
                throw new Exception('Description must be less than 500 characters');
            }

            $updateData = [
                'name' => $name,
                'description' => $description,
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $newImagePath = $this->handleImageUpload('image');
                $category = Category::getById($this->conn, $categoryId);
                $imagePath = $category['image'];
                $updateData['image'] = $newImagePath;
            }

            if (Category::update($this->conn, $categoryId, $updateData)) {
                if ($imagePath) {
                    $this->cleanupUploadedFiles([$imagePath]);
                }

                $this->setFlash('success', 'Category updated successfully');
                $this->redirect('/admin/dashboard/categories');
            } else {
                if ($newImagePath) {
                    $this->cleanupUploadedFiles([$newImagePath]);
                }
                throw new Exception('Failed to update category');
            }
        } catch (Exception $e) {
            if ($newImagePath) {
                $this->cleanupUploadedFiles([$newImagePath]);
            }

            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/dashboard/categories?edit=' . $categoryId);
        }

    }

    public function deleteCategory($categoryId)
    {
        $this->requireLogin('admin');
        $this->validateCsrf();

        try {
            $category = Category::getById($this->conn, $categoryId);
            $imagePath = $category['image'];

            if (Category::delete($this->conn, $categoryId)) {
                if ($imagePath) {
                    $this->cleanupUploadedFiles([$imagePath]);
                }

                $this->setFlash('success', 'Category deleted successfully');
            } else {
                throw new Exception('Failed to delete category');
            }
        } catch (Exception $e) {
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/dashboard/categories?delete=' . $categoryId);
        }
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

        $uploadsDir = __DIR__ . '/../../public/assets/uploads/category';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
            throw new Exception("Could not create upload directory.");
        }

        $newFileName = uniqid('', true) . '.' . $extension;
        $destination = $uploadsDir . '/' . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to save uploaded file.");
        }

        return '/assets/uploads/category/' . $newFileName;
    }

    protected function cleanupUploadedFiles(array $filePaths): void
    {
        $basePath = realpath(__DIR__ . '/../../public');
        $uploadsDir = realpath($basePath . '/assets/uploads/category');

        foreach ($filePaths as $file) {
            if (empty($file)) {
                continue;
            }

            try {
                $relativePath = ltrim(str_replace('/assets/uploads/category/', '', $file), '/');
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

    protected function validateCsrf(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['csrf_token']) ||
            !Helper::validateCsrfToken($_POST['csrf_token'])
        ) {
            $this->setFlash('error', "Invalid or missing CSRF token.");
            $this->redirect("/admin/categories");
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

    protected function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}