<?php
$error = $error ?? [
    'message' => 'Database Connection Error',
    'details' => 'We’re having trouble connecting to the database. Please try again shortly.',
    'timestamp' => date('Y-m-d h:i A'),
];

$retryCooldown = $retryCooldown ?? 30;
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <title>TiffinCraft - Database Error</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let retrySeconds = <?= $retryCooldown ?>;
        let retryBtn;

        function startCountdown() {
            retryBtn.disabled = true;
            retryBtn.classList.add("opacity-50", "cursor-not-allowed");
            retryBtn.innerHTML = `Retrying in <span id="countdown">${retrySeconds}</span>s`;

            const countdown = () => {
                retrySeconds--;
                const el = document.getElementById('countdown');
                if (el) el.textContent = retrySeconds;

                if (retrySeconds <= 0) {
                    retryBtn.innerHTML = "Retry Now";
                    retryBtn.disabled = false;
                    retryBtn.classList.remove("opacity-50", "cursor-not-allowed");
                    retrySeconds = <?= $retryCooldown ?>;
                } else {
                    setTimeout(countdown, 1000);
                }
            };

            setTimeout(countdown, 1000);
        }

        document.addEventListener("DOMContentLoaded", () => {
            retryBtn = document.getElementById("retryBtn");
            retryBtn.addEventListener("click", () => {
                startCountdown();
                window.location.href = "/database-error";
            });
            startCountdown();
        });
    </script>
</head>

<body class="bg-orange-50 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white max-w-md w-full p-8 rounded-lg shadow-lg text-center">
        <!-- Icon -->
        <div class="mb-4 flex justify-center">
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <!-- Title & Message -->
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Oops! Something Went Wrong</h2>
        <p class="text-red-600 font-medium"><?= htmlspecialchars($error['message']) ?></p>
        <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($error['details']) ?></p>

        <!-- Retry Button -->
        <div class="mt-6">
            <button id="retryBtn"
                class="w-full px-4 py-2 bg-white border border-gray-300 text-sm text-gray-700 font-medium rounded-md shadow cursor-not-allowed opacity-50"
                disabled>
                Retry <span id="countdown"><?= $retryCooldown ?></span>s
            </button>
        </div>

        <!-- Timestamp -->
        <p class="mt-6 text-xs text-gray-400">Timestamp: <?= htmlspecialchars($error['timestamp']) ?></p>
    </div>
</body>

</html>