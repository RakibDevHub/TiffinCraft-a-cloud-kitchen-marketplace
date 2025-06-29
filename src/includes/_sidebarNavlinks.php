<?php
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$currentRole = $isLoggedIn ? $_SESSION['role'] : null;
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
        class="relative flex flex-col w-2/5 max-w-xs h-full bg-white shadow-xl transform transition-all duration-300 ease-in-out -translate-x-full">
        <div class="pb-3 space-y-1 overflow-y-auto flex-1">
            <?php if (!$isBusinessView && !$isDashboardView): ?>
                <!-- Public Navigation -->
                <div class="border-t border-gray-200">
                    <a href="/"
                        class="bg-orange-50 border-orange-500 text-orange-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Home</a>
                    <a href="/dishes"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Delicious
                        Dishes</a>
                    <a href="/kitchens"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Browse
                        Kitchens</a>
                    <a href="/contact"
                        class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-200 ease-in-out">Contact
                        Us</a>
                </div>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <!-- User-Specific Navigation -->
                <div class="border-t border-gray-200 pb-3">
                    <div class="mt-3 space-y-1">
                        <!-- Dashboard Link -->
                        <a href="<?= $views['dashboard'] ?? '/dashboard' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Dashboard</a>

                        <div class="border-t border-gray-200"></div>

                        <!-- Role-Specific Navigation -->
                        <?php if ($currentRole === 'buyer'): ?>
                            <!-- Buyer Navigation -->
                            <a href="/dishes"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Browse
                                Dishes</a>
                            <a href="/kitchens"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Browse
                                Kitchens</a>
                            <a href="/orders"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">My
                                Orders</a>
                            <a href="/subscriptions"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">My
                                Subscriptions</a>
                            <a href="/favorites"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Favorites</a>
                            <a href="/loyalty"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Loyalty
                                Points</a>

                        <?php elseif ($currentRole === 'seller'): ?>
                            <!-- Seller Navigation -->
                            <a href="/business/dashboard/menu"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Manage
                                Menu</a>
                            <a href="/business/dashboard/orders"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">View
                                Orders</a>
                            <a href="/business/dashboard/promotions"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Promotions</a>
                            <a href="/business/dashboard/subscriptions"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Subscription
                                Packages</a>
                            <a href="/business/dashboard/reviews"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Customer
                                Reviews</a>
                            <a href="/business/dashboard/areas"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Service
                                Areas</a>
                            <a href="/business/dashboard/earnings"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Earnings
                                Report</a>

                        <?php elseif ($currentRole === 'admin'): ?>
                            <!-- Admin Navigation -->
                            <a href="/admin/dashboard/users"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Manage
                                Users</a>
                            <a href="/admin/dashboard/kitchens"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Manage
                                Kitchens</a>
                            <a href="/admin/dashboard/categories"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Manage
                                Categories</a>
                            <a href="/admin/dashboard/reviews"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Manage
                                Reviews</a>
                            <a href="/admin/dashboard/orders"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">View
                                All Orders</a>
                            <a href="/admin/dashboard/reports"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Reports</a>
                            <a href="/admin/dashboard/promotions"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Platform
                                Promotions</a>
                            <a href="/admin/dashboard/content"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Content
                                Management</a>
                            <a href="/admin/dashboard/settings"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Site
                                Settings</a>
                        <?php endif; ?>

                        <div class="border-t border-gray-200"></div>

                        <!-- Common Navigation -->
                        <a href="/"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">TiffinCraft
                            Home</a>
                        <?php if ($currentRole === 'buyer'): ?>
                            <a href="/business"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">TiffinCraft
                                Business</a>
                        <?php endif; ?>

                        <div class="border-t border-gray-200"></div>

                        <!-- Account Navigation -->
                        <a href="<?= $views['profile'] ?? '/profile' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Profile</a>
                        <a href="<?= $views['settings'] ?? '/settings' ?>"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Settings</a>
                        <a href="/support"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Support</a>
                        <form action="/logout" method="POST">
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-base font-medium text-red-500 hover:text-red-800 hover:bg-orange-50 border-l-4 hover:border-orange-500 transition-colors duration-200 ease-in-out">Logout</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <!-- Guest Navigation -->
                <div class="border-t border-gray-200 pt-4 pb-3">
                    <div class="space-y-1">
                        <a href="/login"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Login</a>
                        <a href="/register"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors duration-200 ease-in-out">Register</a>
                        <div class="border-t border-gray-200 pt-2">
                            <a href="/business"
                                class="block px-4 py-2 text-base font-medium text-orange-600 hover:text-orange-800 hover:bg-orange-50 transition-colors duration-200 ease-in-out">For
                                Business Owners</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>