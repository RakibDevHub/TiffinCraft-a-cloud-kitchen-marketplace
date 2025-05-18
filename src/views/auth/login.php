<?php
$pageTitle = "TiffinCraft Login";
ob_start();

$helper = new App\Utils\Helper();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}
?>

<section class="relative h-max py-10">
    <div class="w-full max-w-md mx-auto bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login to TiffinCraft</h2>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?= $_SESSION['login_error'] ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white rounded py-2 hover:bg-blue-700 transition">Login</button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Don’t have an account?
            <a href="/register" class="text-blue-600 hover:underline">Register here</a>
        </p>
    </div>
</section>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>