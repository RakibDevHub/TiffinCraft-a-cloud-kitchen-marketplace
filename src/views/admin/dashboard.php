<?php
define('BASE_PATH', dirname(__DIR__, 3));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                            700: '#a16207',
                            800: '#854d0e',
                            900: '#713f12',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 bg-white shadow-md w-64">
        <div class="flex items-center px-3 h-16 border-b">
            <img src="/assets/images/TiffinCraft.png" alt="TiffinCraft Logo" class="h-10 mr-2">
        </div>
        <div class="overflow-y-auto h-full py-4 px-3">
            <ul class="space-y-2">
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-white rounded-lg bg-primary-500">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-utensils mr-3"></i>
                        <span>Dishes</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-store mr-3"></i>
                        <span>Vendors</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-users mr-3"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-shopping-bag mr-3"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chart-line mr-3"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-cog mr-3"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-6 pt-2">
        <!-- Top Navigation -->
        <div class="flex justify-between items-center mb-6">
            <!-- <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1> -->
            <div class="relative max-w-[500px] w-[300px]">
                <input type="text" placeholder="Search..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <div class="flex items-center space-x-4">
                <button class="p-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bell"></i>
                </button>
                <!-- <div class="flex items-center">
                    <img src="<?= htmlspecialchars($_SESSION['profile_image'] ?? '/assets/images/default-profile.jpg') ?>"
                        alt="Profile" class="w-10 h-10 border-2 border-solid rounded-full">
                    <div class="flex flex-col">
                        <span class="ml-2 text-sm font-medium"><?= $user_name ?></span>
                        <span class="ml-2 text-[12px] capitalize"><?= $user_type ?></span>
                    </div>
                </div> -->

                <?php include BASE_PATH . '/src/includes/_profileDropdown.php' ?>

            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-2xl font-bold">1,248</p>
                    </div>
                    <div
                        class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-primary-100 text-primary-500">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
                <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 12% from last month</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Vendors</p>
                        <p class="text-2xl font-bold">86</p>
                    </div>
                    <div
                        class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 5 new this week</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Registered Users</p>
                        <p class="text-2xl font-bold">2,541</p>
                    </div>
                    <div
                        class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-green-100 text-green-500">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 8% from last month</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Revenue</p>
                        <p class="text-2xl font-bold">$24,780</p>
                    </div>
                    <div
                        class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-purple-100 text-purple-500">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <p class="text-sm text-red-500 mt-2"><i class="fas fa-arrow-down mr-1"></i> 2% from last month</p>
            </div>
        </div>

        <!-- Recent Orders and Popular Dishes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Orders -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Recent Orders</h2>
                    <a href="#" class="text-sm text-primary-500 hover:underline">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-left text-sm font-medium text-gray-500 border-b">
                                <th class="pb-2">Order ID</th>
                                <th class="pb-2">Customer</th>
                                <th class="pb-2">Amount</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="py-3">#TC-4821</td>
                                <td>Sarah Johnson</td>
                                <td>$24.50</td>
                                <td><span
                                        class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Delivered</span>
                                </td>
                            </tr>
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="py-3">#TC-4820</td>
                                <td>Michael Brown</td>
                                <td>$32.75</td>
                                <td><span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Processing</span>
                                </td>
                            </tr>
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="py-3">#TC-4819</td>
                                <td>Emily Davis</td>
                                <td>$18.90</td>
                                <td><span
                                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Shipped</span>
                                </td>
                            </tr>
                            <tr class="text-sm border-b hover:bg-gray-50">
                                <td class="py-3">#TC-4818</td>
                                <td>Robert Wilson</td>
                                <td>$45.20</td>
                                <td><span
                                        class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Cancelled</span>
                                </td>
                            </tr>
                            <tr class="text-sm hover:bg-gray-50">
                                <td class="py-3">#TC-4817</td>
                                <td>Jessica Lee</td>
                                <td>$28.60</td>
                                <td><span
                                        class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Delivered</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Popular Dishes -->
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Popular Dishes</h2>
                    <a href="#" class="text-sm text-primary-500 hover:underline">View All</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <img src="https://via.placeholder.com/60" alt="Dish" class="w-12 h-12 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium">Butter Chicken</h3>
                            <p class="text-sm text-gray-500">Spicy Kitchen</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$12.99</p>
                            <p class="text-xs text-gray-500">48 orders</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <img src="https://via.placeholder.com/60" alt="Dish" class="w-12 h-12 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium">Biryani</h3>
                            <p class="text-sm text-gray-500">Delhi Darbar</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$14.50</p>
                            <p class="text-xs text-gray-500">36 orders</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <img src="https://via.placeholder.com/60" alt="Dish" class="w-12 h-12 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium">Paneer Tikka</h3>
                            <p class="text-sm text-gray-500">Vegetarian Delight</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$10.99</p>
                            <p class="text-xs text-gray-500">32 orders</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <img src="https://via.placeholder.com/60" alt="Dish" class="w-12 h-12 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium">Chole Bhature</h3>
                            <p class="text-sm text-gray-500">Punjabi Tadka</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$9.99</p>
                            <p class="text-xs text-gray-500">28 orders</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                        <img src="https://via.placeholder.com/60" alt="Dish" class="w-12 h-12 rounded-lg object-cover">
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium">Masala Dosa</h3>
                            <p class="text-sm text-gray-500">South Indian Special</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">$8.50</p>
                            <p class="text-xs text-gray-500">25 orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Sales Overview</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm bg-primary-100 text-primary-500 rounded-lg">Monthly</button>
                        <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Weekly</button>
                        <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Daily</button>
                    </div>
                </div>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <p class="text-gray-400">Sales chart will be displayed here</p>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Recent Activities</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div
                            class="h-[2rem] w-[2rem] flex items-center justify-center bg-green-100 text-green-500 rounded-full mr-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-medium">New order #TC-4821 placed</p>
                            <p class="text-sm text-gray-500">2 min ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="h-[2rem] w-[2rem] flex items-center justify-center bg-blue-100 text-blue-500 rounded-full mr-3">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <p class="font-medium">New vendor "Spicy Kitchen" registered</p>
                            <p class="text-sm text-gray-500">1 hour ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="h-[2rem] w-[2rem] flex items-center justify-center bg-purple-100 text-purple-500 rounded-full mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-medium">New customer Sarah Johnson signed up</p>
                            <p class="text-sm text-gray-500">3 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="h-[2rem] w-[2rem] flex items-center justify-center bg-yellow-100 text-yellow-500 rounded-full mr-3">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div>
                            <p class="font-medium">Order #TC-4818 cancelled</p>
                            <p class="text-sm text-gray-500">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="h-[2rem] w-[2rem] flex items-center justify-center bg-red-100 text-red-500 rounded-full mr-3">
                            <i class="fas fa-bug"></i>
                        </div>
                        <div>
                            <p class="font-medium">System maintenance scheduled</p>
                            <p class="text-sm text-gray-500">Yesterday</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>