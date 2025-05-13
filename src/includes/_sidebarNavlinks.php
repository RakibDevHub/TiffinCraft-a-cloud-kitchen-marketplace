<?php
// src/includes/_sidebarNavlinks.php
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$requestUri = $_SERVER['REQUEST_URI'];
$isDashboardView = (strpos($requestUri, '/admin') !== false) || (strpos($requestUri, '/dashboard') !== false);
$isBusinessView = strpos($requestUri, '/business') !== false;
?>

<!-- Sidebar -->
<div class="md:hidden top-[56px] fixed inset-0 z-40 hidden" id="sidebar-navlinks">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 top-[56px] transition-opacity duration-300 ease-in-out opacity-0"
        id="sidebar-navlinks-overlay"></div>

    <!-- Links Container -->
    <div
        class="relative flex flex-col w-4/5 max-w-sm h-full bg-white shadow-xl transform transition-all duration-300 ease-in-out -translate-x-full">
        <div class="pb-3 space-y-1 overflow-y-auto flex-1">
            <?php if (!$isBusinessView && !$isDashboardView): ?>
                <div class="border-t border-gray-200">
                    <a href="/"
                        class="bg-orange-50 border-orange-500 text-orange-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Home</a>
                    <a href="#dishes"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Delicious
                        Dishes</a>
                    <a href="#kitchens"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Kitchens</a>
                    <a href="#how-it-works"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">How
                        It Works</a>
                </div>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <div class="border-t border-gray-200 pb-3">
                    <div class="mt-3 space-y-1">
                        <a href="<?= $views['dashboard'] ?? '/dashboard' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Dashboard</a>
                        <a href="<?= $views['profile'] ?? '/profile' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Profile</a>
                        <a href="<?= $views['settings'] ?? '/settings' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Settings</a>
                        <form action="/logout" method="POST">
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Logout</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="border-t border-gray-200 pt-4 pb-3">
                    <div class="space-y-1">
                        <a href="/login"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Login</a>
                        <a href="/register"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Register</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>