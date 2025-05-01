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

            $profile_image_path = $this->handleImageUpload('profile_image', '/register');

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
            $conn = Database::getConnection();

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
                $kitchenDescription = trim($_POST['kitchen_description']);
                $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
                $serviceAreas = $_POST['service_areas'] ?? '';

                $profile_image_path = $this->handleImageUpload('profile_image', '/business/register');
                $kitchen_image_path = $this->handleImageUpload('kitchen_image', '/business/register');


                // Create user
                $userId = User::registerSeller($conn, [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => $password,
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

    private function handleImageUpload($inputName, $redirectPath = '/register')
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file_tmp = $_FILES[$inputName]['tmp_name'];
        $file_name = $_FILES[$inputName]['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($file_ext, $valid_extensions)) {
            $_SESSION['register_error'] = "Invalid image type for $inputName.";
            header("Location: $redirectPath");
            exit;
        }

        $unique_name = uniqid() . '.' . $file_ext;
        $upload_dir = BASE_PATH . '/public/assets/upload/';
        $upload_path = $upload_dir . $unique_name;

        if (!move_uploaded_file($file_tmp, $upload_path)) {
            $_SESSION['register_error'] = "Image upload failed for $inputName.";
            header("Location: $redirectPath");
            exit;
        }

        return '/assets/upload/' . $unique_name;
    }

}
