<?php
define('BASE_PATH', dirname(__DIR__, 2));
$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false;

?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <?php $title = 'TiffinCraft - Homemade Meals';
    include BASE_PATH . '/src/includes/_head.php'; ?>
</head>

<body class="font-sans">
    <?php include BASE_PATH . '/src/includes/navbar.php' ?>
    <main>
        <?php if (!$isBusinessView): ?>
            <?php include BASE_PATH . '/src/includes/buyer/_buyerContent.php' ?>
        <?php else: ?>
            <?php include BASE_PATH . '/src/includes/seller/_sellerContent.php' ?>
        <?php endif; ?>
    </main>

    <?php include BASE_PATH . '/src/includes/footer.php' ?>

</body>

</html>