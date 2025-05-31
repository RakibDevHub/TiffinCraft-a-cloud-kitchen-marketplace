<?php
$pageTitle = "Access Denied - TiffinCraft Business";
$error = $data['error']; // Optional: only if you're passing custom error info

ob_start();
?>

<div class="flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <i class="fas fa-user-lock text-6xl text-yellow-500 mb-4"></i>
            <h2 class="text-3xl font-extrabold text-gray-900">
                Access Denied
            </h2>
        </div>

        <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            You do not have permission to access this page.
                        </p>
                        <?php if (!empty($error['message'])): ?>
                            <p class="text-sm text-yellow-700 mt-1">
                                <?= htmlspecialchars($error['message']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-600">
                <p>If you believe this is an error, please contact support.</p>
                <p class="mt-4">
                    <a href="/" class="text-orange-600 hover:text-orange-500">
                        <i class="fas fa-arrow-left mr-1"></i> Return to homepage
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>