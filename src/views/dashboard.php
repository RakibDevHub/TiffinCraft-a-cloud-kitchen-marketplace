<!DOCTYPE html>
<html lang="en">

<head>
    <?php $title = $pageTitle;
    include BASE_PATH . '/src/includes/header.php'; ?>
</head>

<body class="bg-gray-50">
    <?php include BASE_PATH . '/src/includes/navbar.php'; ?>

    <!-- Main Content -->
    <main class="relative top-[56px] max-w-7xl mx-auto p-4 transition-all duration-300 ease-in-out" id="main-content">
        <?php include BASE_PATH . '/src/includes/alert.php'; ?>
        <?= $content ?>
    </main>

    <script src="/assets/js/script.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            offset: 200,
            duration: 800,
            easing: "ease-in-sine",
            delay: 100,
            once: false,
            mirror: true
        });
    </script>
</body>

</html>