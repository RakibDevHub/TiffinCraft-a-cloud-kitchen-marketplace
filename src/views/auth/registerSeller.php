<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register Seller</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Preview image before uploading
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            const closeButton = document.getElementById('closeButton');
            const reader = new FileReader();

            if (file) {
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the preview image
                    closeButton.style.display = 'block'; // Show the close button
                }
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        }

        // Remove preview image
        function removeImage() {
            const preview = document.getElementById('imagePreview');
            const closeButton = document.getElementById('closeButton');
            preview.src = "";
            preview.style.display = 'none';
            closeButton.style.display = 'none'; // Hide the close button
            document.getElementById('profile_image').value = ""; // Clear the file input
        }
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <?php include BASE_PATH . '/src/includes/header.php'; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Seller Registration</h2>

        <?php if (!empty($_SESSION['register_error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $_SESSION['register_error'];
                unset($_SESSION['register_error']); ?>
            </div>
        <?php endif; ?>

        <form action="/business/register" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Image preview -->
            <div class="mb-4 flex justify-center relative">
                <img id="imagePreview" src="" alt="Image Preview" class="hidden w-32 h-32 object-cover rounded-lg" />
                <button type="button" id="closeButton" onclick="removeImage()"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden">
                    &times;
                </button>
            </div>

            <div class="mb-4">
                <label for="profile_image" class="block font-semibold mb-1">Profile Image (optional)</label>
                <input type="file" name="profile_image" id="profile_image" accept="image/*"
                    class="w-full border p-2 rounded" onchange="previewImage(event)" />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Owner Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Phone Number</label>
                <input type="text" name="phone_number" required placeholder="e.g., 01XXXXXXXXX"
                    class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Address</label>
                <textarea name="address" required class="w-full border border-gray-300 p-2 rounded"></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Kitchen Name</label>
                <input type="text" name="kitchen_name" required class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Kitchen Address</label>
                <textarea name="kitchen_address" required class="w-full border border-gray-300 p-2 rounded"></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Register as Seller
                </button>
            </div>
        </form>
    </div>
</body>

</html>