<?php
$pageTitle = "Kitchen Management";
$kitchens = $data['kitchens'];
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;

$statusClasses = [
    0 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
    1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
    2 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
    3 => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Suspended']
];

$statusCounts = [
    'all' => count($kitchens),
    '0' => 0,
    '1' => 0,
    '2' => 0,
    '3' => 0,
];

foreach ($kitchens as $kitchen) {
    if (isset($kitchen['is_approved']) && isset($statusCounts[$kitchen['is_approved']])) {
        $statusCounts[$kitchen['is_approved']]++;
    }
}

$helper = new App\Utils\Helper();
$csrfToken = $helper->generateCsrfToken();

$viewId = isset($_GET['view']) ? (int) $_GET['view'] : null;
$viewKitchen = $viewId
    ? current(array_filter($kitchens, fn($kitchen) => $kitchen['kitchen_id'] == $viewId))
    : null;

$suspendId = isset($_GET['suspend']) ? (int) $_GET['suspend'] : null;
$suspendKitchen = $suspendId
    ? current(array_filter($kitchens, fn($kitchen) => $kitchen['kitchen_id'] == $suspendId))
    : null;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 9;

// Pagination logic
$totalItems = count($kitchens);
$totalPages = ceil($totalItems / $perPage);
$page = max(1, min($page, $totalPages));
$offset = ($page - 1) * $perPage;
$kitchens = array_slice($kitchens, $offset, $perPage);

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
                <h1 class="text-2xl font-bold text-gray-800">Manage Kitchens</h1>
                <p class="text-gray-600 text-sm">Total kitchens: <?= count($kitchens) ?></p>
            </div>
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

            <!-- Search Bar & Filters -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                <!-- Search Bar -->
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input id="kitchenSearch" type="text" placeholder="Search kitchens..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" />
                </div>

                <!-- Status Filter -->
                <select id="statusFilter"
                    class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="all">All (<?= $statusCounts['all'] ?>)</option>
                    <option value="0">Pending (<?= $statusCounts['0'] ?>)</option>
                    <option value="1">Approved (<?= $statusCounts['1'] ?>)</option>
                    <option value="2">Rejected (<?= $statusCounts['2'] ?>)</option>
                    <option value="3">Suspended (<?= $statusCounts['3'] ?>)</option>
                </select>

            </div>
        </div>
    </div>

    <?php if (empty($kitchens)): ?>
        <div class="col-span-full text-center py-10">
            <i class="fas fa-utensils text-4xl text-gray-400 mb-3"></i>
            <p class="text-lg text-gray-500">No kitchens found</p>
            <p class="text-sm text-gray-400">All kitchens are approved or no new registrations</p>
        </div>
    <?php else: ?>
        <!-- Kitchen Cards View -->
        <div id="cardView" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($kitchens as $kitchen): ?>
                <?php
                $status = $kitchen['is_approved'] ?? 0;
                $statusInfo = $statusClasses[$status] ?? $statusClasses[0];
                ?>
                <div class="kitchen-card group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 hover:border-orange-100"
                    data-status="<?= $status ?>"
                    data-search="<?= strtolower(htmlspecialchars($kitchen['name'] . ' ' . $kitchen['address'] . ' ' . $kitchen['owner_name'])) ?>">

                    <!-- Kitchen Image -->
                    <div class="overflow-hidden rounded-t-lg h-48 bg-gray-200 relative">
                        <?php if (!empty($kitchen['kitchen_image'])): ?>
                            <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                                alt="<?= htmlspecialchars($kitchen['name'] ?? 'Kitchen Image') ?>"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fa-solid fa-kitchen-set text-6xl text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                            <h3 class="text-white font-semibold"><?= htmlspecialchars($kitchen['name']) ?></h3>
                            <p class="text-white/90 text-sm"><?= htmlspecialchars($kitchen['address']) ?></p>
                        </div>
                        <div
                            class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-medium <?= $statusInfo['class'] ?? 'bg-gray-100 text-gray-800' ?>">
                            <?= $statusInfo['text'] ?? 'Unknown' ?>
                        </div>
                    </div>

                    <!-- Kitchen Details -->
                    <div class="p-4">
                        <div class="flex flex-col items-start mb-3 min-h-[68px] w-full">
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                                <i class="fas fa-user w-4 h-4 flex items-center justify-center text-green-500"></i>
                                <?= htmlspecialchars($kitchen['owner_name']) ?>
                            </div>
                            <div class="flex items-center gap-2 text-sm mb-1">
                                <i class="fas fa-star text-yellow-400 w-4 h-4"></i>
                                <?php if ($status == 1 && isset($kitchen['avg_rating'])): ?>
                                    <span><?= round($kitchen['avg_rating'], 1) ?> (<?= $kitchen['review_count'] ?>
                                        reviews)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">No reviews yet</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <i class="fa-solid fa-person-biking w-4 h-4 text-orange-500"></i>
                                <?php if (!empty($kitchen['service_areas'])): ?>
                                    <span class="line-clamp-1"><?= htmlspecialchars($kitchen['service_areas']) ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">N/A</span>
                                <?php endif; ?>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <?php if ($status == 0): ?>
                                <form method="post" action="/admin/dashboard/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form method="post" action="/admin/dashboard/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            <?php else: ?>
                                <button onclick="window.location.href='?view=<?= $kitchen['kitchen_id'] ?>'"
                                    class="flex-1 w-full p-2 bg-blue-500 text-white text-xs rounded-lg flex items-center justify-center gap-1">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </button>

                                <div class="relative flex-1">
                                    <button id="dropdownActionButton_<?= $kitchen['kitchen_id'] ?>"
                                        class="w-full p-2 bg-gray-500 text-white text-xs rounded-lg flex items-center justify-center gap-1">
                                        <i class="fas fa-ellipsis-h"></i>
                                        <span>Actions</span>
                                    </button>

                                    <div id="dropdownAction_<?= $kitchen['kitchen_id'] ?>"
                                        class="z-10 hidden absolute top-full left-0 mt-1 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-40">
                                        <ul class="py-2 text-sm text-gray-700">
                                            <?php if ($status == 1): ?>
                                                <li>
                                                    <form method="post"
                                                        action="/admin/dashboard/kitchens/reject/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        onclick="window.location.href='?suspend=<?= $kitchen['kitchen_id'] ?>'"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-pause mr-2"></i> Suspend
                                                    </button>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <form method="post"
                                                        action="/admin/dashboard/kitchens/approve/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fas fa-check mr-2 text-green-500"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Kitchen List View -->
        <div id="listView" class="hidden bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitchen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($kitchens as $kitchen): ?>
                        <?php
                        $status = $kitchen['is_approved'] ?? 0;
                        $statusInfo = $statusClasses[$status] ?? $statusClasses[0];
                        ?>
                        <tr class="kitchen-row hover:bg-gray-50" data-status="<?= $kitchen['is_approved'] ?>"
                            data-search="<?= strtolower(htmlspecialchars($kitchen['name'] . ' ' . $kitchen['address'] . ' ' . $kitchen['owner_name'])) ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if (!empty($kitchen['kitchen_image'])): ?>
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                                                alt="<?= htmlspecialchars($kitchen['name']) ?>">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fa-solid fa-kitchen-set text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($kitchen['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($kitchen['address']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($kitchen['owner_name']) ?></div>
                                <div class="text-sm text-gray-500"><?= htmlspecialchars($kitchen['owner_email']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full <?= $statusInfo['class'] ?>">
                                    <?= $statusInfo['text'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($status == 1 && isset($kitchen['avg_rating'])): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span><?= round((float) $kitchen['avg_rating'], 1) ?>
                                            (<?= $kitchen['review_count'] ?? 0 ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="window.location.href='?view=<?= $kitchen['kitchen_id'] ?>'"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <?php if ($status == 0): ?>
                                    <form method="post" action="/admin/dashboard/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
                                        class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form method="post" action="/admin/dashboard/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
                                        class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="relative inline-block">
                                        <button id="listDropdownButton_<?= $kitchen['kitchen_id'] ?>"
                                            class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div id="listDropdown_<?= $kitchen['kitchen_id'] ?>"
                                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                            <div class="py-1">
                                                <?php if ($status == 1): ?>
                                                    <form method="post"
                                                        action="/admin/dashboard/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
                                                        class="block">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                    <button onclick="window.location.href='?suspend=<?= $kitchen['kitchen_id'] ?>'"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-pause mr-2"></i> Suspend
                                                    </button>
                                                <?php else: ?>
                                                    <form method="post"
                                                        action="/admin/dashboard/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
                                                        class="block">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-check mr-2"></i> Approve
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-3 mt-10">
                <?php if ($page > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">&laquo; Prev</a>
                <?php endif; ?>

                <span class="px-4 py-2 bg-gray-200 rounded text-gray-700">
                    Page <?= $page ?> of <?= $totalPages ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <!-- View Kitchen Modal -->
    <?php if ($viewKitchen): ?>
        <?php
        $status = $viewKitchen['is_approved'] ?? 0;
        $statusInfo = $statusClasses[$status] ?? $statusClasses[0];
        ?>
        <div id="viewKitchenModal"
            class="fixed inset-0 !mt-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div
                class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in p-6">

                <!-- Modal Header -->
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        <?= htmlspecialchars($viewKitchen['name'] ?? 'Kitchen Details') ?>
                    </h3>
                    <button onclick="closeModal('view')"
                        class="text-gray-500 hover:text-gray-700 bg-gray-300 p-1 rounded-md h-8 w-8 absolute right-2 top-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Left Column -->
                    <div>
                        <!-- Kitchen Image -->
                        <div class="h-48 bg-gray-200 rounded-lg mb-4">
                            <?php if (!empty($viewKitchen['kitchen_image'])): ?>
                                <img src="<?= htmlspecialchars($viewKitchen['kitchen_image']) ?>"
                                    alt="<?= htmlspecialchars($viewKitchen['name'] ?? 'Kitchen Image') ?>"
                                    class="w-full h-full object-cover rounded-lg">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                                    <i class="fas fa-utensils text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Owner Info -->
                        <div class="space-y-2">
                            <h4 class="font-semibold text-gray-700">Owner Information</h4>
                            <p class="text-gray-600">
                                <i class="fa-solid fa-user mr-1"></i>
                                <?= htmlspecialchars($viewKitchen['owner_name'] ?? 'Unknown') ?>
                            </p>
                            <p class="text-gray-600">
                                <i class="fa-solid fa-envelope mr-1"></i>
                                <?= htmlspecialchars($viewKitchen['owner_email'] ?? 'No email') ?>
                            </p>
                            <p class="text-gray-600">
                                <i class="fa-solid fa-phone mr-1"></i>
                                <?= htmlspecialchars($viewKitchen['owner_phone'] ?? 'No phone') ?>
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Address -->
                        <div>
                            <h4 class="font-semibold text-gray-700">Address</h4>
                            <p class="text-gray-600">
                                <i class="fa-solid fa-location-dot mr-1"></i>
                                <?= htmlspecialchars($viewKitchen['address'] ?? 'No address') ?>
                            </p>
                        </div>

                        <!-- Service Areas -->
                        <?php if (!empty($viewKitchen['service_areas'])): ?>
                            <div>
                                <h4 class="font-semibold text-gray-700">Service Areas</h4>
                                <p class="text-gray-600">
                                    <i class="fa-solid fa-person-biking mr-1"></i>
                                    <?= htmlspecialchars($viewKitchen['service_areas']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Description -->
                        <?php if (!empty($viewKitchen['description'])): ?>
                            <div>
                                <h4 class="font-semibold text-gray-700">Description</h4>
                                <p class="text-gray-600">
                                    <i class="fa-solid fa-align-left mr-1"></i>
                                    <?= htmlspecialchars($viewKitchen['description']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Status -->
                        <div>
                            <h4 class="font-semibold text-gray-700">Status</h4>
                            <span class="px-2 py-1 text-xs rounded-full <?= $statusInfo['class'] ?>">
                                <?= $statusInfo['text'] ?>
                            </span>
                        </div>

                        <!-- Join Date -->
                        <div>
                            <h4 class="font-semibold text-gray-700">Join Since</h4>
                            <p class="text-gray-600">
                                <i class="fa-solid fa-calendar-plus mr-1"></i>
                                <?php if (!empty($viewKitchen['created_at'])):
                                    $date = new DateTime($viewKitchen['created_at']); ?>
                                    <?= htmlspecialchars($date->format('F j, Y')) ?>
                                <?php else: ?>
                                    Unknown date
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Rating -->
                        <?php if ($status == 1 && isset($viewKitchen['avg_rating'])): ?>
                            <div>
                                <h4 class="font-semibold text-gray-700">Rating</h4>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span><?= round((float) $viewKitchen['avg_rating'], 1) ?></span>
                                    <span class="text-gray-500 ml-2">
                                        (<?= $viewKitchen['review_count'] ?? 0 ?> reviews)
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Suspend Modal -->
    <?php if ($suspendKitchen): ?>
        <div id="suspendKitchenModal" class="fixed !mt-0 inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Suspend Kitchen</h2>
                <p class="text-gray-600 mb-4">You are about to suspend:
                    <strong><?= htmlspecialchars($suspendKitchen['name'] ?? 'this kitchen') ?></strong>
                </p>

                <form id="suspendForm" method="post"
                    action="/admin/dashboard/kitchens/suspend/<?= $suspendKitchen['kitchen_id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                    <div class="mb-4">
                        <label for="reason" class="block mb-2 font-medium text-sm">Reason for suspension</label>
                        <textarea name="reason" id="reason" rows="3" required
                            class="w-full border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300"
                            placeholder="Enter the reason for suspension..."></textarea>
                    </div>

                    <label for="duration" class="block mb-2 font-medium text-sm">Select Duration</label>
                    <select name="duration" id="duration"
                        class="w-full border rounded-md p-2 mb-4 focus:outline-none focus:ring focus:border-blue-300">
                        <option value="24">24 hours</option>
                        <option value="48">48 hours</option>
                        <option value="72">72 hours</option>
                        <option value="168">1 week</option>
                        <option value="custom">Custom (days)</option>
                    </select>

                    <div id="customDaysContainer" class="hidden mb-4">
                        <input type="number" name="custom_days" min="1" max="365" placeholder="Enter number of days"
                            class="w-full border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal('suspend')"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Suspend</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Model Functions 
    function closeModal(type) {
        const url = new URL(window.location);
        url.searchParams.delete(type);
        window.history.pushState({}, '', url);
        document.getElementById(`${type}KitchenModal`).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Filter kitchens
    function filterKitchens(status) {
        const cards = document.querySelectorAll('.kitchen-card');
        const rows = document.querySelectorAll('.kitchen-row');

        cards.forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // Search kitchens
    function searchKitchens(query) {
        const cards = document.querySelectorAll('.kitchen-card');
        const rows = document.querySelectorAll('.kitchen-row');

        cards.forEach(card => {
            const searchText = card.dataset.search;
            if (searchText.includes(query)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        rows.forEach(row => {
            const searchText = row.dataset.search;
            if (searchText.includes(query)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // Combine filter and search
    function updateKitchenDisplay() {
        const currentFilter = document.getElementById('statusFilter').value;
        const currentSearch = document.getElementById('kitchenSearch').value.toLowerCase();

        filterKitchens(currentFilter);
        if (currentSearch) searchKitchens(currentSearch);
    }

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
        // View toggle
        const cardViewBtn = document.getElementById('cardViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const cardView = document.getElementById('cardView');
        const listView = document.getElementById('listView');

        // default view
        const defaultView = localStorage.getItem('kitchenView') || 'card';
        if (defaultView === 'list') {
            cardView.classList.add('hidden');
            listView.classList.remove('hidden');
            cardViewBtn.classList.remove('bg-gray-200');
            listViewBtn.classList.add('bg-gray-200');
        } else {
            cardView.classList.remove('hidden');
            listView.classList.add('hidden');
            cardViewBtn.classList.add('bg-gray-200');
            listViewBtn.classList.remove('bg-gray-200');
        }

        cardViewBtn.addEventListener('click', function () {
            cardView.classList.remove('hidden');
            listView.classList.add('hidden');
            cardViewBtn.classList.add('bg-gray-200');
            listViewBtn.classList.remove('bg-gray-200');
            localStorage.setItem('kitchenView', 'card');
        });

        listViewBtn.addEventListener('click', function () {
            cardView.classList.add('hidden');
            listView.classList.remove('hidden');
            cardViewBtn.classList.remove('bg-gray-200');
            listViewBtn.classList.add('bg-gray-200');
            localStorage.setItem('kitchenView', 'list');
        });

        // Filter status
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function () {
            filterKitchens(this.value);
        });

        // Search function
        const searchInput = document.getElementById('kitchenSearch');
        searchInput.addEventListener('input', function () {
            searchKitchens(this.value.toLowerCase());
        });

        // Dropdown for CardView
        document.querySelectorAll('[id^="dropdownActionButton_"]').forEach(button => {
            const id = button.id.replace("dropdownActionButton_", "");
            const dropdown = document.getElementById(`dropdownAction_${id}`);

            button.addEventListener("click", function (e) {
                e.stopPropagation();
                document.querySelectorAll('[id^="dropdownAction_"]').forEach(d => {
                    if (d !== dropdown) d.classList.add("hidden");
                });
                dropdown.classList.toggle("hidden");
            });
        });

        // Dropdown for ListView
        document.querySelectorAll('[id^="listDropdownButton_"]').forEach(button => {
            const id = button.id.replace("listDropdownButton_", "");
            const dropdown = document.getElementById(`listDropdown_${id}`);

            button.addEventListener("click", function (e) {
                e.stopPropagation();
                document.querySelectorAll('[id^="listDropdown_"]').forEach(d => {
                    if (d !== dropdown) d.classList.add("hidden");
                });
                dropdown.classList.toggle("hidden");
            });
        });

        // Close dropdowns
        window.addEventListener("click", () => {
            document.querySelectorAll('[id^="dropdownAction_"]').forEach(d => d.classList.add("hidden"));
            document.querySelectorAll('[id^="listDropdown_"]').forEach(d => d.classList.add("hidden"));
        });

        // Suspend modal
        const durationSelect = document.getElementById('duration');
        const customContainer = document.getElementById('customDaysContainer');

        // durationSelect.addEventListener('change', function () {
        //     customContainer.classList.toggle('hidden', this.value !== 'custom');
        // });
    });
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>