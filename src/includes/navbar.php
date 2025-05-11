<?php
// src/includes/navbar.php

$requestUri = $_SERVER['REQUEST_URI'];
$isHomeView = ($requestUri === '/' || $requestUri === '/home');
$isBusinessView = strpos($requestUri, '/business') !== false;
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$currentRole = $isLoggedIn ? $_SESSION['role'] : null;

// Prepare user data if logged in
if ($isLoggedIn) {
    $fullName = htmlspecialchars($_SESSION['name']);
    $token = strtok($fullName, ' ');
    $parts = [];
    $count = 0;
    while ($token !== false && $count < 2) {
        $parts[] = $token;
        $token = strtok(' ');
        $count++;
    }
    $user_name = implode(' ', $parts);
    $user_type = htmlspecialchars($_SESSION['role']);
}

// Prepare view paths
$views = [
    'profile' => '/profile',
    'settings' => '/settings',
    'dashboard' => '/dashboard'
];

if ($currentRole === 'seller') {
    $views = [
        'profile' => '/business/profile',
        'settings' => '/business/settings',
        'dashboard' => '/business/dashboard'
    ];
} elseif ($currentRole === 'admin') {
    $views = [
        'profile' => '/admin/profile',
        'settings' => '/admin/settings',
        'dashboard' => '/admin/dashboard'
    ];
}
?>

<?php if ($isHomeView && !$isLoggedIn): ?>
    <div id="ctaBar"
        class="h-12 fixed top-0 left-0 w-full bg-orange-100 text-orange-800 z-50 flex items-center justify-between shadow transition-all duration-300 transform -translate-y-full opacity-0">
        <div class="flex items-center justify-between w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div>
                <span class="font-semibold">Own a Tiffin Business?</span>
                <a href="/business" class="ml-2 text-orange-700 underline hover:text-orange-900">Join TiffinCraft
                    Business</a>
            </div>
            <button id="closeCta" class="ml-4 text-orange-800 hover:text-orange-900 text-xl font-bold">&times;</button>
        </div>
    </div>
<?php endif; ?>

<!-- Main Navbar -->
<nav id="mainNav" class="fixed top-0 left-0 w-full bg-white shadow z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">
            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button id="mobile-menu-button" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Logo and main links -->
            <a href="<?= $isBusinessView ? '/business' : '/' ?>" class="text-xl font-bold text-gray-800">
                <?= $isBusinessView ? 'TiffinCraft Business' : 'TiffinCraft' ?>
            </a>

            <!-- Desktop navigation -->
            <div class="hidden md:ml-6 md:flex md:space-x-8">
                <?php if (!$isBusinessView): ?>
                    <a href="/"
                        class="border-orange-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</a>
                    <a href="#dishes"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Delicious
                        Dishes</a>
                    <a href="#kitchens"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Kitchens</a>
                    <a href="#how-it-works"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">How
                        It Works</a>
                <?php endif; ?>
            </div>

            <!-- Right side (auth) -->
            <div class="flex items-center">
                <?php if ($isLoggedIn): ?>
                    <?php include BASE_PATH . '/src/includes/_profileDropdown.php'; ?>
                <?php else: ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="/register"
                            class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include BASE_PATH . '/src/includes/_mobileMenu.php'; ?>
</nav>

<script src="/assets/js/navbar.js"></script>
<script src="/assets/js/dropdown.js"></script>