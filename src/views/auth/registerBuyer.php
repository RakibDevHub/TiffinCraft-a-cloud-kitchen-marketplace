<?php
$pageTitle = "TiffinCraft Register";
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;
ob_start();

$helper = new App\Utils\Helper();

if (empty($_SESSION['csrf_token'])) {
    $helper->generateCsrfToken();
}
$csrfToken = $_SESSION['csrf_token'];
?>

<section class="min-h-screen bg-orange-50 flex items-center justify-center py-10">
    <div class="w-full max-w-xl bg-white shadow-lg rounded-xl p-8">
        <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Buyer Registration</h2>

        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?= $_SESSION['register_error'] ?>
                <?php unset($_SESSION['register_error']); ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

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

            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" id="name" required class="w-full border p-2 rounded"
                    placeholder="Enter your full name" />
            </div>

            <div class="mb-4">
                <label for="email" class="block font-semibold mb-1">Email </label>
                <input type="email" name="email" id="email" required class="w-full border p-2 rounded"
                    placeholder="Enter your email address" />
            </div>

            <div class="mb-4">
                <label for="phone_number" class="block font-semibold mb-1">Number</label>
                <input type="text" name="phone_number" id="phone_number" required class="w-full border p-2 rounded"
                    placeholder="Enter your phone number" />
            </div>

            <div class="mb-4">
                <label for="address" class="block font-semibold mb-1">Address</label>
                <input type="text" name="address" id="address" required class="w-full border p-2 rounded"
                    placeholder="e.g., Bashundhara, Block B, Vatara, Dhaka 1212" />
            </div>

            <div class="mb-4">
                <label for="password" class="block font-semibold mb-1">Password</label>
                <input type="password" name="password" id="password" required class="w-full border p-2 rounded"
                    placeholder="Enter your password" />
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block font-semibold mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                    class="w-full border p-2 rounded" placeholder="Confirm your password" />
            </div>

            <div class="mb-4">
                <label for="terms" class="inline-flex items-center">
                    <input type="checkbox" name="terms" id="terms" required class="mr-2">
                    <span>I accept the <a href="#" class="text-blue-600">terms & conditions</a></span>
                </label>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Register</button>
        </form>

    </div>
</section>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const closeButton = document.getElementById('closeButton');
        const reader = new FileReader();

        if (file) {
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                closeButton.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const preview = document.getElementById('imagePreview');
        const closeButton = document.getElementById('closeButton');
        preview.src = "";
        preview.style.display = 'none';
        closeButton.style.display = 'none';
        document.getElementById('profile_image').value = "";
    }

</script>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>