<?php
$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/business') !== false;
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$currentRole = $isLoggedIn ? $_SESSION['role'] : null;
?>

<?php if (!$isBusinessView && !$isLoggedIn): ?>
    <!-- CTA Bar -->
    <div id="ctaBar"
        class="h-12 fixed top-0 left-0 w-full bg-orange-100 text-orange-800 z-50 flex items-center justify-between px-4 py-2 shadow transition-all duration-300 transform -translate-y-full opacity-0">
        <div>
            <span class="font-semibold">Own a Tiffin Business?</span>
            <a href="/business" class="ml-2 text-orange-700 underline hover:text-orange-900">Join TiffinCraft Business</a>
        </div>
        <button id="closeCta" class="ml-4 text-orange-800 hover:text-orange-900 text-xl font-bold">&times;</button>
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
                    <!-- Hamburger icon -->
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
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

            <!-- Desktop navigation (visible to all) -->
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
                    <!-- User dropdown -->
                    <div class="ml-3 relative">
                        <button type="button"
                            class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full"
                                src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
                                alt="User profile">
                        </button>

                        <!-- Dropdown menu -->
                        <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" id="user-dropdown">
                            <div class="flex items-center px-4 py-3">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full"
                                        src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
                                        alt="User profile">
                                </div>
                                <div class="ml-3">
                                    <div class="text-base font-medium text-gray-800">
                                        <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>
                                    </div>
                                    <div class="text-sm font-medium text-gray-500"><?= ucfirst($currentRole) ?> Account
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <?php if ($currentRole === 'buyer'): ?>
                                <a href="/buyer/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Dashboard</a>
                                <a href="/marketplace/browse" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Browse
                                    Tiffins</a>
                                <a href="/buyer/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">My Orders</a>
                                <a href="/buyer/subscriptions" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">My
                                    Subscriptions</a>
                                <a href="/buyer/reviews" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">My Reviews</a>
                            <?php elseif ($currentRole === 'seller'): ?>
                                <a href="/seller/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Dashboard</a>
                                <a href="/seller/menu" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Manage Menu</a>
                                <a href="/seller/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">View Orders</a>
                                <a href="/seller/subscribers" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">My
                                    Subscribers</a>
                                <a href="/seller/reviews" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Customer Reviews</a>
                                <a href="/seller/kitchen" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">My Kitchen</a>
                            <?php elseif ($currentRole === 'admin'): ?>
                                <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Admin Dashboard</a>
                                <a href="/admin/users" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Manage Users</a>
                                <a href="/admin/kitchens" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Manage Kitchens</a>
                                <a href="/admin/categories" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Food Categories</a>
                                <a href="/admin/reports" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Reports & Metrics</a>
                                <a href="/admin/flashdeals" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Flash Deals</a>
                            <?php endif; ?>
                            <div class="border-t border-gray-100"></div>
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem">Profile</a>
                            <form action="/logout" method="POST" role="none">
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Logout</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Guest buttons -->
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="/register"
                            class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
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
            <?php if ($isLoggedIn): ?>
                <div class="border-t border-gray-200 pt-4 pb-3">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full"
                                src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
                                alt="User profile">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">
                                <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>
                            </div>
                            <div class="text-sm font-medium text-gray-500"><?= ucfirst($currentRole) ?> Account</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <?php if ($currentRole === 'buyer'): ?>
                            <a href="/buyer/dashboard"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard</a>
                            <a href="/marketplace/browse"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Browse
                                Tiffins</a>
                            <a href="/buyer/orders"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My
                                Orders</a>
                            <a href="/buyer/subscriptions"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My
                                Subscriptions</a>
                            <a href="/buyer/reviews"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My
                                Reviews</a>
                        <?php elseif ($currentRole === 'seller'): ?>
                            <a href="/seller/dashboard"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Dashboard</a>
                            <a href="/seller/menu"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Manage
                                Menu</a>
                            <a href="/seller/orders"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">View
                                Orders</a>
                            <a href="/seller/subscribers"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My
                                Subscribers</a>
                            <a href="/seller/reviews"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Customer
                                Reviews</a>
                            <a href="/seller/kitchen"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">My
                                Kitchen</a>
                        <?php elseif ($currentRole === 'admin'): ?>
                            <a href="/admin/dashboard"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Admin
                                Dashboard</a>
                            <a href="/admin/users"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Manage
                                Users</a>
                            <a href="/admin/kitchens"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Manage
                                Kitchens</a>
                            <a href="/admin/categories"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Food
                                Categories</a>
                            <a href="/admin/reports"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Reports
                                & Metrics</a>
                            <a href="/admin/flashdeals"
                                class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Flash
                                Deals</a>
                        <?php endif; ?>
                        <a href="/profile"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Profile</a>
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
</nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = mobileMenuButton.querySelector('svg:first-child');
    const closeIcon = mobileMenuButton.querySelector('svg:last-child');

    mobileMenuButton.addEventListener('click', function () {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.classList.toggle('hidden');
        hamburgerIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });

    <?php if ($isLoggedIn): ?>
        // User dropdown toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuButton.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (event) {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userMenuButton.setAttribute('aria-expanded', 'false');
                userDropdown.classList.add('hidden');
            }
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
    <?php endif; ?>

    <?php if (!$isBusinessView && !$isLoggedIn): ?>
        // CTA Bar functionality
        localStorage.removeItem('ctaClosed');

        const ctaBar = document.getElementById('ctaBar');
        const closeCta = document.getElementById('closeCta');
        const mainNav = document.getElementById('mainNav');

        let ctaClosed = localStorage.getItem('ctaClosed') === 'true';
        let ctaVisible = false;

        const showCTA = () => {
            if (ctaClosed) return;
            ctaBar.classList.remove('-translate-y-full', 'opacity-0');
            ctaBar.classList.add('translate-y-0', 'opacity-100');
            mainNav.style.top = `${ctaBar.offsetHeight}px`;
            ctaVisible = true;
        };

        const hideCTA = () => {
            ctaBar.classList.add('-translate-y-full', 'opacity-0');
            ctaBar.classList.remove('translate-y-0', 'opacity-100');
            mainNav.style.top = '0';
            ctaVisible = false;
        };

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50 && !ctaClosed && !ctaVisible) {
                showCTA();
            } else if (window.scrollY <= 50 && !ctaClosed && ctaVisible) {
                hideCTA();
            }
        });

        closeCta.addEventListener('click', () => {
            ctaClosed = true;
            localStorage.setItem('ctaClosed', 'true');
            hideCTA();
        });

        if (!ctaClosed && window.scrollY > 50) {
            showCTA();
        }
    <?php endif; ?>
</script>