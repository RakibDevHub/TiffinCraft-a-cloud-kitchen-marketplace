<?php
$user_data = [
    'name' => $_SESSION['name'],
    'role' => $_SESSION['role'],
    'email' => $_SESSION['email'],
    'profile_image' => $_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg',
];

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

<div class="!ml-2 relative " id="dropdown-container">
    <div class="flex flex-row items-center gap-1 sm:gap-2">
        <div class="">
            <button id="notification-button"
                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-300 rounded-full bg-gray-100 hover:focus:outline-none hover:focus:ring-2 hover:focus:ring-inset hover:focus:ring-orange-500 transition-colors duration-200">
                <i class="fas fa-bell h-4 sm:h-6 w-4 sm:w-6 flex justify-center items-center"></i>
            </button>
            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-orange-500"></span>
        </div>

        <!-- Dropdown Toggle Button -->
        <button type="button" id="user-dropdown-button"
            class="rounded-full bg-gray-300 flex justify-center items-center gap-2 text-sm text-left h-[32px] w-[32px] sm:h-[40px] sm:w-[40px] hover:focus:outline-none hover:focus:ring-2 hover:focus:ring-inset hover:focus:ring-orange-500 transition-colors duration-200"
            aria-expanded="false" aria-haspopup="true" aria-controls="user-dropdown">
            <span class="sr-only">User menu</span>
            <img class="h-[27px] w-[27px] sm:h-[35px] sm:w-[35px] rounded-full"
                src="<?= htmlspecialchars($user_data['profile_image']) ?>" alt="<?= $user_name ?>'s profile picture">
        </button>
    </div>

    <!-- Dropdown -->
    <div id="notification-dropdown"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-60"
        role="menu" aria-labelledby="notification-button">

        <div class="flex flex-col justify-center items-center p-4 border-b border-gray-200">
            <span class="font-medium text-gray-700"><?= $user_name ?></span>
            <span class="font-normal text-sm text-gray-700"><?= $user_email ?></span>
            <span
                class="absolute top-[1px] right-[1px] bg-orange-200 text-orange-800 text-xs font-medium rounded-tr-md rounded-bl-md px-3 py-1 capitalize"><?= $user_type ?></span>
        </div>

        <a href="<?= $views['dashboard'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Dashboard
        </a>
        <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
            Profile
        </a>
        <a href="<?= $views['settings'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Settings
        </a>
        <form action="/logout" method="POST" role="none">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100"
                role="menuitem" tabindex="-1">
                Log Out
            </button>
        </form>
    </div>

    <div id="user-dropdown"
        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-60"
        role="menu" aria-labelledby="user-dropdown-button">

        <div class="flex flex-col justify-center items-center p-4 border-b border-gray-200">
            <span class="font-medium text-gray-700"><?= $user_name ?></span>
            <span class="font-normal text-sm text-gray-700"><?= $user_email ?></span>
            <span
                class="absolute top-[1px] right-[1px] bg-orange-200 text-orange-800 text-xs font-medium rounded-tr-md rounded-bl-md px-3 py-1 capitalize"><?= $user_type ?></span>
        </div>

        <a href="<?= $views['dashboard'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Dashboard
        </a>
        <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
            Profile
        </a>
        <a href="<?= $views['settings'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Settings
        </a>
        <form action="/logout" method="POST" role="none">
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100"
                role="menuitem" tabindex="-1">
                Log Out
            </button>
        </form>
    </div>
</div>