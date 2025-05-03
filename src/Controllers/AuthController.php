<?php
namespace App\Controllers;

use App\Core\Database;

use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;


class AuthController
{

    public function registerAsBuyer()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $conn = Database::getConnection();
            $uploadedImage = null;

            try {
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $phone_number = trim($_POST['phone_number']);
                $address = htmlspecialchars(trim($_POST['address']));
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);

                // Input validation
                if (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
                    throw new \Exception("Invalid name.");
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format.");
                }

                if (!preg_match("/^01\d{9}$/", $phone_number)) {
                    throw new \Exception("Invalid phone number.");
                }

                if (strlen($password) < 6) {
                    throw new \Exception("Password too short.");
                }

                if ($password !== $confirm_password) {
                    throw new \Exception("Passwords do not match.");
                }

                $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                $profile_image_path = $this->handleImageUpload('profile_image', '/register');
                $uploadedImage = $profile_image_path;

                $user = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password_hashed,
                    'phone_number' => $phone_number,
                    'address' => $address,
                    'profile_image' => $profile_image_path,
                    'role' => 'buyer'
                ];

                $user_id = User::registerBuyer($conn, $user);

                if ($user_id) {
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role'] = 'buyer';
                    $_SESSION['name'] = $name;
                    header("Location: /buyer/dashboard");
                    exit;
                } else {
                    throw new \Exception("Registration failed.");
                }

            } catch (\Exception $e) {
                // Delete image if it was uploaded
                if ($uploadedImage) {
                    $absolutePath = __DIR__ . '/../../public' . $uploadedImage;
                    if (file_exists($absolutePath)) {
                        unlink($absolutePath);
                    }
                }

                $_SESSION['register_error'] = $e->getMessage();
                header("Location: /register");
                exit;
            }
        }

        include __DIR__ . '/../views/auth/registerBuyer.php';
    }


    public function registerAsSeller()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = Database::getConnection();

            try {
                // Basic input handling and sanitization
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone_number']);
                $address = trim($_POST['address']);
                $kitchenName = trim($_POST['kitchen_name']);
                $kitchenAddress = trim($_POST['kitchen_address']);
                $kitchenDescription = trim($_POST['kitchen_description']);
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);
                $serviceAreas = $_POST['service_areas'] ?? '';

                // Input validation
                if (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
                    $_SESSION['register_error'] = "Invalid name.";
                    header("Location: /business/register");
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['register_error'] = "Invalid email format.";
                    header("Location: /business/register");
                    exit;
                }

                if (!preg_match("/^01\d{9}$/", $phone)) {
                    $_SESSION['register_error'] = "Invalid phone number.";
                    header("Location: /business/register");
                    exit;
                }

                if (strlen($password) < 6) {
                    $_SESSION['register_error'] = "Password must be at least 6 characters.";
                    header("Location: /business/register");
                    exit;
                }

                if ($password !== $confirm_password) {
                    $_SESSION['register_error'] = "Passwords do not match.";
                    header("Location: /business/register");
                    exit;
                }

                if (empty($kitchenName) || empty($kitchenAddress) || empty($kitchenDescription)) {
                    $_SESSION['register_error'] = "Fill up the empty fields.";
                    header("Location: /business/register");
                    exit;
                }

                $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

                // Track uploaded files for rollback
                $uploadedFiles = [];

                $profile_image_path = $this->handleImageUpload('profile_image', '/business/register');
                if ($profile_image_path)
                    $uploadedFiles[] = $profile_image_path;

                $kitchen_image_path = $this->handleImageUpload('kitchen_image', '/business/register');
                if ($kitchen_image_path)
                    $uploadedFiles[] = $kitchen_image_path;


                // Create user
                $userId = User::registerSeller($conn, [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => $passwordHashed,
                    'image' => $profile_image_path
                ]);

                $kitchenId = Kitchen::create($conn, [
                    'owner_id' => $userId,
                    'name' => $kitchenName,
                    'address' => $kitchenAddress,
                    'description' => $kitchenDescription,
                    'kitchen_image' => $kitchen_image_path
                ]);


                // Insert service areas
                $areas = array_filter(array_map('trim', explode(',', $serviceAreas)));
                foreach ($areas as $area) {
                    if (!ServiceArea::exists($conn, $kitchenId, $area)) {
                        ServiceArea::insert($conn, $kitchenId, $area);
                    }
                }

                oci_commit($conn);
                // Auto-login the seller
                $_SESSION['user_id'] = $userId;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = 'seller';

                header("Location: /business/dashboard");
                exit;

            } catch (Exception $e) {
                oci_rollback($conn);

                // Delete uploaded images if rollback happens
                foreach ($uploadedFiles as $file) {
                    $absolutePath = __DIR__ . '/../../public' . $file;

                    error_log("Trying to delete file: $absolutePath");
                    if (file_exists($absolutePath)) {
                        if (unlink($absolutePath)) {
                            error_log("File deleted: $absolutePath");
                        } else {
                            error_log("Failed to delete file: $absolutePath");
                        }
                    } else {
                        error_log("File not found: $absolutePath");
                    }

                    if (file_exists($absolutePath)) {
                        unlink($absolutePath);
                    }
                }

                $_SESSION['register_error'] = "Registration failed: " . $e->getMessage();
                header("Location: /business/register");
                exit;
            }

        }

        include __DIR__ . '/../views/auth/registerSeller.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = Database::getConnection();

            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = "Email and password are required.";
                header("Location: /login");
                exit;
            }

            $user = User::findByEmail($conn, $email);

            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['login_error'] = "Invalid email or password.";
                header("Location: /login");
                exit;
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            switch ($user['role']) {
                case 'buyer':
                    header("Location: /buyer/dashboard");
                    break;
                case 'seller':
                    header("Location: /seller/dashboard");
                    break;
                case 'admin':
                    header("Location: /admin");
                    break;
                default:
                    header("Location: /");
            }
            exit;
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit;
    }

    private function handleImageUpload($inputName, $redirectOnFail)
    {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$inputName]['tmp_name'];
            $fileName = basename($_FILES[$inputName]['name']);
            $fileSize = $_FILES[$inputName]['size'];
            $fileType = mime_content_type($fileTmpPath);

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['register_error'] = "Unsupported image type.";
                header("Location: $redirectOnFail");
                exit;
            }

            $uploadsDir = __DIR__ . '/../../public/uploads';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            $newFileName = uniqid() . '_' . $fileName;
            $destination = $uploadsDir . '/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                return '/uploads/' . $newFileName; // Relative URL to use in HTML or DB
            } else {
                $_SESSION['register_error'] = "Failed to move uploaded file.";
                header("Location: $redirectOnFail");
                exit;
            }
        }

        // Optional: return null or empty string if no file uploaded
        return null;
    }

}
