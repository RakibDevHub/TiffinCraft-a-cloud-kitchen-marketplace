<?php
$fullName = htmlspecialchars($_SESSION['name'] ?? 'User');
$token = strtok($fullName, ' ');
$parts = [];
$count = 0;
while ($token !== false && $count < 2) {
    $parts[] = $token;
    $token = strtok(' ');
    $count++;
}
$user_name = implode(' ', $parts);
$user_type = htmlspecialchars($_SESSION['role'] ?? 'Guest');

$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/business') !== false;
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);

if ($user_type === 'seller') {
    $profileView = '/business/profile';
    $settingsView = '/business/settings';
} elseif ($user_type === 'admin') {
    $profileView = '/admin/profile';
    $settingsView = '/admin/settings';
} else {
    $profileView = '/profile';
    $settingsView = '/settings';
}

?>

<div class="!ml-2 relative">
    <button type="button"
        class="bg-white rounded-full flex justify-center items-center gap-2 text-sm text-left leading-[1.5]"
        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
        <span class="sr-only">Open user menu</span>
        <img class="h-8 w-8 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
            alt="User profile">
        <div class="flex flex-col">
            <span class="text-[14px] font-medium"><?= $user_name ?></span>
            <span class="text-[12px] font-normal capitalize"><?= $user_type ?></span>
        </div>
    </button>
    <!-- Dropdown menu -->
    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" id="user-dropdown">

        <?php if ($user_type === 'buyer'): ?>
            <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Dashboard</a>
            <a href="/marketplace/browse" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Browse
                Tiffins</a>
            <a href="/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My Orders</a>
            <a href="/subscriptions" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My
                Subscriptions</a>
            <a href="/reviews" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My
                Reviews</a>
        <?php elseif ($user_type === 'seller'): ?>
            <a href="/business/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Dashboard</a>
            <a href="/business/menu" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Manage
                Menu</a>
            <a href="/business/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">View
                Orders</a>
            <a href="/business/subscribers" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">My
                Subscribers</a>
            <a href="/business/reviews" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Customer Reviews</a>
            <a href="/business/kitchen" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">My
                Kitchen</a>
        <?php elseif ($user_type === 'admin'): ?>
            <!-- <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Admin
                Dashboard</a>
            <a href="/admin/users" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Manage
                Users</a>
            <a href="/admin/kitchens" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Manage
                Kitchens</a>
            <a href="/admin/categories" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Food
                Categories</a>
            <a href="/admin/reports" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Reports
                & Metrics</a>
            <a href="/admin/flashdeals" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Flash Deals</a> -->

        <?php endif; ?>
        <?php if ($isLoggedIn): ?>
            <a href="<?= $profileView ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Profile</a>
            <a href="<?= $settingsView ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem">Settings</a>
            <form action="/logout" method="POST" role="none">
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem">Log Out</button>
            </form>
        <?php endif; ?>
        <!-- <div class="border-t border-gray-100"></div> -->
    </div>
</div>

<script>
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
        // if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
        //     mobileMenuButton.setAttribute('aria-expanded', 'false');
        //     mobileMenu.classList.add('hidden');
        //     hamburgerIcon.classList.remove('hidden');
        //     closeIcon.classList.add('hidden');
        // }
    });
</script>