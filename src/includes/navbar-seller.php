<!-- Fixed Navbar -->
<nav id="mainNav" class="fixed top-0 left-0 w-full bg-white shadow z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/index.php" class="text-xl font-bold text-gray-800">TiffinCraft Business</a>
        <ul class="flex space-x-6 text-gray-600 font-medium">
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>
                <img class="h-8 w-8" src="<?= $_SESSION['profile_image'] ?>" alt="Profile">
                <li><a href="/dashboard" class="hover:underline">Dashboard</a></li>
                <li><a href="/logout" class="hover:underline">logout</a></li>
            <?php else: ?>
                <li><a href="/login" class="hover:underline">Login</a></li>
                <li><a href="/register" class="rounded  hover:bg-orange-400 hover:text-white">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>