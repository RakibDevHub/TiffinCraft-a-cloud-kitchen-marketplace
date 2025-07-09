<?php
$requestUri = $_SERVER['REQUEST_URI'];
$isSeller = ($requestUri === '/business/login');
$pageTitle = $isSeller ? "Business Login - TiffinCraft" : "Login - TiffinCraft";

$error = $data['error'] ?? null;

$helper = new App\Utils\Helper();
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}

ob_start();
?>

<section class="min-h-screen flex items-center justify-center p-4">
    <!-- Main Container with zoom-in animation -->
    <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-2xl overflow-hidden" data-aos="zoom-in"
        data-aos-delay="100">

        <!-- Gradient Header -->
        <div class="bg-gradient-to-r from-orange-400 to-orange-600 py-6 px-8 text-center">
            <h1 class="text-2xl font-bold text-white" data-aos="zoom-in" data-aos-delay="200">
                Welcome Back
            </h1>
            <p class="text-orange-100 mt-1" data-aos="zoom-in" data-aos-delay="300">
                Login to your TiffinCraft<?= $isSeller ? " Business" : ""; ?> account
            </p>
        </div>

        <!-- Form Container -->
        <div class="p-8" data-aos="zoom-in" data-aos-delay="400">
            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded mb-6" data-aos="zoom-in"
                    data-aos-delay="100">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-transition
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="your@email.com">
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-transition
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="••••••••">
                    <div class="text-right mt-1">
                        <a href="/forgot-password" class="text-sm text-orange-600 hover:underline">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold 
                                   py-3 px-4 rounded-lg shadow-md transition-all duration-300 transform
                                   hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-orange-500
                                   focus:ring-offset-2">
                    LOGIN
                </button>
            </form>

            <!-- Registration Link -->
            <div class="text-center mt-6 text-sm text-gray-600">
                Don't have an account?
                <a href="/register" class="font-medium text-orange-600 hover:underline ml-1">
                    Create one
                </a>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>