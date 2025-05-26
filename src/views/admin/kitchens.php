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

function getUserStatus()
{

}
?>

<!-- Main Content -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kitchen Approvals</h1>
        <div class="flex space-x-3">
            <div class="flex space-x-2">
                <button id="filterAll"
                    class="px-3 py-2 border rounded-lg text-sm font-medium bg-white hover:bg-gray-50">
                    All <span id="allCount" class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs ml-1">
                        <?= count($kitchens) ?>
                    </span>
                </button>
                <button id="filterPending"
                    class="px-3 py-2 border rounded-lg text-sm font-medium bg-orange-100 text-orange-700 hover:bg-orange-200">
                    Pending <span id="pendingCount"
                        class="bg-orange-200 text-orange-800 px-2 py-1 rounded-full text-xs ml-1">
                        <?= count(array_filter($kitchens, fn($k) => $k['is_approved'] == 0)) ?>
                    </span>
                </button>
            </div>
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

    <!-- Kitchen Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="kitchenGrid">
        <?php if (empty($kitchens)): ?>
            <div class="col-span-full text-center py-10">
                <i class="fas fa-utensils text-4xl text-gray-400 mb-3"></i>
                <p class="text-lg text-gray-500">No kitchens found</p>
                <p class="text-sm text-gray-400">All kitchens are approved or no new registrations</p>
            </div>
        <?php else: ?>
            <?php foreach ($kitchens as $kitchen): ?>
                <!-- overflow-hidden -->
                <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow"
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
                                        class="w-full bg-blue-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Action
                                    </button>

                                    <div id="dropdownAction_<?= $kitchen['kitchen_id'] ?>"
                                        class="z-10 hidden absolute top-0 left-full ml-2 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700">

                                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">

                                            <?php if ($kitchen['is_approved'] == 1): ?>
                                                <li>
                                                    <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-times mr-2"></i> Reject
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" onclick="openSuspendModal('<?= $kitchen['kitchen_id'] ?>')"
                                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                        <i class="fas fa-pause mr-2"></i> Suspend
                                                    </button>
                                                </li>
                                                <!-- Add more options like Disable here if needed -->
                                            <?php else: ?>
                                                <li>
                                                    <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-check mr-2"></i> Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="post" action="/admin/kitchens/suspend/<?= $kitchen['kitchen_id'] ?>">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                            <i class="fas fa-pause mr-2"></i> Suspend
                                                        </button>
                                                    </form>
                                                </li>
                                                <!-- Add more options like Disable here if needed -->
                                            <?php endif; ?>

                                        </ul>
                                    </div>
                                </div>


                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

                        <!-- Modal Action Buttons -->
                        <div class="flex space-x-2 mt-4">
                            <?php if ($kitchen['is_approved'] == 1): ?>
                                <form method="post" action="/admin/kitchens/reject/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-red-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </form>
                                <form method="post" action="/admin/kitchens/suspend/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-gray-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">
                                        <i class="fas fa-pause mr-1"></i> Suspend
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="/admin/kitchens/approve/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form method="post" action="/admin/kitchens/suspend/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <button type="submit"
                                        class="w-full bg-gray-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">
                                        <i class="fas fa-pause mr-1"></i> Suspend
                                    </button>
                                </form>
                            <?php endif; ?>
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
            const searchInput = document.getElementById("kitchenSearch");
            let openDropdownId = null;

            // Dropdown toggle
            document.querySelectorAll('[id^="dropdownActionButton_"]').forEach(button => {
                const id = button.id.replace("dropdownActionButton_", "");
                const dropdown = document.getElementById(`dropdownAction_${id}`);

                button.addEventListener("click", function (e) {
                    e.stopPropagation();

                    // Toggle current dropdown
                    if (openDropdownId === id) {
                        dropdown.classList.add("hidden");
                        openDropdownId = null;
                        return;
                    }

                    // Close all others and open current
                    document.querySelectorAll('[id^="dropdownAction_"]').forEach(dd => dd.classList.add("hidden"));
                    dropdown.classList.remove("hidden");
                    openDropdownId = id;
                });
            });

            // Close dropdowns when clicking outside
            window.addEventListener("click", () => {
                document.querySelectorAll('[id^="dropdownAction_"]').forEach(dd => dd.classList.add("hidden"));
                openDropdownId = null;
            });

            // Prevent dropdown closing when clicked inside
            document.querySelectorAll('[id^="dropdownAction_"]').forEach(dropdown => {
                dropdown.addEventListener("click", e => e.stopPropagation());
            });

            // Filter & Serach event listener
            document.getElementById('filterAll')?.addEventListener('click', () => filterKitchens('all'));
            document.getElementById('filterPending')?.addEventListener('click', () => filterKitchens(0));
            searchInput?.addEventListener('input', searchKitchens);

            // Close modal with ESC key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });

            const durationSelect = document.getElementById('duration');
            const customContainer = document.getElementById('customDaysContainer');

            durationSelect.addEventListener('change', function () {
                if (this.value === 'custom') {
                    customContainer.classList.remove('hidden');
                } else {
                    customContainer.classList.add('hidden');
                }
            });
        });

        // Kitchen Data
        const kitchensData = {
            <?php foreach ($kitchens as $kitchen): ?>
                            '<?= $kitchen['kitchen_id'] ?>': {
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

        // Kitchen Model function
        function openKitchenModal(kitchenId) {
            const kitchen = kitchensData[kitchenId];
            if (!kitchen) return console.error("Kitchen not found:", kitchenId);

            // Basic informtion
            document.getElementById('modalKitchenName').textContent = kitchen.name || 'Unnamed Kitchen';
            document.getElementById('modalOwnerName').textContent = kitchen.owner_name || 'Not available';
            document.getElementById('modalOwnerEmail').textContent = kitchen.owner_email || 'Not available';
            document.getElementById('modalOwnerPhone').textContent = kitchen.owner_phone || 'Not available';
            document.getElementById('modalAddress').textContent = kitchen.address || 'Not available';
            document.getElementById('modalServiceAreas').textContent = kitchen.service_areas || 'Not specified';
            document.getElementById('modalDescription').textContent = kitchen.description || 'No description provided';

            // Image Handle
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

            // Approval Status
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

            // Date format
            try {
                const date = new Date(kitchen.created_at);
                document.getElementById('modalCreatedAt').textContent = date.toLocaleString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
            } catch (e) {
                document.getElementById('modalCreatedAt').textContent = 'Unknown date';
            }

            // Rating
            const ratingWrapper = document.getElementById('modalRatingWrapper');
            const avg = parseFloat(kitchen.avg_rating);
            if (kitchen.is_approved == 1 && !isNaN(avg)) {
                document.getElementById('modalRating').textContent = avg.toFixed(1);
                document.getElementById('modalReviewCount').textContent = `(${kitchen.review_count || 0} reviews)`;
                ratingWrapper.classList.remove('hidden');
            } else {
                ratingWrapper.classList.add('hidden');
            }

            document.getElementById('kitchenModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('kitchenModal').classList.add('hidden');
        }

        // Filter & Search functions
        function filterKitchens(status) {
            document.querySelectorAll('#kitchenGrid > div[data-status]').forEach(card => {
                card.style.display = (status === 'all' || card.dataset.status == status) ? '' : 'none';
            });
        }

        function searchKitchens() {
            const term = document.getElementById('kitchenSearch').value.toLowerCase();
            document.querySelectorAll('#kitchenGrid > div[data-search]').forEach(card => {
                card.style.display = (card.dataset.search || '').includes(term) ? '' : 'none';
            });
        }

        function openSuspendModal(kitchenId) {
            document.getElementById('suspendModal').classList.remove('hidden');
            document.getElementById('modalKitchenId').value = kitchenId;
            document.getElementById('suspendForm').action = `/admin/kitchens/suspend/${kitchenId}`;
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
        }
    </script>



    <?php
    $content = ob_get_clean();
    include BASE_PATH . '/src/views/dashboard.php';
    ?>