<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Error</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="font-sans bg-orange-50">
    <div class="h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <i class="fas fa-database text-6xl text-red-500 mb-4"></i>
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Database Connection Error
                </h2>
            </div>

            <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <?= htmlspecialchars($error['message']) ?>
                            </p>
                            <?php if (!empty($error['details'])): ?>
                                <p class="text-sm text-red-700 mt-1">
                                    <?= htmlspecialchars($error['details']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-center text-sm text-gray-600">
                    <p>Our technical team has been notified. Please try again later.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>