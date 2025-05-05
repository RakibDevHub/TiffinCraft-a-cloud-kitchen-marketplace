<?php
$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/business') !== false;
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$currentRole = $isLoggedIn ? $_SESSION['role'] : null;
?>

<?php if (!$isBusinessView && !$isLoggedIn): ?>
    <!-- CTA Bar -->
    <div id="ctaBar"
        class="h-[50px] fixed top-0 left-0 w-full bg-orange-100 text-orange-800 z-50 flex items-center justify-between px-4 py-2 shadow transition-all duration-300 transform -translate-y-full opacity-0">
        <div>
            <span class="font-semibold">Own a Tiffin Business?</span>
            <a href="/business" class="ml-2 text-orange-700 underline hover:text-orange-900">Join TiffinCraft Business</a>
        </div>
        <button id="closeCta" class="ml-4 text-orange-800 hover:text-orange-900 text-xl font-bold">&times;</button>
    </div>
<?php endif; ?>

<!-- Main Navbar -->
<nav id="mainNav" class="fixed top-0 left-0 w-full bg-white shadow z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 py-2 flex justify-between items-center h-[60px]"> <!-- Fixed height -->
        <!-- Logo/Brand -->
        <a href="<?= $isBusinessView ? '/business' : '/' ?>" class="text-xl font-bold text-gray-800">
            <?= $isBusinessView ? 'TiffinCraft Business' : 'TiffinCraft' ?>
        </a>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6 relative"> <!-- Added relative positioning -->
            <?php if ($isLoggedIn): ?>
                <!-- User Dropdown -->
                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button" class="flex text-sm bg-gray-200 rounded-full focus:ring-4 focus:ring-gray-300"
                        id="user-menu-button" aria-expanded="false">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full"
                            src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
                            alt="User profile">
                    </button>

                    <!-- Dropdown menu - now positioned absolutely -->
                    <div class="absolute right-0 top-full mt-2 z-50 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-md w-48"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span
                                class="block text-sm text-gray-900"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></span>
                            <span class="block text-sm text-gray-500 truncate">
                                <?= ucfirst($currentRole) ?> Account
                            </span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <?php if ($currentRole === 'admin'): ?>
                                <li>
                                    <a href="/admin/dashboard"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                </li>
                                <li>
                                    <a href="/admin/users"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Manage Users</a>
                                </li>
                            <?php elseif ($currentRole === 'seller'): ?>
                                <li>
                                    <a href="/seller/dashboard"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kitchen Dashboard</a>
                                </li>
                                <li>
                                    <a href="/seller/orders"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Orders</a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="/buyer/home" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My
                                        Account</a>
                                </li>
                                <li>
                                    <a href="/buyer/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My
                                        Orders</a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="/settings"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            </li>
                            <li>
                                <form action="/logout" method="post">
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                                        out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Guest Menu -->
            <ul class="flex space-x-6 text-gray-600 font-medium">
                <li><a href="<?= $isBusinessView ? '/business/login' : '/login' ?>" class="hover:underline">Login</a></li>
                <li>
                    <a href="<?= $isBusinessView ? '/business/register' : '/register' ?>"
                        class="rounded-md bg-orange-500 text-white px-4 py-2 hover:bg-orange-600 transition-colors">
                        Register
                    </a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
    </div>
</nav>

<script>
    <?php if ($isLoggedIn): ?>
        // Dropdown toggle functionality
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');

        userMenuButton.addEventListener('click', function () {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!userMenuButton.contains(event.target)) {
                userMenuButton.setAttribute('aria-expanded', 'false');
                userDropdown.classList.add('hidden');
            }
        });
    <?php endif; ?>

    <?php if (!$isBusinessView && !$isLoggedIn): ?>
        // CTA Bar animation script (same as before)
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