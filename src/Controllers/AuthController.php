<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Utils\Helper;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

class AuthController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $this->handleLogin();
            return;
        }

        $this->renderView('pages/login', [
            'error' => $this->getFlash('error'),
            'success' => $this->getFlash('success')
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }

    public function registerAsBuyer()
    {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $this->handleBuyerRegistration();
            return;
        }

        $this->renderView('pages/register', [
            'error' => $this->getFlash('error'),
            'success' => $this->getFlash('success')
        ]);
    }

    public function registerAsSeller()
    {
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $this->handleSellerRegistration();
            return;
        }

        $this->renderView('pages/register', [
            'error' => $this->getFlash('error'),
            'success' => $this->getFlash('success')
        ]);
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function handleLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', "Email and password are required.");
            $this->redirect('/login');
        }

        $user = User::findByEmail($this->conn, $email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->setFlash('error', "Invalid email or password.");
            $this->redirect('/login');
        }

        $this->startUserSession($user['user_id']);
        $this->setFlash('success', "Login successful!");
        $this->redirectToDashboard($user['role']);
    }

    protected function handleBuyerRegistration()
    {
        $uploadedFilesToCleanup = [];

        try {
            $data = $this->validateBuyerInput($_POST);

            $profileImage = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $profileImage = $this->handleImageUpload('profile_image');
                $uploadedFilesToCleanup[] = $profileImage;
            }

            $checkEmail = User::findByEmail($this->conn, $data['email']);
            if ($checkEmail) {
                throw new Exception("Email already exists.");
            }

            $userId = User::registerUser($this->conn, [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'address' => $data['address'],
                'profile_image' => $profileImage,
                'role' => 'buyer',
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            if (!$userId) {
                throw new Exception("Failed to register user.");
            }

            oci_commit($this->conn);

            $this->startUserSession($userId);
            $this->setFlash('success', "Registration successful!");
            $this->redirect('/dashboard');

        } catch (Exception $e) {
            if ($this->conn) {
                oci_rollback($this->conn);
            }

            if (!empty($uploadedFilesToCleanup)) {
                $this->cleanupUploadedFiles($uploadedFilesToCleanup);
            }

            $this->setFlash('error', "Registration failed: " . $e->getMessage());
            $this->redirect('/register');
        }
    }

    protected function handleSellerRegistration()
    {
        $uploadedFilesToCleanup = [];

        try {
            $data = $this->validateSellerInput($_POST);

            $profileImage = null;
            $kitchenImage = null;

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $profileImage = $this->handleImageUpload('profile_image');
                $uploadedFilesToCleanup[] = $profileImage;
            }

            if (isset($_FILES['kitchen_image']) && $_FILES['kitchen_image']['error'] === UPLOAD_ERR_OK) {
                $kitchenImage = $this->handleImageUpload('kitchen_image');
                $uploadedFilesToCleanup[] = $kitchenImage;
            }

            $checkEmail = User::findByEmail($this->conn, $data['email']);
            if ($checkEmail) {
                throw new Exception("Email already exists.");
            }

            $userId = User::registerUser($this->conn, [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone'],
                'address' => $data['address'],
                'profile_image' => $profileImage,
                'role' => 'seller',
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            if (!$userId) {
                throw new Exception("Failed to register user.");
            }

            $kitchenId = Kitchen::create($this->conn, [
                'owner_id' => $userId,
                'name' => $data['kitchen_name'],
                'address' => $data['kitchen_address'],
                'description' => $data['kitchen_description'],
                'kitchen_image' => $kitchenImage
            ]);

            if (!$kitchenId) {
                throw new Exception("Failed to create kitchen.");
            }

            if (!empty($data['service_areas']) && is_array($data['service_areas'])) {
                $this->processServiceAreas($this->conn, $kitchenId, $data['service_areas']);
            }

            oci_commit($this->conn);

            $this->startUserSession($userId);
            $this->setFlash('success', "Registration successful!");
            $this->redirect('/business/dashboard');

        } catch (Exception $e) {
            if ($this->conn) {
                oci_rollback($this->conn);
            }

            if (!empty($uploadedFilesToCleanup)) {
                $this->cleanupUploadedFiles($uploadedFilesToCleanup);
            }

            $this->setFlash('error', "Registration failed: " . $e->getMessage());
            $this->redirect('/business/register');
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
            $this->redirect("/");
        }
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    protected function validateBuyerInput(array $data): array
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone_number'] ?? '');
        $address = trim($data['address'] ?? '');
        $password = $data['password'] ?? '';
        $confirm_password = trim($data['confirm_password'] ?? '');

        if (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
            throw new Exception("Invalid name.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (!preg_match("/^01\d{9}$/", $phone)) {
            throw new Exception("Invalid phone number.");
        }

        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters.");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'password' => $password
        ];
    }

    protected function validateSellerInput(array $data): array
    {
        $validated = $this->validateBuyerInput($data);

        $kitchenName = trim($data['kitchen_name'] ?? '');
        $kitchenAddress = trim($data['kitchen_address'] ?? '');
        $kitchenDescription = trim($data['kitchen_description'] ?? '');
        $serviceAreas = trim($data['service_areas'] ?? '');

        if (empty($kitchenName) || empty($kitchenAddress) || empty($kitchenDescription)) {
            throw new Exception("Please fill all required fields.");
        }

        return array_merge($validated, [
            'kitchen_name' => $kitchenName,
            'kitchen_address' => $kitchenAddress,
            'kitchen_description' => $kitchenDescription,
            'service_areas' => $serviceAreas
        ]);
    }

    protected function handleImageUpload($inputName): string
    {
        if (!isset($_FILES[$inputName])) {
            throw new Exception('No file was uploaded');
        }

        if ($_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error');
        }

        $file = $_FILES[$inputName];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Unsupported image type.");
        }

        $uploadsDir = __DIR__ . '/../../public/assets/uploads';
        if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true)) {
            throw new Exception("Could not create upload directory.");
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $extension;
        $destination = $uploadsDir . '/' . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to save uploaded file.");
        }

        return '/assets/uploads/' . $newFileName;
    }

    protected function cleanupUploadedFiles(array $filePaths): void
    {
        $basePath = realpath(__DIR__ . '/../../public');
        $uploadsDir = realpath($basePath . '/assets/uploads');

        foreach ($filePaths as $file) {
            if (empty($file)) {
                continue;
            }

            try {
                $relativePath = ltrim(str_replace('/assets/uploads/', '', $file), '/');
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

    protected function processServiceAreas($conn, $kitchenId, $areasInput): void
    {
        $areas = array_filter(array_map('trim', explode(',', $areasInput)));

        foreach ($areas as $area) {
            try {
                if (!ServiceArea::exists($conn, $kitchenId, $area)) {
                    ServiceArea::insert($conn, $kitchenId, $area);
                }
            } catch (Exception $e) {
                throw new Exception("Failed to process service area '$area': " . $e->getMessage());
            }
        }
    }

    protected function startUserSession($userId): void
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    protected function redirectToDashboard(string $role): void
    {

        $routes = [
            'buyer' => '/dashboard',
            'seller' => '/business/dashboard',
            'admin' => '/admin',
        ];
        $this->redirect($routes[$role] ?? '/');
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