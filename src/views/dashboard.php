<?php
define('BASE_PATH', dirname(__DIR__, 2));
$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/business') !== false;
$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$currentRole = $isLoggedIn ? $_SESSION['role'] : null;

$views = [
    'profile' => '/profile',
    'settings' => '/settings',
    'dashboard' => '/dashboard'
];

$user = 'Buyer';

if ($currentRole === 'seller') {
    $views = [
        'profile' => '/business/profile',
        'settings' => '/business/settings',
        'dashboard' => '/business/dashboard'
    ];
    $user = 'Business';
} elseif ($currentRole === 'admin') {
    $views = [
        'profile' => '/admin/profile',
        'settings' => '/admin/settings',
        'dashboard' => '/admin/dashboard'
    ];
    $user = 'Admin';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php $title = $user . ' Dashboard';
    include BASE_PATH . '/src/includes/_head.php'; ?>
</head>

<body class="bg-gray-50">

    <?php include BASE_PATH . '/src/includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="relative top-[56px] max-w-7xl mx-auto p-4 transition-all duration-300 ease-in-out" id="main-content">

        <?php if ($currentRole === 'buyer' || !$isLoggedIn): ?>
            <?php include BASE_PATH . '/src/includes/buyer/_buyerDashboard.php' ?>
        <?php elseif ($currentRole === 'seller'): ?>
            <?php include BASE_PATH . '/src/includes/seller/_sellerDashboard.php' ?>
        <?php elseif ($currentRole === 'admin'): ?>
            <?php include BASE_PATH . '/src/includes/admin/_adminDashboard.php' ?>
        <?php endif; ?>
    </main>
</body>

</html>