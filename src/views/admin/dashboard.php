<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <?php include BASE_PATH . '/src/includes/navbar.php' ?>


    <!-- CTA Footer Section -->
    <section class="bg-orange-500 py-12 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ready to taste the best homemade food?</h2>
        <a href="#explore"
            class="bg-white text-orange-500 font-semibold py-3 px-6 rounded-lg transition hover:bg-gray-100">Start
            Ordering Now</a>
    </section>

    <?php include BASE_PATH . '/src/includes/footer.php' ?>

</body>

</html>