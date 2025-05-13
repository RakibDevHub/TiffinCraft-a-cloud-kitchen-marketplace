<?php
// src/includes/_dropdownNavlinks.php

$user_data = [
    'name' => $_SESSION['name'],
    'role' => $_SESSION['role'],
    'email' => $_SESSION['email'],
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
$user_email = htmlspecialchars($user_data['email']);
?>

<div class="!ml-2 relative" id="user-dropdown-container">
    <!-- Dropdown Toggle Button -->
    <button type="button" id="user-dropdown-button"
        class="bg-white rounded-full flex justify-center items-center gap-2 text-sm text-left" aria-expanded="false"
        aria-haspopup="true" aria-controls="user-dropdown">
        <span class="sr-only">User menu</span>
        <img class="h-8 w-8 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            src="<?= htmlspecialchars($user_data['profile_image']) ?>" alt="<?= $user_name ?>'s profile picture">
    </button>

    <!-- Dropdown -->
    <div id="user-dropdown"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-60"
        role="menu" aria-labelledby="user-dropdown-button">

        <div class="flex flex-col justify-center items-center p-4 border-b border-gray-200">
            <span class="font-medium text-gray-700"><?= $user_name ?></span>
            <span class="font-normal text-sm text-gray-700"><?= $user_email ?></span>
            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs"><?= $user_type ?></span>
        </div>

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