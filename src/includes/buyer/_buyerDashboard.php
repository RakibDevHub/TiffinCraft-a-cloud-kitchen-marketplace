<!-- BUYER DASHBOARD CONTENT -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Orders Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Your Orders</p>
                <p class="text-2xl font-bold">14</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-primary-100 text-primary-500">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <p class="text-sm text-green-500 mt-2"><i class="fas fa-arrow-up mr-1"></i> 2 new this week</p>
    </div>

    <!-- Subscriptions Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Active Subscriptions</p>
                <p class="text-2xl font-bold">3</p>
            </div>
            <div class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Next delivery in 2 days</p>
    </div>

    <!-- Favorites Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Saved Favorites</p>
                <p class="text-2xl font-bold">8</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-green-100 text-green-500">
                <i class="fas fa-heart"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">3 kitchens, 5 dishes</p>
    </div>

    <!-- Loyalty Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Loyalty Points</p>
                <p class="text-2xl font-bold">1,250</p>
            </div>
            <div
                class="h-[2.5rem] w-[2.5rem] flex items-center justify-center rounded-full bg-purple-100 text-purple-500">
                <i class="fas fa-award"></i>
            </div>
        </div>
        <p class="text-sm text-green-500 mt-2">Earned 50 points this week</p>
    </div>
</div>

<!-- Recent Orders and Recommended Dishes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
            <a href="/orders" class="text-sm text-primary-500 hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-sm font-medium text-gray-500 border-b">
                        <th class="pb-2">Order ID</th>
                        <th class="pb-2">Kitchen</th>
                        <th class="pb-2">Amount</th>
                        <th class="pb-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4821</td>
                        <td>Spicy Kitchen</td>
                        <td>$24.50</td>
                        <td><span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Delivered</span>
                        </td>
                    </tr>
                    <tr class="text-sm border-b hover:bg-gray-50">
                        <td class="py-3">#TC-4820</td>
                        <td>Delhi Darbar</td>
                        <td>$32.75</td>
                        <td><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Processing</span>
                        </td>
                    </tr>
                    <tr class="text-sm hover:bg-gray-50">
                        <td class="py-3">#TC-4819</td>
                        <td>Punjabi Tadka</td>
                        <td>$18.90</td>
                        <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Shipped</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recommended Dishes -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recommended For You</h2>
            <a href="/dishes" class="text-sm text-primary-500 hover:underline">Browse All</a>
        </div>
        <div class="space-y-4">
            <?php
            $recommendedDishes = [
                ['name' => 'Paneer Butter Masala', 'kitchen' => 'Spicy Kitchen', 'price' => '12.99', 'orders' => '48', 'img' => 'https://via.placeholder.com/60'],
                ['name' => 'Chicken Biryani', 'kitchen' => 'Delhi Darbar', 'price' => '14.50', 'orders' => '36', 'img' => 'https://via.placeholder.com/60'],
                ['name' => 'Dal Makhani', 'kitchen' => 'Punjabi Tadka', 'price' => '10.99', 'orders' => '32', 'img' => 'https://via.placeholder.com/60']
            ];

            foreach ($recommendedDishes as $dish): ?>
                <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg">
                    <img src="<?= $dish['img'] ?>" alt="<?= $dish['name'] ?>" class="w-12 h-12 rounded-lg object-cover">
                    <div class="ml-4 flex-1">
                        <h3 class="font-medium"><?= $dish['name'] ?></h3>
                        <p class="text-sm text-gray-500"><?= $dish['kitchen'] ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">$<?= $dish['price'] ?></p>
                        <p class="text-xs text-gray-500"><?= $dish['orders'] ?> orders</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Subscription Status and Favorite Kitchens -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Subscription Status -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Your Subscriptions</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                <div>
                    <h3 class="font-medium">Weekly Tiffin Plan</h3>
                    <p class="text-sm text-gray-500">Spicy Kitchen</p>
                </div>
                <div class="text-right">
                    <p class="font-medium">$45.00/week</p>
                    <p class="text-xs text-gray-500">Next: Tomorrow</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                <div>
                    <h3 class="font-medium">Lunch Combo</h3>
                    <p class="text-sm text-gray-500">Delhi Darbar</p>
                </div>
                <div class="text-right">
                    <p class="font-medium">$60.00/week</p>
                    <p class="text-xs text-gray-500">Paused</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorite Kitchens -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Favorite Kitchens</h2>
            <a href="/kitchens" class="text-sm text-primary-500 hover:underline">View All</a>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <?php
            $favoriteKitchens = [
                ['name' => 'Spicy Kitchen', 'rating' => '4.8', 'img' => 'https://via.placeholder.com/100'],
                ['name' => 'Delhi Darbar', 'rating' => '4.6', 'img' => 'https://via.placeholder.com/100'],
                ['name' => 'Punjabi Tadka', 'rating' => '4.5', 'img' => 'https://via.placeholder.com/100'],
                ['name' => 'South Indian Special', 'rating' => '4.7', 'img' => 'https://via.placeholder.com/100']
            ];

            foreach ($favoriteKitchens as $kitchen): ?>
                <div class="text-center">
                    <img src="<?= $kitchen['img'] ?>" alt="<?= $kitchen['name'] ?>"
                        class="w-16 h-16 rounded-full object-cover mx-auto mb-2">
                    <h3 class="font-medium text-sm"><?= $kitchen['name'] ?></h3>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <span class="text-xs ml-1"><?= $kitchen['rating'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>