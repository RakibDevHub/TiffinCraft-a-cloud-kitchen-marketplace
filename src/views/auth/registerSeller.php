<?php
$pageTitle = "TiffinCraft Business Register";
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
        <h2 class="text-2xl font-bold mb-6 text-center">Seller Registration</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <div>
                        <p class="font-semibold">Error</p>
                        <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="/business/register" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                <input type="text" name="address" required class="w-full border border-gray-300 p-2 rounded"></input>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Kitchen Name</label>
                <input type="text" name="kitchen_name" required class="w-full border border-gray-300 p-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Kitchen Address</label>
                <input type="text" name="kitchen_address" required
                    class="w-full border border-gray-300 p-2 rounded"></input>
            </div>

            <div>
                <label for="service_areas" class="block font-medium mb-1">Service Areas (comma-separated)</label>
                <input type="text" name="service_areas" id="service_areas" required
                    placeholder="e.g., Badda, Gulshan, Banani" class="w-full border p-2 rounded" />
                <p class="text-sm text-gray-500 mt-1">Enter all service areas your kitchen can deliver to.</p>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Kitchen Description</label>
                <textarea name="kitchen_description" required class="w-full border border-gray-300 p-2 rounded"
                    placeholder="Briefly describe your kitchen, cuisine, specialties, etc."></textarea>
            </div>

            <!-- Kitchen image preview -->
            <div class="mb-4 flex justify-center relative">
                <img id="kitchenImagePreview" src="" alt="Kitchen Image Preview"
                    class="hidden w-full h-48 object-cover rounded-lg" />
                <button type="button" id="kitchenCloseButton" onclick="removeKitchenImage()"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden">
                    &times;
                </button>
            </div>

            <div class="mb-4">
                <label for="kitchen_image" class="block font-semibold mb-1">Kitchen Image (optional)</label>
                <input type="file" name="kitchen_image" id="kitchen_image" accept="image/*"
                    class="w-full border p-2 rounded" onchange="previewKitchenImage(event)" />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 p-2 rounded" placeholder="Enter your password">
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 font-medium">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                    class="w-full border p-2 rounded" placeholder="Confirm your password" />
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Register as Seller
                </button>
            </div>
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

    function displaySelectedAreas() {
        const select = document.getElementById('service_areas');
        const list = document.getElementById('selectedAreasList');
        list.innerHTML = '';

        for (let option of select.selectedOptions) {
            const li = document.createElement('li');
            li.textContent = option.text;
            list.appendChild(li);
        }
    }

    function previewKitchenImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('kitchenImagePreview');
        const closeButton = document.getElementById('kitchenCloseButton');
        const reader = new FileReader();

        if (file) {
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                closeButton.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeKitchenImage() {
        const preview = document.getElementById('kitchenImagePreview');
        const closeButton = document.getElementById('kitchenCloseButton');
        preview.src = "";
        preview.style.display = 'none';
        closeButton.style.display = 'none';
        document.getElementById('kitchen_image').value = "";
    }
</script>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>