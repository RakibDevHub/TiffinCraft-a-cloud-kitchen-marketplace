<?php
$pageTitle = "Manage Categories";
$categories = $data['categories'] ?? [];
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;
ob_start();

$helper = new App\Utils\Helper();
$csrfToken = $helper->generateCsrfToken();

$addCategory = isset($_GET['add']) ? true : false;

$viewId = isset($_GET['view']) ? (int) $_GET['view'] : null;
$viewCategory = $viewId
    ? current(array_filter($categories, fn($category) => $category['category_id'] == $viewId))
    : null;

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$editCategory = $editId
    ? current(array_filter($categories, fn($category) => $category['category_id'] == $editId))
    : null;

$deleteId = isset($_GET['delete']) ? (int) $_GET['delete'] : null;
$deleteCategory = $deleteId
    ? current(array_filter($categories, fn($category) => $category['category_id'] == $deleteId))
    : null;

?>

<!-- Flash Messages -->
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
    <!-- Header  -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Categories Management</h1>
        <button onclick="window.location.href='?add=true'"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add New Category
        </button>
    </div>

    <!-- Categories Table -->
    <?php if (!empty($categories)): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($category['category_id']) ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if (!empty($category['image'])): ?>
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="<?= htmlspecialchars($category['image']) ?>" alt="">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>


                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate">
                                    <?= htmlspecialchars($category['description'] ?? 'No description') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="window.location.href='?view=<?= $category['category_id'] ?>'"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button onclick="window.location.href='?edit=<?= $category['category_id'] ?>'"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="window.location.href='?delete=<?= $category['category_id'] ?>'"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                <i class="fas fa-tags text-5xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No categories found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your first category.</p>
            <button onclick="window.location.href='?add=true'"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors inline-block">
                <i class="fas fa-plus mr-2"></i> Add Category
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- View Category Modal -->
<?php if ($viewCategory): ?>
    <div id="viewCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-xl font-bold">Category Details</h3>
                <button onclick="closeModal('view')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image -->
                    <div class="col-span-2">
                        <div class="h-64 w-full bg-gray-100 rounded-lg overflow-hidden">
                            <?php if ($viewCategory['image']): ?>
                                <img src="<?= htmlspecialchars($viewCategory['image']) ?>" alt="Category Image"
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
                        <h4 class="text-lg font-semibold"><?= htmlspecialchars($viewCategory['name']) ?></h4>
                        <p class="text-gray-600 mt-1">
                            <?= htmlspecialchars($viewCategory['description'] ?? 'No description available') ?>
                        </p>
                    </div>

                    <!-- <div class="col-span-2 flex justify-end pt-4 border-t mt-6">
                        <button onclick="closeModal('view')"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                            Close
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Add Category Modal -->
    <?php if ($addCategory): ?>
        <div id="addCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Category</h3>
                    <button onclick="closeModal('add')" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="addCategoryForm" action="/admin/dashboard/categories/add" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <?php if ($error): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <p><?= htmlspecialchars($error) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="categoryImage" class="block text-sm font-medium text-gray-700 mb-1">Category
                            Image</label>
                        <div class="mt-1 flex flex-col gap-4 items-center">
                            <span class="inline-block h-64 w-full rounded-md overflow-hidden bg-gray-100">
                                <img id="categoryImagePreview" src="" alt="Preview"
                                    class="h-full w-full object-cover hidden">
                                <div id="categoryImagePlaceholder"
                                    class="h-full w-auto flex items-center justify-center text-gray-300">
                                    <i class="fas fa-utensils text-4xl"></i>
                                </div>
                            </span>
                            <input type="file" id="categoryImage" name="image" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'categoryImagePreview', 'categoryImagePlaceholder')">
                            <button type="button" onclick="document.getElementById('categoryImage').click()"
                                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Upload Image
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('add')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Edit Category Modal -->
    <?php if ($editCategory): ?>
        <div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Category</h3>
                    <button onclick="closeModal('edit')" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editCategoryForm" action="/admin/dashboard/categories/edit/<?= $editCategory['category_id'] ?>"
                    method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <?php if ($error): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <p><?= htmlspecialchars($error) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="editCategoryImage" class="block text-sm font-medium text-gray-700 mb-1">Category
                            Image</label>
                        <div class="mt-1 flex flex-col gap-4 items-center">
                            <span class="inline-block h-64 w-full rounded-md overflow-hidden bg-gray-100">
                                <?php if ($editCategory['image']): ?>
                                    <img id="editCategoryImagePreview" src="<?= htmlspecialchars($editCategory['image']) ?>"
                                        alt="Preview" class="h-full w-full object-cover hidden">
                                <?php else: ?>
                                    <img id="editCategoryImagePreview" src="" alt="Preview"
                                        class="h-full w-full hidden object-cover">
                                    <div id="editCategoryImagePlaceholder"
                                        class="h-full w-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-utensils text-4xl"></i>
                                    </div>
                                <?php endif; ?>
                            </span>
                            <input type="file" id="editCategoryImage" name="image" accept="image/*" class="hidden"
                                onchange="previewImage(this, 'editCategoryImagePreview', 'editCategoryImagePlaceholder')">
                            <button type="button" onclick="document.getElementById('editCategoryImage').click()"
                                class="px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Change Image
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" id="edit_name" name="name" required
                            value="<?= htmlspecialchars($editCategory['name']) ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="edit_description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit_description" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($editCategory['description']) ?></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('edit')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Delete Confirmation Modal -->
    <?php if ($deleteCategory): ?>
        <div id="deleteCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
                <div class="flex justify-between items-center border-b pb-4">
                    <h3 class="text-xl font-bold">Confirm Deletion</h3>
                    <button onclick="closeModal('delete')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mt-4">
                    <p class="text-gray-700">Are you sure you want to delete this category? This action cannot be undone.
                    </p>
                    <p class="font-medium mt-2"><?= htmlspecialchars($deleteCategory['name']) ?></p>

                    <div class="flex justify-end space-x-3 pt-6 mt-6 border-t">
                        <button onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            No, Cancel
                        </button>
                        <form method="POST" action="/admin/dashboard/category/delete/<?= $deleteCategory['category_id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Yes, Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Modal Close Functions
        function closeModal(type) {
            const url = new URL(window.location);
            url.searchParams.delete(type);
            window.history.pushState({}, '', url);
            document.getElementById(`${type}CategoryModal`).classList.add('hidden');
            document.body.style.overflow = '';
        }

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

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const modalTypes = ['view', 'add', 'edit', 'delete'];
            if (modalTypes.some(type => urlParams.has(type))) {
                window.scrollTo({ top: 0, behavior: 'smooth' })
            }
        })

    </script>

    <?php
    $content = ob_get_clean();
    include BASE_PATH . '/src/views/dashboard.php';