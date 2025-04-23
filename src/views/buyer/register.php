<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <?php include BASE_PATH . '/src/includes/header.php' ?>

    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
            <div>
                <h2 class="text-center text-3xl font-extrabold text-gray-900">Create Your Buyer Account</h2>
            </div>

            <form class="mt-8 space-y-6" action="/src/controllers/register_buyer.php" method="POST">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="John Doe">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" name="email" type="email" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="you@example.com">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input id="address" name="address" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="123 Street Name">
                    </div>

                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input id="city" name="city" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Dhaka">
                    </div>

                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input id="postal_code" name="postal_code" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="1207">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Login here</a>
            </p>
        </div>
    </div>

    <?php include BASE_PATH . '/src/includes/footer.php' ?>
</body>

</html>