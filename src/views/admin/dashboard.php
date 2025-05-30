<?php
$pageTitle = "Admin Dashboard";
$users = $data['users'];
$error = $data['error'];
ob_start();
?>

<!-- ADMIN DASHBOARD CONTENT -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <a href="/admin/users">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold"><?= $users['users_count'] ?></p>
                </div>
                <div
                    class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-primary-100 text-primary-500">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 8% from last month</p>
        </div>
    </a>

    <!-- Active Kitchens -->
    <a href="/admin/kitchens">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Kitchens</p>
                    <p class="text-2xl font-bold">86</p>
                </div>
                <div
                    class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-green-100 text-green-500">
                    <i class="fas fa-store"></i>
                </div>
            </div>
            <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 5 new this week</p>
        </div>
    </a>

    <!-- Pending Approvals -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pending Approvals</p>
                <p class="text-2xl font-bold">12</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">3 kitchens, 9 dishes</p>
    </div>

    <!-- Platform Revenue -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Platform Revenue</p>
                <p class="text-2xl font-bold">$24,780</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-purple-100 text-purple-500">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 15% from last month</p>
    </div>
</div>

<!-- Recent Orders and Pending Approvals -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
            <a href="/admin/orders" class="text-sm text-primary-500 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-sm font-medium text-gray-500 border-b">
                        <th class="pb-2">Order ID</th>
                        <th class="pb-2">Customer</th>
                        <th class="pb-2">Kitchen</th>
                        <th class="pb-2">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4821</td>
                        <td>Sarah Johnson</td>
                        <td>Spicy Kitchen</td>
                        <td>$24.50</td>
                    </tr>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4820</td>
                        <td>Michael Brown</td>
                        <td>Delhi Darbar</td>
                        <td>$32.75</td>
                    </tr>
                    <tr class="text-sm hover:bg-gray-50">
                        <td class="py-3">#TC-4819</td>
                        <td>Emily Davis</td>
                        <td>Punjabi Tadka</td>
                        <td>$18.90</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Pending Approvals</h2>
            <a href="/admin/approvals" class="text-sm text-primary-500 hover:underline">Manage</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div>
                    <h3 class="font-medium">Spicy Tadka</h3>
                    <p class="text-sm text-gray-500">New kitchen application</p>
                </div>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-lg">Review</button>
            </div>
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                <div>
                    <h3 class="font-medium">Paneer Special</h3>
                    <p class="text-sm text-gray-500">New dish from Punjabi Tadka</p>
                </div>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-lg">Review</button>
            </div>
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                <div>
                    <h3 class="font-medium">Mumbai Street Food</h3>
                    <p class="text-sm text-gray-500">New kitchen application</p>
                </div>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-lg">Review</button>
            </div>
        </div>
    </div>
</div>

<!-- Platform Stats and Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Platform Stats -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Platform Growth</h2>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm bg-primary-100 text-primary-500 rounded-lg">Monthly</button>
                <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Weekly</button>
            </div>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-400">Growth chart will be displayed here</p>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">System Activities</h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-green-100 text-green-500 rounded-full mr-3">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <p class="font-medium">New kitchen "Spicy Tadka" approved</p>
                    <p class="text-sm text-gray-500">2 hours ago</p>
                </div>
            </div>
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-blue-100 text-blue-500 rounded-full mr-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p class="font-medium">New admin user created</p>
                    <p class="text-sm text-gray-500">5 hours ago</p>
                </div>
            </div>
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-purple-100 text-purple-500 rounded-full mr-3">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <p class="font-medium">New platform promotion launched</p>
                    <p class="text-sm text-gray-500">Yesterday</p>
                </div>
            </div>
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-red-100 text-red-500 rounded-full mr-3">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div>
                    <p class="font-medium">User complaint received</p>
                    <p class="text-sm text-gray-500">2 days ago</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';

?>