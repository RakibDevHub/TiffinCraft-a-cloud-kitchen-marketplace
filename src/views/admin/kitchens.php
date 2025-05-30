<?php
$pageTitle = "Kitchen Management";
$kitchens = $data['kitchens'];
$error = $data['error'];
ob_start();

$statusClasses = [
    0 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
    1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
    2 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected'],
    3 => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Disabled'],
    4 => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Suspended']
];

// Count kitchens by status
$statusCounts = [
    'all' => count($kitchens),
    '0' => count(array_filter($kitchens, fn($k) => $k['is_approved'] == 0)),
    '1' => count(array_filter($kitchens, fn($k) => $k['is_approved'] == 1)),
    '2' => count(array_filter($kitchens, fn($k) => $k['is_approved'] == 2)),
    '3' => count(array_filter($kitchens, fn($k) => $k['is_approved'] == 3)),
    '4' => count(array_filter($kitchens, fn($k) => $k['is_approved'] == 4))
];
?>

<!-- Main Content -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kitchen Approvals</h1>
        <div class="flex space-x-3 items-center">
            <!-- View Toggle Buttons -->
            <div class="flex space-x-1">
                <button id="cardViewBtn" class="px-3 py-[0.35rem] text-gray-700 border rounded-l-lg hover:bg-gray-200">
                    <i class="fas fa-th-large "></i>
                </button>
                <button id="listViewBtn" class="px-3 py-[0.35rem] text-gray-700 border rounded-r-lg hover:bg-gray-200">
                    <i class="fas fa-list "></i>
                </button>
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select id="statusFilter"
                    class="appearance-none pl-3 pr-8 py-2 border rounded-lg text-sm w-48 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="all">All (<?= $statusCounts['all'] ?>)</option>
                    <option value="0">Pending (<?= $statusCounts['0'] ?>)</option>
                    <option value="1">Approved (<?= $statusCounts['1'] ?>)</option>
                    <option value="2">Rejected (<?= $statusCounts['2'] ?>)</option>
                    <option value="3">Disabled (<?= $statusCounts['3'] ?>)</option>
                    <option value="4">Suspended (<?= $statusCounts['4'] ?>)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Search Box -->
            <div class="relative">
                <input type="text" placeholder="Search kitchens..." id="kitchenSearch"
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm w-64 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <p class="font-semibold">Error</p>
                    <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Kitchen Cards View -->
    <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($kitchens)): ?>
            <div class="col-span-full text-center py-10">
                <i class="fas fa-utensils text-4xl text-gray-400 mb-3"></i>
                <p class="text-lg text-gray-500">No kitchens found</p>
                <p class="text-sm text-gray-400">All kitchens are approved or no new registrations</p>
            </div>
        <?php else: ?>
            <?php foreach ($kitchens as $kitchen): ?>
                <div class="kitchen-card bg-white rounded-lg shadow hover:shadow-md transition-shadow"
                    data-status="<?= $kitchen['is_approved'] ?>"
                    data-search="<?= strtolower(htmlspecialchars($kitchen['name'] . ' ' . $kitchen['address'] . ' ' . $kitchen['owner_name'])) ?>">

                    <!-- Kitchen Image -->
                    <div class="overflow-hidden rounded-t-lg h-48 bg-gray-200 relative">
                        <?php if (!empty($kitchen['kitchen_image'])): ?>
                            <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fas fa-utensils text-4xl text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                            <h3 class="text-white font-semibold"><?= htmlspecialchars($kitchen['name']) ?></h3>
                            <p class="text-white/90 text-sm"><?= htmlspecialchars($kitchen['address']) ?></p>
                        </div>
                    </div>

                    <!-- Kitchen Details -->
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="flex items-center text-sm text-gray-500 mb-1">
                                    <i class="fas fa-user mr-1"></i>
                                    <?= htmlspecialchars($kitchen['owner_name']) ?>
                                </div>
                                <div class="flex items-center text-sm mb-1">
                                    <?php if ($kitchen['is_approved'] == 1 && isset($kitchen['avg_rating'])): ?>
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span><?= round($kitchen['avg_rating'], 1) ?> (<?= $kitchen['review_count'] ?>
                                            reviews)</span>
                                    <?php else: ?>
                                        <span class="text-gray-400">No reviews yet</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($kitchen['service_areas'])): ?>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        <span><?= htmlspecialchars($kitchen['service_areas']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span
                                class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$kitchen['is_approved']]['class'] ?>">
                                <?= $statusClasses[$kitchen['is_approved']]['text'] ?>
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <?php if ($kitchen['is_approved'] == 0): ?>
                                <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>" class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>" class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                            <?php else: ?>
                                <button onclick="openKitchenModal('<?= $kitchen['kitchen_id'] ?>')"
                                    class="flex-1 bg-blue-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>

                                <div class="relative flex-1">
                                    <button id="dropdownActionButton_<?= $kitchen['kitchen_id'] ?>"
                                        class="w-full bg-gray-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">
                                        <i class="fas fa-ellipsis-h mr-1"></i> Actions
                                    </button>

                                    <div id="dropdownAction_<?= $kitchen['kitchen_id'] ?>"
                                        class="z-10 hidden absolute top-full left-0 mt-1 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-40">
                                        <ul class="py-2 text-sm text-gray-700">
                                            <?php if ($kitchen['is_approved'] == 1): ?>
                                                <li>
                                                    <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" onclick="openSuspendModal('<?= $kitchen['kitchen_id'] ?>')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-pause mr-2"></i> Suspend
                                                    </button>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>">
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
        <?php endif; ?>
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
                <?php if (empty($kitchens)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No kitchens found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($kitchens as $kitchen): ?>
                        <tr class="kitchen-row hover:bg-gray-50" data-status="<?= $kitchen['is_approved'] ?>"
                            data-search="<?= strtolower(htmlspecialchars($kitchen['name'] . ' ' . $kitchen['address'] . ' ' . $kitchen['owner_name'])) ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if (!empty($kitchen['kitchen_image'])): ?>
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>" alt="">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-400"></i>
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
                                <span
                                    class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$kitchen['is_approved']]['class'] ?>">
                                    <?= $statusClasses[$kitchen['is_approved']]['text'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($kitchen['is_approved'] == 1 && isset($kitchen['avg_rating'])): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span><?= round($kitchen['avg_rating'], 1) ?> (<?= $kitchen['review_count'] ?>)</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openKitchenModal('<?= $kitchen['kitchen_id'] ?>')"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <?php if ($kitchen['is_approved'] == 0): ?>
                                    <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
                                        class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
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
                                                <?php if ($kitchen['is_approved'] == 1): ?>
                                                    <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
                                                        class="block">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                    <button onclick="openSuspendModal('<?= $kitchen['kitchen_id'] ?>')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-pause mr-2"></i> Suspend
                                                    </button>
                                                <?php else: ?>
                                                    <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Kitchen Modal -->
    <div id="kitchenModal"
        class="fixed !mt-0 inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div
            class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 id="modalKitchenName" class="text-xl font-bold">Kitchen Name</h3>
                <button onclick="closeModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors bg-gray-300 p-0.5 rounded-md h-8 w-8 absolute right-2 top-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <div class="h-48 bg-gray-200 rounded-lg mb-4" id="modalKitchenImageWrapper">
                        <img id="modalKitchenImage" src="" alt="Kitchen Image"
                            class="w-full h-full object-cover rounded-lg hidden">
                        <div id="modalKitchenFallbackImage"
                            class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                            <i class="fas fa-utensils text-4xl text-gray-400"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <h4 class="font-semibold text-gray-700">Owner Information</h4>
                            <p class="text-gray-600">Name: <span id="modalOwnerName"></span></p>
                            <p class="text-gray-600">Email: <span id="modalOwnerEmail"></span></p>
                            <p class="text-gray-600">Phone: <span id="modalOwnerPhone"></span></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Address</h4>
                            <p class="text-gray-600" id="modalAddress"></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700">Service Areas</h4>
                            <p class="text-gray-600" id="modalServiceAreas"></p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Description</h4>
                        <p class="text-gray-600" id="modalDescription"></p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Status</h4>
                        <span id="modalStatusBadge" class="px-2 py-1 text-xs rounded-full"></span>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Registration Date</h4>
                        <p class="text-gray-600" id="modalCreatedAt"></p>
                    </div>

                    <div class="mb-4 hidden" id="modalRatingWrapper">
                        <h4 class="font-semibold text-gray-700">Rating</h4>
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span id="modalRating"></span>
                            <span class="text-gray-500 ml-1" id="modalReviewCount"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspendModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Suspend Kitchen</h2>
            <form id="suspendForm" method="post" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="kitchen_id" id="modalKitchenId">

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
                    <input type="number" name="custom_days" min="1" placeholder="Enter number of days"
                        class="w-full border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeSuspendModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Suspend</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Section -->
<script>
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

        // Close modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });

        // Suspend modal
        const durationSelect = document.getElementById('duration');
        const customContainer = document.getElementById('customDaysContainer');

        durationSelect.addEventListener('change', function () {
            customContainer.classList.toggle('hidden', this.value !== 'custom');
        });
    });

    // Kitchen Modal
    function openKitchenModal(kitchenId) {
        const kitchen = window.kitchensData[kitchenId];
        if (!kitchen) return console.error("Kitchen not found:", kitchenId);

        // Basic information
        document.getElementById('modalKitchenName').textContent = kitchen.name || 'Unnamed Kitchen';
        document.getElementById('modalOwnerName').textContent = kitchen.owner_name || 'Not available';
        document.getElementById('modalOwnerEmail').textContent = kitchen.owner_email || 'Not available';
        document.getElementById('modalOwnerPhone').textContent = kitchen.owner_phone || 'Not available';
        document.getElementById('modalAddress').textContent = kitchen.address || 'Not available';
        document.getElementById('modalServiceAreas').textContent = kitchen.service_areas || 'Not specified';
        document.getElementById('modalDescription').textContent = kitchen.description || 'No description provided';

        // Image handling
        const imgEl = document.getElementById('modalKitchenImage');
        const fallbackEl = document.getElementById('modalKitchenFallbackImage');
        if (kitchen.kitchen_image) {
            imgEl.src = kitchen.kitchen_image;
            imgEl.classList.remove('hidden');
            fallbackEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            fallbackEl.classList.remove('hidden');
        }

        // Status badge
        const statusMap = {
            0: { text: 'Pending', class: 'bg-yellow-100 text-yellow-800' },
            1: { text: 'Approved', class: 'bg-green-100 text-green-800' },
            2: { text: 'Rejected', class: 'bg-red-100 text-red-800' },
            3: { text: 'Disabled', class: 'bg-gray-100 text-gray-800' },
            4: { text: 'Suspended', class: 'bg-gray-100 text-gray-800' },
        };
        const badge = document.getElementById('modalStatusBadge');
        const status = statusMap[kitchen.is_approved] || { text: 'Unknown', class: 'bg-gray-100 text-gray-800' };
        badge.textContent = status.text;
        badge.className = `px-2 py-1 text-xs rounded-full ${status.class}`;

        // Date formatting
        try {
            const date = new Date(kitchen.created_at);
            document.getElementById('modalCreatedAt').textContent = date.toLocaleString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        } catch (e) {
            document.getElementById('modalCreatedAt').textContent = 'Unknown date';
        }

        // Rating display
        const ratingWrapper = document.getElementById('modalRatingWrapper');
        const avg = parseFloat(kitchen.avg_rating);
        if (kitchen.is_approved == 1 && !isNaN(avg)) {
            document.getElementById('modalRating').textContent = avg.toFixed(1);
            document.getElementById('modalReviewCount').textContent = `(${kitchen.review_count || 0} reviews)`;
            ratingWrapper.classList.remove('hidden');
        } else {
            ratingWrapper.classList.add('hidden');
        }

        // Show modal
        document.getElementById('kitchenModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('kitchenModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Suspend Modal
    function openSuspendModal(kitchenId) {
        document.getElementById('modalKitchenId').value = kitchenId;
        document.getElementById('suspendForm').action = `/admin/kitchens/suspend/${kitchenId}`;
        document.getElementById('suspendModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
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

    // Kitchens data
    window.kitchensData = {
        <?php foreach ($kitchens as $kitchen): ?>'<?= $kitchen['kitchen_id'] ?>': {
                name: '<?= addslashes(htmlspecialchars($kitchen['name'])) ?>',
                owner_name: '<?= addslashes(htmlspecialchars($kitchen['owner_name'])) ?>',
                owner_email: '<?= addslashes(htmlspecialchars($kitchen['owner_email'])) ?>',
                owner_phone: '<?= addslashes(htmlspecialchars($kitchen['owner_phone'])) ?>',
                address: '<?= addslashes(htmlspecialchars($kitchen['address'])) ?>',
                service_areas: '<?= addslashes(htmlspecialchars($kitchen['service_areas'])) ?>',
                description: '<?= addslashes(htmlspecialchars($kitchen['description'])) ?>',
                kitchen_image: '<?= addslashes(htmlspecialchars($kitchen['kitchen_image'] ?? '')) ?>',
                is_approved: <?= $kitchen['is_approved'] ?>,
                created_at: '<?= $kitchen['created_at'] ?>',
                avg_rating: <?= $kitchen['avg_rating'] ?? 'null' ?>,
                review_count: <?= $kitchen['review_count'] ?? 0 ?>
            },
        <?php endforeach; ?>
    };
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>