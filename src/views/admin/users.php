<?php
$pageTitle = "User Management";
define('BASE_PATH', dirname(__DIR__, 3));
ob_start();
?>

<!-- admin/users.php -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">User Management</h1>
        <div class="flex space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search users..."
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm w-80 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <!-- <button class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600">
                <i class="fas fa-plus mr-1"></i> Add User
            </button> -->
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-sm font-medium text-gray-500">
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <div class="font-medium">John Doe</div>
                                <div class="text-sm text-gray-500">john@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Customer</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">2023-05-15</td>
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

        <!-- Pagination -->
        <div class="px-6 py-4 border-t flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span
                    class="font-medium">24</span> results
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button
                    class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-orange-500 hover:bg-orange-600">
                    1
                </button>
                <button class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    2
                </button>
                <button class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>