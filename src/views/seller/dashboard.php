<?php
define('BASE_PATH', dirname(__DIR__, 3));
$pageTitle = "Seller Dashboard";
ob_start();
?>

<!-- SELLER DASHBOARD CONTENT -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Today's Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Today's Orders</p>
                <p class="text-2xl font-bold">12</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-primary-100 text-primary-500">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 3 new this morning</p>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Monthly Revenue</p>
                <p class="text-2xl font-bold">$1,245</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-green-100 text-green-500">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 15% from last month</p>
    </div>

    <!-- Active Subscribers -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Subscribers</p>
                <p class="text-2xl font-bold">24</p>
            </div>
            <div class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">2 new this week</p>
    </div>

    <!-- Average Rating -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Average Rating</p>
                <p class="text-2xl font-bold">4.7</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">From 86 reviews</p>
    </div>
</div>

<!-- Recent Orders and Popular Items -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
            <a href="/business/orders" class="text-sm text-primary-500 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-sm font-medium text-gray-500 border-b">
                        <th class="pb-2">Order ID</th>
                        <th class="pb-2">Customer</th>
                        <th class="pb-2">Items</th>
                        <th class="pb-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4821</td>
                        <td>Sarah J.</td>
                        <td>3</td>
                        <td><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Ready</span>
                        </td>
                    </tr>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4820</td>
                        <td>Michael B.</td>
                        <td>2</td>
                        <td><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Preparing</span>
                        </td>
                    </tr>
                    <tr class="text-sm hover:bg-gray-50">
                        <td class="py-3">#TC-4819</td>
                        <td>Emily D.</td>
                        <td>1</td>
                        <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">New</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popular Items -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Top Selling Items</h2>
            <a href="/business/menu" class="text-sm text-primary-500 hover:underline">Manage Menu</a>
        </div>
        <div class="space-y-4">
            <?php
            $popularItems = [
                ['name' => 'Butter Chicken', 'price' => '12.99', 'orders' => '48', 'img' => 'https://via.placeholder.com/60'],
                ['name' => 'Vegetable Biryani', 'price' => '10.50', 'orders' => '36', 'img' => 'https://via.placeholder.com/60'],
                ['name' => 'Paneer Tikka', 'price' => '8.99', 'orders' => '32', 'img' => 'https://via.placeholder.com/60']
            ];

            foreach ($popularItems as $item): ?>
                <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                    <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>" class="w-12 h-12 rounded-lg object-cover">
                    <div class="ml-4 flex-1">
                        <h3 class="font-medium"><?= $item['name'] ?></h3>
                        <p class="text-sm text-gray-500">$<?= $item['price'] ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium"><?= $item['orders'] ?></p>
                        <p class="text-xs text-gray-500">orders</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Revenue Chart and Recent Reviews -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Revenue Chart -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Weekly Revenue</h2>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm bg-primary-100 text-primary-500 rounded-lg">Weekly</button>
                <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">Monthly</button>
            </div>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <p class="text-gray-400">Revenue chart will be displayed here</p>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recent Reviews</h2>
            <a href="/business/reviews" class="text-sm text-primary-500 hover:underline">View All</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-blue-100 text-blue-500 rounded-full mr-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="flex items-center mb-1">
                        <div class="flex mr-2">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="font-medium">Sarah Johnson</p>
                    </div>
                    <p class="text-sm">The butter chicken was amazing! Will definitely order again.</p>
                    <p class="text-xs text-gray-500 mt-1">2 days ago</p>
                </div>
            </div>
            <div class="flex items-start">
                <div
                    class="h-[2rem] w-[2rem] flex items-center justify-center bg-blue-100 text-blue-500 rounded-full mr-3">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="flex items-center mb-1">
                        <div class="flex mr-2">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            <?php endfor; ?>
                            <i class="fas fa-star text-gray-300 text-xs"></i>
                        </div>
                        <p class="font-medium">Michael Brown</p>
                    </div>
                    <p class="text-sm">Good food but delivery was a bit late.</p>
                    <p class="text-xs text-gray-500 mt-1">1 week ago</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';

?>