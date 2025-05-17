<?php
$pageTitle = "Order Management";
define('BASE_PATH', dirname(__DIR__, 3));
ob_start();
?>

<!-- admin/orders.php -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Orders Management</h1>
        <div class="flex space-x-3">
            <select
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option>All Status</option>
                <option>Pending</option>
                <option>Completed</option>
                <option>Cancelled</option>
            </select>
            <div class="relative">
                <input type="date"
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-calendar absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-medium text-gray-500">
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Kitchen</th>
                    <th class="px-6 py-3">Amount</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">#TC-4821</td>
                    <td class="px-6 py-4">
                        <div>Sarah Johnson</div>
                        <div class="text-sm text-gray-500">sarah@example.com</div>
                    </td>
                    <td class="px-6 py-4">Spice Delight</td>
                    <td class="px-6 py-4">₹450</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Delivered</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-orange-500 hover:text-orange-700">
                            <i class="fas fa-eye"></i> View
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