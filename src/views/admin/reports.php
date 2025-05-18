<?php
$pageTitle = "Report Management";
ob_start();
?>

<!-- admin/reports.php -->
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Platform Reports</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Report Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold">₹1,24,780</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
            <div class="h-40 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-400">Revenue chart</p>
            </div>
        </div>

        <!-- Report Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">New Users</p>
                    <p class="text-2xl font-bold">248</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="h-40 bg-gray-50 rounded-lg flex items-center justify-center">
                <p class="text-gray-400">User growth chart</p>
            </div>
        </div>

        <!-- Additional report cards... -->
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Export Data</h2>
            <button class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600">
                <i class="fas fa-download mr-1"></i> Generate Report
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center mr-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="font-medium">User Data</span>
                </div>
            </div>

            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center mr-3">
                        <i class="fas fa-store"></i>
                    </div>
                    <span class="font-medium">Kitchen Data</span>
                </div>
            </div>

            <!-- Additional export options... -->
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>