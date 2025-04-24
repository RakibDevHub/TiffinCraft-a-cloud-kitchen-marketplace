<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft - Buyer Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-orange-50">

    <?php include BASE_PATH . '/src/includes/header.php'; ?>

    <section class="min-h-screen relative top-1 bg-orange-50 flex items-center justify-center py-10">
        <div class="w-full max-w-xl bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Buyer Registration</h2>

            <form id="buyerRegisterForm" method="POST" action="/src/controllers/register_buyer.php" class="space-y-6">
                <!-- Step 1: Personal Info -->
                <div id="step1">
                    <div class="mb-4">
                        <label for="name" class="block font-semibold mb-1">Full Name</label>
                        <input type="text" name="name" id="name" required class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block font-semibold mb-1">Address</label>
                        <input type="text" name="address" id="address" required class="w-full border p-2 rounded"
                            placeholder="e.g., Bashundhara, Block B, Vatara, Dhaka 1212" />
                    </div>


                    <button type="button" onclick="nextStep()"
                        class="bg-blue-700 text-white px-4 py-2 rounded">Next</button>
                </div>

                <!-- Step 2: Account Setup -->
                <div id="step2" class="hidden">
                    <div class="mb-4">
                        <label for="email" class="block font-semibold mb-1">Email</label>
                        <input type="email" name="email" id="email" required class="w-full border p-2 rounded" />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block font-semibold mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full border p-2 rounded" />
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="prevStep()"
                            class="bg-gray-500 text-white px-4 py-2 rounded">Back</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        function nextStep() {
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        }
        function prevStep() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        }
    </script>

    <?php include BASE_PATH . '/src/includes/footer.php'; ?>

</body>

</html>