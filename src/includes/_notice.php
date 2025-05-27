<?php
$sessionData = [
    'isUserSuspended' => $_SESSION['isUserSuspended'] ?? false,
    'userSuspendedUntil' => $_SESSION['userSuspendedUntil'] ?? '',
    'isKitchenSuspended' => $_SESSION['isKitchenSuspended'] ?? false,
    'kitchenSuspendedUntil' => $_SESSION['kitchenSuspendedUntil'] ?? ''
];
?>

<?php if (!empty($sessionData['isUserSuspended']) && !empty($sessionData['userSuspendedUntil'])): ?>
    <?php
    $dt = new DateTime($sessionData['userSuspendedUntil']);
    $formatted = $dt->format('d M Y, g:i A');
    ?>
    <div class="bg-white px-6 py-3 rounded-lg shadow mb-6 relative" id="userSuspendedNotice">
        <button onclick="document.getElementById('userSuspendedNotice').style.display='none'"
            class="absolute top-2 right-3 text-gray-400 font-bold text-lg hover:text-gray-600">&times;</button>
        <span class="font-medium text-sm text-red-600">Your Account has been suspended until <?= $formatted ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($sessionData['isKitchenSuspended']) && !empty($sessionData['kitchenSuspendedUntil'])): ?>
    <?php
    $dt = new DateTime($sessionData['kitchenSuspendedUntil']);
    $formatted = $dt->format('d M Y, g:i A');
    ?>
    <div class="bg-white px-6 py-3 rounded-lg shadow mb-6 relative" id="kitchenSuspendedNotice">
        <button onclick="document.getElementById('kitchenSuspendedNotice').style.display='none'"
            class="absolute top-2 right-3 text-gray-400 font-bold text-lg hover:text-gray-600">&times;</button>
        <span class="font-medium text-sm text-red-600">Your Kitchen has been suspended until <?= $formatted ?></span>
    </div>
<?php endif; ?>