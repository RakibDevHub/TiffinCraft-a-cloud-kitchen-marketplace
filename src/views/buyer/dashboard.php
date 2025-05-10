<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <?php $title = 'TiffinCraft - Homemade Meals';
    include BASE_PATH . '/src/includes/_head.php'; ?>
</head>

<body class="font-sans">
    <?php include BASE_PATH . '/src/includes/navbar.php' ?>

    <section class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-7xl mx-auto relative top-12">
            <h1 class="text-3xl font-bold mb-6">Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card: Orders -->
                <div class="bg-white p-5 rounded-2xl shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Recent Orders</h2>
                    <p class="text-gray-600">You have 3 ongoing orders.</p>
                    <a href="/buyer/orders.php" class="text-blue-600 text-sm mt-2 inline-block">View Orders</a>
                </div>

                <!-- Card: Profile -->
                <div class="bg-white p-5 rounded-2xl shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Your Profile</h2>
                    <p class="text-gray-600">Manage your information and preferences.</p>
                    <a href="/buyer/profile.php" class="text-blue-600 text-sm mt-2 inline-block">Edit Profile</a>
                </div>

                <!-- Card: Browse Dishes -->
                <div class="bg-white p-5 rounded-2xl shadow-md">
                    <h2 class="text-xl font-semibold mb-2">Explore Delicious Dishes</h2>
                    <p class="text-gray-600">Find new meals from your favorite kitchens.</p>
                    <a href="/dishes" class="text-blue-600 text-sm mt-2 inline-block">Browse Dishes</a>
                </div>

            </div>
        </div>
    </section>


    <?php include BASE_PATH . '/src/includes/footer.php' ?>

</body>

</html>