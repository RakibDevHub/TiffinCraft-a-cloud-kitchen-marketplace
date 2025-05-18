<?php
$pageTitle = "Dishes Management";
ob_start();
?>

<!-- admin/dishes.php -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Dishes Management</h1>
        <div class="flex space-x-3">
            <select
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option>All Kitchens</option>
                <option>Spice Delight</option>
                <option>Punjabi Tadka</option>
            </select>
            <div class="relative">
                <input type="text" placeholder="Search dishes..."
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm w-64 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-medium text-gray-500">
                    <th class="px-6 py-3">Dish</th>
                    <th class="px-6 py-3">Kitchen</th>
                    <th class="px-6 py-3">Price</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-md bg-gray-200 overflow-hidden mr-3">
                                <img src="/assets/images/dish-sample.jpg" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="font-medium">Butter Chicken</div>
                                <div class="text-sm text-gray-500">North Indian</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">Spice Delight</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">₹220</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-orange-500 hover:text-orange-700 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-500 hover:text-red-700">
                            <i class="fas fa-ban"></i>
                        </button>
                    </td>
                </tr>
                <!-- Additional rows... -->
            </tbody>
        </table>

        <!-- Pagination would go here -->
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>