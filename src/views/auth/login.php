<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - TiffinCraft</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <?php include BASE_PATH . '/src/includes/header.php'; ?>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login to TiffinCraft</h2>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <?= $_SESSION['login_error'] ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
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

</body>

</html>