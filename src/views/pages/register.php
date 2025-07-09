<?php
$requestUri = $_SERVER['REQUEST_URI'];
$isSeller = ($requestUri === '/business/register');
$pageTitle = $isSeller ? "Business Register - TiffinCraft" : "Register - TiffinCraft";

$error = $data['error'] ?? null;
$success = $data['success'] ?? null;

$helper = new App\Utils\Helper();
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}

ob_start();
?>

<?php if (!$isSeller): ?>
    <!-- BUYER REGISTRATION FORM -->
    <section class="min-h-screen flex items-center justify-center p-4 sm:p-10">
        <div class="w-full max-w-md sm:max-w-xl bg-white shadow-xl rounded-xl overflow-hidden" data-aos="zoom-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-400 to-orange-600 p-4 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white" data-aos="fade-down" data-aos-delay="100">
                    Create Your Account
                </h2>
                <p class="text-orange-100 mt-1" data-aos="fade-down" data-aos-delay="200">
                    Join TiffinCraft as a Buyer
                </p>
            </div>

            <!-- Form Container -->
            <div class="p-6 sm:p-8" data-aos="zoom-in">
                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-6">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <!-- Image Upload -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative flex flex-col items-center group gap-4">
                            <img id="buyerImagePreview" src="" alt="Preview"
                                class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-full border-4 border-white shadow-md hidden">
                            <button type="button"
                                onclick="removeImage('buyerImagePreview', 'buyerCloseButton', 'buyerProfileImage')"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden hover:bg-red-600 btn-transition">
                                &times;
                            </button>
                            <label class="block mt-4 cursor-pointer text-center">
                                <span
                                    class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-medium hover:bg-orange-200 btn-transition">
                                    Upload Photo
                                </span>
                                <input type="file" name="profile_image" id="buyerProfileImage" accept="image/*"
                                    class="hidden"
                                    onchange="previewImage(event, 'buyerImagePreview', 'buyerCloseButton', 'buyerProfileImage')">
                            </label>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Name -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="John Doe">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="john@example.com">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="+8801XXXXXXXXX">
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" id="address" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="Bashundhara, Block B, Vatara, Dhaka 1212">
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="••••••••">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                                Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Terms & Submit -->
                    <div class="flex items-center mt-4">
                        <input type="checkbox" name="terms" id="terms" required
                            class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-orange-600 hover:underline">Terms & Conditions</a>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg shadow-md hover:shadow-lg btn-transition mt-6">
                        Register Now
                    </button>
                </form>

                <div class="text-center mt-6 text-sm text-gray-600">
                    Already have an account? <a href="/login" class="text-orange-600 font-medium hover:underline">Sign
                        In</a>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>
    <!-- SELLER REGISTRATION FORM -->
    <section class="min-h-screen flex items-center justify-center p-4 sm:p-10">
        <div class="w-full max-w-2xl bg-white shadow-xl rounded-xl overflow-hidden" data-aos="zoom-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-400 to-orange-600 py-6 px-8 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white" data-aos="fade-down" data-aos-delay="100">
                    Register Your Business
                </h2>
                <p class="text-orange-100 mt-1" data-aos="fade-down" data-aos-delay="200">
                    Join TiffinCraft as a Seller
                </p>
            </div>

            <!-- Form Container -->
            <div class="p-6 sm:p-8" data-aos="fade-up">
                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-6">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="/business/register" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                    <!-- Profile Image -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative flex flex-col items-center group gap-4">
                            <img id="sellerImagePreview" src="" alt="Preview"
                                class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-full border-4 border-white shadow-md hidden">
                            <button type="button"
                                onclick="removeImage('sellerImagePreview', 'sellerCloseButton', 'sellerProfileImage')"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden hover:bg-red-600 btn-transition">
                                &times;
                            </button>
                            <label class="block mt-4 cursor-pointer text-center">
                                <span
                                    class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-medium hover:bg-orange-200 btn-transition">
                                    Upload Profile Photo
                                </span>
                                <input type="file" name="profile_image" id="sellerProfileImage" accept="image/*"
                                    class="hidden"
                                    onchange="previewImage(event, 'sellerImagePreview', 'sellerCloseButton', 'sellerProfileImage')">
                            </label>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="grid sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner Name</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="your@email.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="01XXXXXXXXX">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="border-t border-gray-200 pt-6 mt-6 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Business Information</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kitchen Name</label>
                            <input type="text" name="kitchen_name" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kitchen Address</label>
                            <input type="text" name="kitchen_address" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Service Areas
                                (comma-separated)</label>
                            <input type="text" name="service_areas" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="Badda, Gulshan, Banani">
                            <p class="text-xs text-gray-500 mt-1">Enter areas where you can deliver food</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kitchen Description</label>
                            <textarea name="kitchen_description" required rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="Describe your kitchen and cuisine"></textarea>
                        </div>

                        <!-- Kitchen Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kitchen Photo</label>
                            <div class="relative group">
                                <img id="kitchenImagePreview" src="" alt="Kitchen Preview"
                                    class="w-full h-48 object-cover rounded-lg border-2 border-dashed border-gray-300 hidden">
                                <button type="button"
                                    onclick="removeImage('kitchenImagePreview', 'kitchenCloseButton', 'kitchenImage')"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden hover:bg-red-600 btn-transition">
                                    &times;
                                </button>
                                <label class="block cursor-pointer text-center py-4">
                                    <span
                                        class="bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-medium hover:bg-orange-200 btn-transition">
                                        Upload Kitchen Photo
                                    </span>
                                    <input type="file" name="kitchen_image" id="kitchenImage" accept="image/*"
                                        class="hidden"
                                        onchange="previewImage(event, 'kitchenImagePreview', 'kitchenCloseButton', 'kitchenImage')">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="grid sm:grid-cols-2 gap-4 sm:gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="••••••••">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="confirm_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg input-focus"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Terms & Submit -->
                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="terms" id="sellerTerms" required
                            class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <label for="sellerTerms" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-orange-600 hover:underline">Terms & Conditions</a>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg shadow-md hover:shadow-lg btn-transition mt-6">
                        Register Business
                    </button>
                </form>

                <div class="text-center mt-6 text-sm text-gray-600">
                    Already have an account? <a href="/login" class="text-orange-600 font-medium hover:underline">Sign
                        In</a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<script>
    // Image handling functions
    function previewImage(event, previewId, closeId, inputId) {
        const file = event.target.files[0];
        const preview = document.getElementById(previewId);
        const closeButton = document.getElementById(closeId);
        const reader = new FileReader();

        if (file) {
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                closeButton.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage(previewId, closeId, inputId) {
        const preview = document.getElementById(previewId);
        const closeButton = document.getElementById(closeId);
        preview.src = "";
        preview.classList.add('hidden');
        closeButton.classList.add('hidden');
        document.getElementById(inputId).value = "";
    }
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>