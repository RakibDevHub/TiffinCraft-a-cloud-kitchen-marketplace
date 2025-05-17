<!DOCTYPE html>
<html lang="en">

<head>
    <?php $title = $pageTitle;
    include BASE_PATH . '/src/includes/_head.php'; ?>
</head>

<body class="bg-gray-50">

    <?php include BASE_PATH . '/src/includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="relative top-[56px] max-w-7xl mx-auto p-4 transition-all duration-300 ease-in-out" id="main-content">
        <?= $content ?>
    </main>

</body>

</html>