<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <?php $title = $pageTitle;
    include BASE_PATH . '/src/includes/_head.php'; ?>
</head>

<body class="font-sans">
    <?php include BASE_PATH . '/src/includes/navbar.php' ?>
    <main class="relative top-[56px]">
        <?= $content ?>
    </main>
    <?php include BASE_PATH . '/src/includes/footer.php' ?>

    <script src="/assets/js/script.js" defer></script>
</body>

</html>