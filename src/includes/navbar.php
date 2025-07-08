<?php
$requestUri = $_SERVER['REQUEST_URI'];
$isHomeView = ($requestUri === '/' || $requestUri === '/home');
$isDashboardView = (strpos($requestUri, '/admin') !== false) || (strpos($requestUri, '/dashboard') !== false);
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
        'dashboard' => '/business/dashboard',
        'profile' => '/business/dashboard/profile',
        'settings' => '/business/dashboard/settings',
    ];
} elseif ($currentRole === 'admin') {
    $views = [
        'dashboard' => '/admin/dashboard',
        'profile' => '/admin/dashboard/profile',
        'settings' => '/admin/dashboard/settings',
    ];
}
?>

<?php if ($isHomeView && !$isLoggedIn): ?>
    <div id="ctaBar"
        class="h-16 sm:h-12 fixed top-0 left-0 w-full bg-orange-100 text-orange-800 z-50 flex items-center justify-between shadow transition-all duration-300 transform -translate-y-full opacity-0">
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
            <!-- Mobile menu button and logo -->
            <div class="flex gap-2 items-center">
                <div class="flex items-center <?= $isDashboardView ? '' : 'md:hidden' ?>">
                    <button id="sidebar-navlinks-button" type="button"
                        class="<?= $isDashboardView ? '' : 'md:hidden' ?> inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500 transition-colors duration-200"
                        aria-expanded="false" aria-controls="sidebar-navlinks">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg class="h-6 w-6" id="sidebar-navlinks-open-icon" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close icon -->
                        <svg class="hidden h-6 w-6" id="sidebar-navlinks-close-icon" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <a href="<?= $isBusinessView ? '/business' : '/' ?>"
                    class="text-xl font-bold text-gray-800 hover:text-orange-500 transition-colors flex items-centergroup">

                    <!-- Logo Image - Added hover effect and better sizing -->
                    <img class="h-10 w-auto ml-[-5px]" src="/assets/images/main-logo.png"
                        alt="TiffinCraft <?= $isBusinessView ? 'Business' : 'Home' ?>">

                    <!-- Text Logo - Only shown in business view -->
                    <?php if ($isBusinessView): ?>
                        <span class="flex flex-start items-center">
                            <span
                                class="bg-orange-200 text-orange-600 px-2 py-1 rounded-md text-xs sm:text-sm font-semibold">BUSINESS</span>

                        </span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Desktop navigation -->
            <?php if (!$isBusinessView && !$isDashboardView): ?>
                <div class="hidden md:ml-6 md:flex flex-row gap-4">
                    <a href="/#"
                        class="border-orange-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Home</a>
                    <a href="/dishes"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Delicious
                        Dishes</a>
                    <a href="/kitchens"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Browse
                        Kitchens</a>
                    <a href="/contact"
                        class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Contact
                        Us</a>
                </div>
            <?php endif; ?>

            <!-- Right side -->
            <div class="flex justify-end">
                <div class="flex items-center">
                    <?php if ($isLoggedIn): ?>
                        <?php include BASE_PATH . '/src/includes/_dropdownNavlinks.php'; ?>
                    <?php else: ?>
                        <div class="hidden <?= $isBusinessView ? 'min-[450px]:flex' : 'min-[350px]:flex' ?> items-center">
                            <a href="/login"
                                class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Login</a>
                            <a href="/register"
                                class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>
<?php include BASE_PATH . '/src/includes/_sidebarNavlinks.php'; ?>