<?php
$pageTitle = "Dishes Management";
define('BASE_PATH', dirname(__DIR__, 3));
ob_start();
?>

<!-- admin/kitchens.php -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kitchen Approvals</h1>
        <div class="flex space-x-3">
            <div class="flex space-x-2">
                <button class="px-3 py-2 border rounded-lg text-sm font-medium bg-white hover:bg-gray-50">
                    All <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs ml-1">24</span>
                </button>
                <button
                    class="px-3 py-2 border rounded-lg text-sm font-medium bg-orange-100 text-orange-700 hover:bg-orange-200">
                    Pending <span class="bg-orange-200 text-orange-800 px-2 py-1 rounded-full text-xs ml-1">5</span>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Kitchen Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-48 bg-gray-200 relative">
                <img src="/assets/images/kitchen-sample.jpg" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <h3 class="text-white font-semibold">Spice Delight</h3>
                    <p class="text-white/90 text-sm">Mumbai, Maharashtra</p>
                </div>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <i class="fas fa-user mr-1"></i> Rajesh Kumar
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span>4.5 (32 reviews)</span>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                </div>
                <div class="flex space-x-2">
                    <button
                        class="flex-1 bg-green-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-600">
                        <i class="fas fa-check mr-1"></i> Approve
                    </button>
                    <button class="flex-1 bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                </div>
            </div>
        </div>
        <!-- Additional kitchen cards... -->
    </div>
</div>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>