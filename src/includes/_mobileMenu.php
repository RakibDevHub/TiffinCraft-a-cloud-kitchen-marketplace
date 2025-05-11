<?php
$isDashboardView = strpos($requestUri, '/dashboard' || '/admin') !== false;
?>

<div class="md:hidden hidden" id="mobile-menu">
    <div class="pt-2 pb-3 space-y-1">
        <a href="/"
            class="bg-orange-50 border-orange-500 text-orange-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</a>
        <a href="#dishes"
            class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Delicious
            Dishes</a>
        <a href="#kitchens"
            class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Kitchens</a>
        <a href="#how-it-works"
            class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">How
            It Works</a>

        <?php if ($isLoggedIn && !$isDashboardView): ?>
            <div class="border-t border-gray-200 pb-3">
                <div class="mt-3 space-y-1">
                    <a href="<?= $views['dashboard'] ?>"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard</a>
                    <a href="<?= $views['profile'] ?>"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Profile</a>
                    <a href="<?= $views['settings'] ?>"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Settings</a>
                    <form action="/logout" method="POST">
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="border-t border-gray-200 pt-4 pb-3">
                <div class="space-y-1">
                    <a href="/login"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Login</a>
                    <a href="/register"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Register</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>