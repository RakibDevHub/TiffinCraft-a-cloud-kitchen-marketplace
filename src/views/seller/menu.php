<?php
$pageTitle = "Manage Menu";

$selectedCategory = isset($_GET['category']) ? urldecode(trim($_GET['category'])) : null;
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : null;
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 6;

$menuItems = $data['menuItems'] ?? [];
$categories = $data['categories'] ?? [];
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;

$totalItems = $data['totalItems'] ?? 0;
$totalPages = $data['totalPages'] ?? 1;

$helper = new App\Utils\Helper();
$csrfToken = $helper->generateCsrfToken();

$addItem = isset($_GET['add']) ? true : false;

$viewId = isset($_GET['view']) ? (int) $_GET['view'] : null;
$viewItem = $viewId
    ? current(array_filter($menuItems, fn($item) => $item['item_id'] == $viewId))
    : null;

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$editItem = $editId
    ? current(array_filter($menuItems, fn($item) => $item['item_id'] == $editId))
    : null;

$deleteId = isset($_GET['delete']) ? (int) $_GET['delete'] : null;
$deleteItem = $deleteId
    ? current(array_filter($menuItems, fn($item) => $item['item_id'] == $deleteId))
    : null;

ob_start();
?>

<!-- Toast Messages -->
<?php if ($success): ?>
    <div id="toast-success"
        class="fixed top-12 right-6 flex items-center w-full max-w-xs p-4 mb-4 text-green-700 bg-white border border-green-200 rounded-lg shadow-md z-50"
        role="alert">
        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
            <i class="fas fa-check text-green-600"></i>
        </div>
        <div class="ms-3 text-sm font-medium flex-1"><?= htmlspecialchars($success) ?></div>
        <button onclick="this.parentElement.remove()" type="button"
            class="ms-auto text-gray-500 hover:text-gray-800 rounded-lg p-1.5 focus:ring-2 focus:ring-gray-300"
            aria-label="Close">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
<?php endif; ?>

<!-- Main Content -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 bg-white rounded-lg shadow-sm p-6">
        <div class="w-full flex flex-row justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Menu Management</h1>
            </div>
            <button onclick="window.location.href='?add=true'"
                class="flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                <i class="fas fa-plus mr-2"></i> Add New Item
            </button>
        </div>

        <!-- Controls: View, Search, Filters -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- View Toggle -->
            <div class="flex rounded-md overflow-hidden w-full sm:w-auto">
                <button id="cardViewBtn"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 transition">
                    <i class="fas fa-th-large"></i> Card View
                </button>
                <button id="listViewBtn"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50 transition">
                    <i class="fas fa-list"></i> List View
                </button>
            </div>

            <!-- In the filters section -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Search Bar -->
                <form method="get" action="/business/dashboard/menu" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" placeholder="Search items..."
                        value="<?= htmlspecialchars($searchTerm) ?>"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <?php if ($searchTerm): ?>
                        <a href="?<?= http_build_query(array_filter([
                            'category' => $selectedCategory,
                            'status' => $statusFilter
                        ])) ?>"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Preserve other filters in hidden fields -->
                    <?php if ($selectedCategory): ?>
                        <input type="hidden" name="category" value="<?= $selectedCategory ?>">
                    <?php endif; ?>
                    <?php if ($statusFilter && $statusFilter !== 'all'): ?>
                        <input type="hidden" name="status" value="<?= $statusFilter ?>">
                    <?php endif; ?>
                </form>

                <!-- Status Filter -->
                <select name="status" id="statusFilter"
                    class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="public" <?= $statusFilter === 'public' ? 'selected' : '' ?>>Public Only</option>
                    <option value="private" <?= $statusFilter === 'private' ? 'selected' : '' ?>>Private Only</option>
                </select>

                <!-- Category Filter -->
                <select name="category" id="categoryFilter"
                    class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['name']) ?>"
                            <?= strtolower($selectedCategory) === strtolower($category['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Clear All Filters Button -->
                <div class="flex justify-center sm:justify-end items-end">
                    <a href="/business/dashboard/menu"
                        class="px-8 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-300 text-center">
                        Clear
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($menuItems)): ?>
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                <i class="fas fa-utensils text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No menu items found</h3>
            <p class="text-gray-500 mb-6">Get started by adding your first menu item.</p>
            <button onclick="window.location.href='?add=true'"
                class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition-colors inline-block">
                <i class="fas fa-plus mr-2"></i> Add Item
            </button>
        </div>
    <?php else: ?>
        <!-- Item Card View -->
        <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($menuItems as $item): ?>
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow flex flex-col h-full group">
                    <!-- Item Image -->
                    <div class="h-48 bg-gray-200 relative overflow-hidden rounded-t-lg">
                        <?php if ($item['item_image']): ?>
                            <img src="<?= htmlspecialchars($item['item_image']) ?>"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                alt="<?= htmlspecialchars($item['name']) ?>">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-0 transition-all duration-300 flex items-center justify-center">
                                <span class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </span>
                            </div>
                        <?php else: ?>
                            <div
                                class="w-full h-full flex items-center justify-center text-gray-400 group-hover:text-gray-500 transition-colors">
                                <i class="fas fa-utensils text-4xl"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Availability Badge -->
                        <span class="absolute top-2 right-2 px-2 py-1 text-xs rounded-full 
                            <?= $item['available'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= $item['available'] ? 'Public' : 'Private' ?>
                        </span>
                    </div>

                    <div class="p-4 flex flex-col flex-grow gap-2">
                        <!-- Category and Price Row -->
                        <div class="flex justify-between items-center">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 italic">
                                <?= htmlspecialchars($item['category_name']) ?>
                            </span>
                            <span class="text-orange-500 font-bold whitespace-nowrap pl-2 text-xl">
                                Tk. <?= number_format($item['price'], 2) ?>
                            </span>
                        </div>

                        <!-- Item Name -->
                        <h3 class="font-semibold text-lg line-clamp-2">
                            <?= htmlspecialchars($item['name']) ?>
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm line-clamp-2 min-h-[2.75rem]">
                            <?= htmlspecialchars($item['description']) ?>
                        </p>

                        <!-- Tags -->
                        <?php if (!empty($item['tags'])): ?>
                            <div class="flex flex-wrap gap-1.5 max-h-[3rem] overflow-y-auto py-1">
                                <?php
                                $tags = array_filter(array_map('trim', explode(',', $item['tags'])));
                                $colorClasses = [
                                    'bg-purple-100 text-purple-800',
                                    'bg-pink-100 text-pink-800',
                                    'bg-green-100 text-green-800',
                                    'bg-yellow-100 text-yellow-800',
                                    'bg-indigo-100 text-indigo-800'
                                ];
                                foreach ($tags as $i => $tag):
                                    if (!empty($tag)):
                                        $colorClass = $colorClasses[$i % count($colorClasses)];
                                        ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>">
                                            <?= htmlspecialchars($tag) ?>
                                        </span>
                                        <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2 mt-auto">
                            <form method="post" action="/menu/toggle-availability/<?= $item['item_id'] ?>" class="flex-1">
                                <input type="hidden" name="available" value="<?= $item['available'] ? 0 : 1 ?>">
                                <button type="submit"
                                    class="w-full p-2 text-sm rounded-lg flex items-center justify-center gap-1
                                   <?= $item['available'] ? 'bg-gray-200 text-gray-700' : 'bg-green-500 text-white' ?>">
                                    <i class="fas <?= $item['available'] ? 'fa-eye-slash' : 'fa-eye' ?> text-xs"></i>
                                    <span class="text-xs"><?= $item['available'] ? 'Make Private' : 'Make Public' ?></span>
                                </button>
                            </form>

                            <div class="relative flex-1">
                                <button
                                    class="dropdown-toggle w-full py-2 bg-blue-500 text-white text-sm rounded-lg flex items-center justify-center gap-1">
                                    <i class="fas fa-ellipsis-h text-xs"></i>
                                    <span class="text-xs">Actions</span>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-1 w-40 bg-white rounded-md shadow-lg z-10 hidden">
                                    <button class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        onclick="window.location.href='?view=<?= $item['item_id'] ?>'">
                                        <i class="fas fa-eye mr-2 text-blue-500"></i> View
                                    </button>
                                    <button class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        onclick="window.location.href='?edit=<?= $item['item_id'] ?>'">
                                        <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                                    </button>
                                    <button class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        onclick="window.location.href='?delete=<?= $item['item_id'] ?>'">
                                        <i class="fas fa-trash mr-2 text-red-500"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Item List View -->
        <div id="listView" class="hidden bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($menuItems as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-14 w-14">
                                        <?php if ($item['item_image']): ?>
                                            <img class="h-14 w-14 rounded-md object-cover shadow-md"
                                                src="<?= htmlspecialchars($item['item_image']) ?>"
                                                alt="<?= htmlspecialchars($item['name']) ?>">
                                        <?php else: ?>
                                            <div
                                                class="w-full h-full flex items-center rounded-md shadow-md justify-center text-gray-400 group-hover:text-gray-500 transition-colors">
                                                <i class="fas fa-utensils text-xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-1">
                                            <?= htmlspecialchars($item['description']) ?>
                                        </div>

                                        <?php if (!empty($item['tags'])): ?>
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                <?php
                                                $tags = array_filter(array_map('trim', explode(',', $item['tags'])));
                                                $colorClasses = ['bg-purple-100 text-purple-800', 'bg-pink-100 text-pink-800', 'bg-green-100 text-green-800', 'bg-yellow-100 text-yellow-800', 'bg-indigo-100 text-indigo-800'];
                                                foreach ($tags as $i => $tag):
                                                    if (!empty($tag)):
                                                        $colorClass = $colorClasses[$i % count($colorClasses)];
                                                        ?>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?> hover:shadow-sm transition-all">
                                                            <?= htmlspecialchars($tag) ?>
                                                        </span>
                                                        <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Uncategorized') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-orange-500 font-medium">
                                    $<?= number_format($item['price'], 2) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="post" action="/menu/toggle-availability/<?= $item['item_id'] ?>">
                                    <input type="hidden" name="available" value="<?= $item['available'] ? 0 : 1 ?>">
                                    <button type="submit"
                                        class="px-2 py-1 text-xs rounded-full 
                                      <?= $item['available'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= $item['available'] ? 'Public' : 'Private' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="window.location.href='?view=<?= $item['item_id'] ?>'"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="window.location.href='?edit=<?= $item['item_id'] ?>'"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="window.location.href='?delete=<?= $item['item_id'] ?>'"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-3 mt-8">
                <!-- Previous Button -->
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">&laquo; Prev</a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-300 text-gray-400 rounded cursor-not-allowed">&laquo; Prev</span>
                <?php endif; ?>

                <!-- Page Info -->
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-800 border">
                    Page <?= $page ?> of <?= $totalPages ?>
                </span>

                <!-- Next Button -->
                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Next &raquo;</a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-300 text-gray-400 rounded cursor-not-allowed">Next &raquo;</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>


<!-- Add Item Modal -->
<?php if ($addItem): ?>
    <div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-bold">Add New Menu Item</h3>
                <button onclick="closeModal('add')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addItemForm" action="/business/dashboard/menu/add" method="POST" enctype="multipart/form-data"
                class="mt-4 space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Upload -->
                    <div class="col-span-2">
                        <label for="itemImage" class="block text-sm font-medium text-gray-700 mb-1">Item Image</label>
                        <div class="mt-1 flex flex-col gap-4 items-center">
                            <span class="inline-block h-64 w-full rounded-md overflow-hidden bg-gray-100">
                                <img id="itemImagePreview" src="" alt="Preview" class="h-full w-full object-cover hidden">
                                <div id="itemImagePlaceholder"
                                    class="h-full w-auto flex items-center justify-center text-gray-500">
                                    <i class="fas fa-utensils text-4xl"></i>
                                </div>
                            </span>
                            <input type="file" id="itemImage" name="image" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'itemImagePreview', 'itemImagePlaceholder')">
                            <button type="button" onclick="document.getElementById('itemImage').click()"
                                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Upload Image
                            </button>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="col-span-2">
                        <label for="itemName" class="block text-sm font-medium text-gray-700 mb-1">Item Name*</label>
                        <input type="text" id="itemName" name="name" required
                            value="<?= htmlspecialchars($data['formData']['name'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="itemCategory" class="block text-sm font-medium text-gray-700 mb-1">Category*</label>
                        <select id="itemCategory" name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>" <?= ($data['formData']['category_id'] ?? '') == $category['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="itemPrice" class="block text-sm font-medium text-gray-700 mb-1">Price*</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Tk</span>
                            </div>
                            <input type="number" id="itemPrice" name="price" min="0" step="0.01" required
                                value="<?= htmlspecialchars($data['formData']['price'] ?? '') ?>"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="available" value="1" <?= isset($data['formData']['available']) && $data['formData']['available'] ? 'checked' : 'checked' ?>
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-600">Available to customers</span>
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="itemDescription"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="itemDescription" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"><?= htmlspecialchars($data['formData']['description'] ?? '') ?></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="col-span-2">
                        <label for="itemTags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma
                            separated)</label>
                        <input type="text" id="itemTags" name="tags"
                            value="<?= htmlspecialchars($data['formData']['tags'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="e.g. spicy, vegan, gluten-free">
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t mt-6">
                    <button type="button" onclick="closeModal('add')"
                        class="mr-3 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- View Item Modal -->
<?php if ($viewItem): ?>
    <div id="viewItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-bold">Menu Item Details</h3>
                <button onclick="closeModal('view')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image -->
                    <div class="col-span-2">
                        <div class="h-64 w-full bg-gray-100 rounded-lg overflow-hidden">
                            <?php if ($viewItem['item_image']): ?>
                                <img src="<?= htmlspecialchars($viewItem['item_image']) ?>" alt="Item Image"
                                    class="h-full w-full object-cover">
                            <?php else: ?>
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-utensils text-5xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="col-span-2">
                        <h4 class="text-lg font-semibold"><?= htmlspecialchars($viewItem['name']) ?></h4>
                        <p class="text-gray-600 mt-1">
                            <?= htmlspecialchars($viewItem['description'] ?? 'No description available') ?>
                        </p>
                    </div>

                    <!-- Category and Price -->
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-medium"><?= htmlspecialchars($viewItem['category_name'] ?? 'Uncategorized') ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="font-medium text-orange-500">Tk. <?= number_format($viewItem['price'], 2) ?></p>
                    </div>

                    <!-- Availability -->
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span
                            class="px-2 py-1 text-xs rounded-full <?= $viewItem['available'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= $viewItem['available'] ? 'Public' : 'Private' ?>
                        </span>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($viewItem['tags'])): ?>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Tags</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <?php
                                $tags = array_filter(array_map('trim', explode(',', $viewItem['tags'])));
                                $colorClasses = ['bg-purple-100 text-purple-800', 'bg-pink-100 text-pink-800', 'bg-green-100 text-green-800'];
                                foreach ($tags as $i => $tag):
                                    if (!empty($tag)): ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $colorClasses[$i % count($colorClasses)] ?>">
                                            <?= htmlspecialchars($tag) ?>
                                        </span>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-end pt-4 border-t mt-6">
                    <button onclick="closeModal('view')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Edit Item Modal -->
<?php if ($editItem): ?>
    <div id="editItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-bold">Edit Menu Item</h3>
                <button onclick="closeModal('edit')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editItemForm" action="/business/dashboard/menu/edit/<?= $editItem['item_id'] ?>" method="POST"
                enctype="multipart/form-data" class="mt-4 space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Upload -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Image</label>
                        <div class="mt-1 flex flex-col gap-4 items-center">
                            <span class="inline-block h-64 w-full rounded-md overflow-hidden bg-gray-100">
                                <?php if ($editItem['item_image']): ?>
                                    <img id="editItemImagePreview" src="<?= htmlspecialchars($editItem['item_image']) ?>"
                                        alt="Preview" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <img id="editItemImagePreview" src="" alt="Preview"
                                        class="h-full w-full hidden object-cover">
                                    <div id="editItemImagePlaceholder"
                                        class="h-full w-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-utensils text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                            </span>
                            <input type="file" id="editItemImage" name="image" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'editItemImagePreview', 'editItemImagePlaceholder')">
                            <button type="button" onclick="document.getElementById('editItemImage').click()"
                                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Change Image
                            </button>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="col-span-2">
                        <label for="editItemName" class="block text-sm font-medium text-gray-700 mb-1">Item
                            Name*</label>
                        <input type="text" id="editItemName" name="name" required
                            value="<?= htmlspecialchars($editItem['name']) ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="editItemCategory" class="block text-sm font-medium text-gray-700 mb-1">Category*</label>
                        <select id="editItemCategory" name="category_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['category_id'] ?>"
                                    <?= $category['category_id'] == $editItem['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="editItemPrice" class="block text-sm font-medium text-gray-700 mb-1">Price*</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Tk</span>
                            </div>
                            <input type="number" id="editItemPrice" name="price" min="0" step="0.01" required
                                value="<?= htmlspecialchars($editItem['price']) ?>"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <div class="mt-1">
                            <label class="inline-flex items-center">
                                <input type="checkbox" id="editItemAvailable" name="available" value="1"
                                    <?= $editItem['available'] ? 'checked' : '' ?>
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-600">Available to customers</span>
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="editItemDescription"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="editItemDescription" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"><?= htmlspecialchars($editItem['description']) ?></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="col-span-2">
                        <label for="editItemTags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma
                            separated)</label>
                        <input type="text" id="editItemTags" name="tags" value="<?= htmlspecialchars($editItem['tags']) ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="e.g. spicy, vegan, gluten-free">
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t mt-6">
                    <button type="button" onclick="closeModal('edit')"
                        class="mr-3 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-orange-500 border border-transparent rounded-md text-sm font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<?php if ($deleteItem): ?>
    <div id="deleteItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-bold">Confirm Deletion</h3>
                <button onclick="closeModal('delete')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mt-4">
                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <p><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <p class="text-gray-700">Are you sure you want to delete this menu item? This action cannot be undone.
                </p>
                <p class="font-medium mt-2"><?= htmlspecialchars($deleteItem['name']) ?></p>

                <div class="flex justify-end space-x-3 pt-6 mt-6 border-t">
                    <button onclick="closeModal('delete')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        No, Cancel
                    </button>
                    <form method="POST" action="/business/dashboard/menu/delete/<?= $deleteItem['item_id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Yes, Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Modal Close Function  
    function closeModal(type) {
        const url = new URL(window.location);
        url.searchParams.delete(type);
        window.history.pushState({}, '', url);
        document.getElementById(`${type}ItemModal`).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Image Preview Function
    function previewImage(input, previewId, placeholderId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toast Message 
    setTimeout(() => {
        ['toast-success', 'toast-error'].forEach(id => {
            const toast = document.getElementById(id);
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        });
    }, 4000);

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const modalTypes = ['add', 'edit', 'view', 'delete'];
        if (modalTypes.some(type => urlParams.has(type))) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        const cardViewBtn = document.getElementById('cardViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const cardView = document.getElementById('cardView');
        const listView = document.getElementById('listView');

        if (cardViewBtn && listViewBtn && cardView && listView) {
            const defaultView = localStorage.getItem('menuItemView') || 'card';
            if (defaultView === 'list') {
                cardView.classList.add('hidden');
                listView.classList.remove('hidden');
            }

            cardViewBtn.addEventListener('click', function () {
                cardView.classList.remove('hidden');
                listView.classList.add('hidden');
                localStorage.setItem('menuItemView', 'card');
            });

            listViewBtn.addEventListener('click', function () {
                cardView.classList.add('hidden');
                listView.classList.remove('hidden');
                localStorage.setItem('menuItemView', 'list');
            });
        }

        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        if (dropdownToggles.length > 0) {
            dropdownToggles.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    if (menu) {
                        document.querySelectorAll('.dropdown-menu').forEach(m => {
                            if (m !== menu && m.classList) {
                                m.classList.add('hidden');
                            }
                        });
                        if (menu.classList) {
                            menu.classList.toggle('hidden');
                        }
                    }
                });
            });

            window.addEventListener('click', function () {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu.classList) {
                        menu.classList.add('hidden');
                    }
                });
            });
        }
    });

    // Event listeners for filter changes
    document.getElementById('statusFilter').addEventListener('change', function () {
        updateFilters();
    });

    document.getElementById('categoryFilter').addEventListener('change', function () {
        updateFilters();
    });

    function updateFilters() {
        const params = new URLSearchParams(window.location.search);

        // Update status filter
        const status = document.getElementById('statusFilter').value;
        if (status && status !== 'all') {
            params.set('status', status);
        } else {
            params.delete('status');
        }

        // Update category filter
        const category = document.getElementById('categoryFilter').value;
        if (category) {
            params.set('category', category);
        } else {
            params.delete('category');
        }

        // Reset to first page when filters change
        params.delete('page');

        window.location.search = params.toString();
    }

</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>