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

    public function login()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        // Handle POST request (login form submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token'])) {
                $token = $_POST['csrf_token'];
                $success = Helper::validateCsrfToken($token);
                if ($success) {
                    $this->handleLogin();
                    return;
                } else {
                    // CSRF token is invalid
                    $_SESSION['login_error'] = "Invalid request. Security token mismatch.";
                    $this->redirect('/login');
                }
            } else {
                // CSRF token is missing
                $_SESSION['login_error'] = "Invalid request. Missing security token.";
                $this->redirect('/login');
            }
        }

        // Show login page for GET requests
        $this->renderView('auth/login');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }

    public function registerAsBuyer()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token'])) {
                $token = $_POST['csrf_token'];
                $success = Helper::validateCsrfToken($token);
                if ($success) {
                    $this->handleBuyerRegistration();
                    return;
                } else {
                    // CSRF token is invalid
                    $_SESSION['login_error'] = "Invalid request. Security token mismatch.";
                    $this->redirect('/register');
                }
            } else {
                // CSRF token is missing
                $_SESSION['login_error'] = "Invalid request. Missing security token.";
                $this->redirect('/register');
            }
        }

        $this->renderView('auth/registerBuyer');
    }

    public function registerAsSeller()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token'])) {
                $token = $_POST['csrf_token'];
                $success = Helper::validateCsrfToken($token);
                if ($success) {
                    $this->handleSellerRegistration();
                    return;
                } else {
                    // CSRF token is invalid
                    $_SESSION['login_error'] = "Invalid request. Security token mismatch.";
                    $this->redirect('/register');
                }
            } else {
                // CSRF token is missing
                $_SESSION['login_error'] = "Invalid request. Missing security token.";
                $this->redirect('/register');
            }
        }


        $this->renderView('auth/registerSeller');
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function handleLogin()
    {
        $conn = Database::getConnection();
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->setFlashError('login_error', "Email and password are required.");
            $this->redirect('/login');
        }

        $user = User::findByEmail($conn, $email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->setFlashError('login_error', "Invalid email or password.");
            $this->redirect('/login');
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate token
        $this->startUserSession($user);
        $this->redirectToDashboard($user['role']);

    }

    protected function handleBuyerRegistration()
    {
        $conn = Database::getConnection();
        $uploadedImage = null;

        try {
            oci_execute(oci_parse($conn, "BEGIN"));

            $data = $this->validateBuyerInput($_POST);
            $uploadedImage = $this->handleImageUpload('profile_image');

            $user_id = User::registerBuyer($conn, [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'phone_number' => $data['phone'],
                'address' => $data['address'],
                'profile_image' => $uploadedImage,
                'role' => 'buyer'
            ]);

            if (!$user_id) {
                throw new Exception("Registration failed.");
            }

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->startUserSession([
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'buyer',
                'profile_image' => $uploadedImage
            ]);
            $this->redirect('/buyer/dashboard');

        } catch (Exception $e) {
            if ($conn) {
                oci_execute(oci_parse($conn, "ROLLBACK"));
            }
            $this->cleanupUploadedFiles([$uploadedImage]);
            $this->setFlashError('register_error', $e->getMessage());
            $this->redirect('/register');
        }
    }

    protected function handleSellerRegistration()
    {
        $conn = Database::getConnection();
        $uploadedFiles = [];

        try {
            oci_execute(oci_parse($conn, "BEGIN"));

            $data = $this->validateSellerInput($_POST);

            $profileImage = $this->handleImageUpload('profile_image');
            $kitchenImage = $this->handleImageUpload('kitchen_image');

            if ($profileImage)
                $uploadedFiles[] = $profileImage;
            if ($kitchenImage)
                $uploadedFiles[] = $kitchenImage;

            $userId = User::registerSeller($conn, [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'image' => $profileImage
            ]);

            $kitchenId = Kitchen::create($conn, [
                'owner_id' => $userId,
                'name' => $data['kitchen_name'],
                'address' => $data['kitchen_address'],
                'description' => $data['kitchen_description'],
                'kitchen_image' => $kitchenImage
            ]);

            $this->processServiceAreas($conn, $kitchenId, $data['service_areas']);

            oci_commit($conn);

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->startUserSession([
                'user_id' => $userId,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => 'seller',
                'profile_image' => $profileImage
            ]);
            $this->redirect('/business/dashboard');

        } catch (Exception $e) {
            if ($conn) {
                oci_execute(oci_parse($conn, "ROLLBACK"));
            }
            $this->cleanupUploadedFiles($uploadedFiles);
            $this->setFlashError('register_error', "Registration failed: " . $e->getMessage());
            $this->redirect('/business/register');
        }
    }

    protected function validateBuyerInput(array $data): array
    {
        $name = htmlspecialchars(trim($data['name'] ?? ''));
        $email = htmlspecialchars(trim($data['email'] ?? ''));
        $phone = htmlspecialchars(trim($data['phone_number'] ?? ''));
        $address = htmlspecialchars(trim($data['address'] ?? ''));
        $password = trim($data['password'] ?? '');
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

        $kitchenName = htmlspecialchars(trim($data['kitchen_name'] ?? ''));
        $kitchenAddress = htmlspecialchars(trim($data['kitchen_address'] ?? ''));
        $kitchenDescription = htmlspecialchars(trim($data['kitchen_description'] ?? ''));
        $serviceAreas = htmlspecialchars(trim($data['service_areas'] ?? ''));

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
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return '';
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
                // Remove leading slash if present and ensure we're using the correct path prefix
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

    protected function startUserSession(array $userData): void
    {
        $_SESSION['user_id'] = $userData['user_id'];
        $_SESSION['role'] = $userData['role'];
        $_SESSION['name'] = $userData['name'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['profile_image'] = $userData['profile_image'];

    }

    protected function redirectToDashboard(string $role): void
    {
        $routes = [
            'buyer' => '/',
            'seller' => '/business/dashboard',
            'admin' => '/admin',
        ];
        $this->redirect($routes[$role] ?? '/');
    }

    protected function setFlashError(string $key, string $message): void
    {
        $_SESSION[$key] = $message;
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