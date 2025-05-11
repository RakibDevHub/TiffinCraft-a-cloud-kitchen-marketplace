<?php

// Default values if not provided
$user_data = [
    'name' => $_SESSION['name'] ?? 'User',
    'role' => $_SESSION['role'] ?? 'Guest',
    'profile_image' => $_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg'
];

// Extract first and last name
$fullName = htmlspecialchars($user_data['name']);
$token = strtok($fullName, ' ');
$parts = [];
$count = 0;
while ($token !== false && $count < 2) {
    $parts[] = $token;
    $token = strtok(' ');
    $count++;
}
$user_name = implode(' ', $parts);
$user_type = htmlspecialchars($user_data['role']);
?>

<div class="!ml-2 relative" id="user-dropdown-container">
    <!-- Dropdown Toggle Button -->
    <button type="button" id="user-menu-button"
        class="bg-white rounded-full flex justify-center items-center gap-2 text-sm text-left leading-[1.5]"
        aria-expanded="false" aria-haspopup="true" aria-controls="user-dropdown">
        <span class="sr-only">User menu</span>
        <img class="h-8 w-8 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            src="<?= htmlspecialchars($user_data['profile_image']) ?>" alt="<?= $user_name ?>'s profile picture">
        <div class="flex flex-col">
            <span class="text-[14px] font-medium"><?= $user_name ?></span>
            <span class="text-[12px] font-normal capitalize"><?= $user_type ?></span>
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div id="user-dropdown"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-50"
        role="menu" aria-labelledby="user-menu-button">
        <a href="<?= $views['dashboard'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Dashboard
        </a>
        <a href="<?= $views['profile'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Profile
        </a>
        <a href="<?= $views['settings'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Settings
        </a>
        <form action="/logout" method="POST" role="none">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                role="menuitem" tabindex="-1">
                Log Out
            </button>
        </form>
    </div>
</div>