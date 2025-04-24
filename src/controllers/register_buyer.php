<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize name (alphabetic characters and spaces only)
    $name = $_POST['name'];
    if (!preg_match("/^[a-zA-Z\s.]+$/", $name)) {
        echo "Invalid name. Only alphabetic characters and spaces are allowed.";
        exit;
    }

    // Validate and sanitize email
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Validate and sanitize phone number (must be 11 digits starting with '01')
    $phone_number = $_POST['phone_number'];
    if (!preg_match("/^01\d{9}$/", $phone_number)) {
        echo "Invalid phone number. It must be 11 digits and start with '01'.";
        exit;
    }

    // Sanitize address
    $address = htmlspecialchars($_POST['address']);

    // Validate password (simple check for length)
    $password = $_POST['password'];
    if (strlen($password) < 6) {
        echo "Password must be at least 6 characters long.";
        exit;
    }

    // Hash password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile image upload (optional)
    $profile_image_path = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Validate the file type
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_name = $_FILES['profile_image']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Check if the file is a valid image
        if (!in_array($file_ext, $valid_extensions)) {
            echo "Invalid image type. Allowed types: JPG, JPEG, PNG, GIF, WEBP.";
            exit;
        }

        // Check initial file size (if larger than 2MB, we will resize)
        $file_size = $_FILES['profile_image']['size'];
        if ($file_size > 2 * 1024 * 1024) {
            // Resize the image
            $image = null;
            switch ($file_ext) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file_tmp);
                    break;
                case 'png':
                    $image = imagecreatefrompng($file_tmp);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file_tmp);
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($file_tmp);
                    break;
                default:
                    echo "Unsupported image type for resizing.";
                    exit;
            }

            // Get original dimensions
            $original_width = imagesx($image);
            $original_height = imagesy($image);

            // Calculate new dimensions (keep the aspect ratio)
            $max_dimension = 1000; // Maximum dimension (e.g., resize to 1000px on the longest side)
            if ($original_width > $original_height) {
                $new_width = $max_dimension;
                $new_height = ($original_height / $original_width) * $new_width;
            } else {
                $new_height = $max_dimension;
                $new_width = ($original_width / $original_height) * $new_height;
            }

            // Create a new image with the new dimensions
            $resized_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

            // Save the resized image
            $unique_name = uniqid() . '.' . $file_ext;
            $upload_dir = BASE_PATH . '/public/assets/upload/';
            $upload_path = $upload_dir . $unique_name;

            // Save the resized image
            switch ($file_ext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($resized_image, $upload_path, 90); // 90 is the quality (0-100)
                    break;
                case 'png':
                    imagepng($resized_image, $upload_path);
                    break;
                case 'gif':
                    imagegif($resized_image, $upload_path);
                    break;
                case 'webp':
                    imagewebp($resized_image, $upload_path);
                    break;
            }

            // Free up memory
            imagedestroy($image);
            imagedestroy($resized_image);
        } else {
            // Move the file if it's smaller than 2MB
            $unique_name = uniqid() . '.' . $file_ext;
            $upload_dir = BASE_PATH . '/public/assets/upload/';
            $upload_path = $upload_dir . $unique_name;
            if (!move_uploaded_file($file_tmp, $upload_path)) {
                echo "Error uploading image.";
                exit;
            }
        }

        // Save the image path
        $profile_image_path = '/assets/upload/' . $unique_name;
    }

    // Begin transaction
    oci_execute(oci_parse($conn, "BEGIN NULL; END;"), OCI_NO_AUTO_COMMIT);

    // Insert user data into 'users' table
    $sql_user = "INSERT INTO users (name, email, password, phone_number, address, profile_image, role) 
                 VALUES (:name, :email, :password, :phone_number, :address, :profile_image, 'buyer') 
                 RETURNING user_id INTO :user_id";
    $stmt_user = oci_parse($conn, $sql_user);
    oci_bind_by_name($stmt_user, ':name', $name);
    oci_bind_by_name($stmt_user, ':email', $email);
    oci_bind_by_name($stmt_user, ':password', $password_hashed);
    oci_bind_by_name($stmt_user, ':phone_number', $phone_number);
    oci_bind_by_name($stmt_user, ':address', $address);
    oci_bind_by_name($stmt_user, ':profile_image', $profile_image_path);
    oci_bind_by_name($stmt_user, ':user_id', $user_id, -1, SQLT_INT);

    if (oci_execute($stmt_user, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = 'buyer';
        header("Location: /buyer/dashboard.php");
        exit;
    } else {
        oci_rollback($conn);
        echo "Error registering user.";
    }
}
?>