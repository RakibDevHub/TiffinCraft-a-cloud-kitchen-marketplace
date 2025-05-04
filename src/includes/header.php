<?php
// define('BASE_PATH', dirname(__DIR__, 3));
?>

<?php if (strpos($_SERVER['REQUEST_URI'], '/business') !== false): ?>
    <?php include BASE_PATH . '/src/includes/navbar-seller.php'; ?>
<?php else: ?>
    <?php include BASE_PATH . '/src/includes/navbar-buyer.php'; ?>
<?php endif; ?>