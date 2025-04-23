<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft - Homemade Meals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <?php include '../../src/includes/header.php'; ?>

    <section class="min-h-screen flex items-center justify-center bg-orange-50 py-16 px-4">
        <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-lg">
            <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Create Your TiffinCraft Account</h2>
            <form class="space-y-6" action="register_buyer_step1.php" method="POST">
                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone" id="phone" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Address Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <!-- Street Address -->
                        <input type="text" name="street_address" placeholder="Street Address" required
                            class="col-span-2 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />

                        <!-- City -->
                        <input type="text" name="city" placeholder="City" required
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />

                        <!-- Area / Locality -->
                        <input type="text" name="area" placeholder="Area / Locality" required
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />

                        <!-- ZIP / Postal Code -->
                        <input type="text" name="zip" placeholder="Postal Code" required
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>

                <!-- Next Button -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">Next</button>
                </div>
            </form>

        </div>
    </section>

    <?php include '../../src/includes/footer.php'; ?>
</body>

</html>