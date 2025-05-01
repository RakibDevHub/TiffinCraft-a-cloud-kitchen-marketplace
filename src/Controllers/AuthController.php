<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

use App\Core\Database;

class AuthController
{

    public function registerAsBuyer()
    {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $conn = $this->getDBConnection();

            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone_number = trim($_POST['phone_number']);
            $address = htmlspecialchars(trim($_POST['address']));
            $password = trim($_POST['password']);

            if (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
                $_SESSION['register_error'] = "Invalid name.";
                header("Location: /register");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['register_error'] = "Invalid email format.";
                header("Location: /register");
                exit;
            }

            if (!preg_match("/^01\d{9}$/", $phone_number)) {
                $_SESSION['register_error'] = "Invalid phone number.";
                header("Location: /register");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['register_error'] = "Password too short.";
                header("Location: /register");
                exit;
            }

            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $profile_image_path = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_name = $_FILES['profile_image']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($file_ext, $valid_extensions)) {
                    $_SESSION['register_error'] = "Invalid image type.";
                    header("Location: /register");
                    exit;
                }

                $unique_name = uniqid() . '.' . $file_ext;
                $upload_dir = BASE_PATH . '/public/assets/upload/';
                $upload_path = $upload_dir . $unique_name;

                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $_SESSION['register_error'] = "Image upload failed.";
                    header("Location: /register");
                    exit;
                }

                $profile_image_path = '/assets/upload/' . $unique_name;
            }

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
                $_SESSION['register_error'] = "Registration failed.";
                header("Location: /register");
                exit;
            }
        }

        include __DIR__ . '/../views/auth/registerBuyer.php';
    }

    public function registerAsSeller()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = $this->getDBConnection();

            // Begin transaction
            oci_execute(oci_parse($conn, "BEGIN"), OCI_NO_AUTO_COMMIT);

            try {
                // Basic input handling and sanitization
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $phone = trim($_POST['phone_number']);
                $address = trim($_POST['address']);
                $kitchenName = trim($_POST['kitchen_name']);
                $kitchenAddress = trim($_POST['kitchen_address']);
                $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                $serviceAreas = $_POST['service_areas'] ?? '';

                // Handle image upload
                $imagePath = null;
                if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['profile_image']['tmp_name'];
                    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
                    $imagePath = '/uploads/' . uniqid('seller_', true) . '.' . $ext;
                    move_uploaded_file($tmpName, BASE_PATH . '/public' . $imagePath);
                }

                // Create user
                $userId = User::registerSeller($conn, [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => $password,
                    'image' => $imagePath
                ]);

                // Create kitchen
                $kitchenId = Kitchen::create($conn, [
                    'owner_id' => $userId,
                    'name' => $kitchenName,
                    'address' => $kitchenAddress
                ]);

                // Insert service areas
                $areas = array_filter(array_map('trim', explode(',', $serviceAreas)));
                foreach ($areas as $area) {
                    if (!ServiceArea::exists($conn, $kitchenId, $area)) {
                        ServiceArea::insert($conn, $kitchenId, $area);
                    }
                }

                oci_commit($conn); // All good

                $_SESSION['register_success'] = "Seller registered successfully!";
                header("Location: /login");
                exit;

            } catch (Exception $e) {
                oci_rollback($conn); // Roll back if anything fails
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
            $conn = $this->getDBConnection();

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
}
