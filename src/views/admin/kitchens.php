<?php
$pageTitle = "Kitchen Management";
$kitchens = $data['kitchens'];
$error = $data['error'];
ob_start();

// Status configuration
$statusClasses = [
    0 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Pending'],
    1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Approved'],
    2 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Rejected']
];

$helper = new App\Utils\Helper();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}

// Check if we're viewing a specific kitchen
$viewingKitchen = isset($_GET['view']) ? array_filter($kitchens, fn($k) => $k['kitchen_id'] == $_GET['view']) : [];
$viewingKitchen = reset($viewingKitchen) ?: null;
?>

<div class="space-y-6">
    <!-- Header and Filters -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kitchen Approvals</h1>
        <div class="flex space-x-3">
            <div class="flex space-x-2">
                <button id="filterAll"
                    class="px-3 py-2 border rounded-lg text-sm font-medium bg-white hover:bg-gray-50">
                    All <span id="allCount"
                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs ml-1"><?= count($kitchens) ?></span>
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
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow"
                    data-status="<?= $kitchen['is_approved'] ?>"
                    data-search="<?= strtolower(htmlspecialchars($kitchen['name'] . ' ' . $kitchen['address'] . ' ' . $kitchen['owner_name'])) ?>">
                    <!-- Kitchen Image -->
                    <div class="h-48 bg-gray-200 relative">
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
                                <a href="?view=<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1 bg-blue-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors text-center">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="/admin/kitchens/edit/<?= $kitchen['kitchen_id'] ?>"
                                    class="flex-1 bg-gray-500 text-white py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors text-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Kitchen Details Modal (shown when ?view=ID is in URL) -->
    <?php if ($viewingKitchen): ?>
        <div id="kitchenModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 id="modalKitchenName" class="text-xl font-bold"><?= htmlspecialchars($viewingKitchen['name']) ?>
                        </h3>
                        <a href="?" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div id="modalKitchenImage" class="h-48 bg-gray-200 rounded-lg mb-4">
                                <?php if (!empty($viewingKitchen['kitchen_image'])): ?>
                                    <img src="<?= htmlspecialchars($viewingKitchen['kitchen_image']) ?>"
                                        class="w-full h-full object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                                        <i class="fas fa-utensils text-4xl text-gray-400"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <h4 class="font-semibold text-gray-700">Owner Information</h4>
                                    <p id="modalOwnerName" class="text-gray-600">Name:
                                        <?= htmlspecialchars($viewingKitchen['owner_name']) ?>
                                    </p>
                                    <p id="modalOwnerEmail" class="text-gray-600">Email:
                                        <?= htmlspecialchars($viewingKitchen['owner_email']) ?>
                                    </p>
                                    <p id="modalOwnerPhone" class="text-gray-600">Phone:
                                        <?= htmlspecialchars($viewingKitchen['owner_phone'] ?? 'N/A') ?>
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700">Address</h4>
                                    <p id="modalKitchenAddress" class="text-gray-600">
                                        <?= htmlspecialchars($viewingKitchen['address']) ?>
                                    </p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-700">Service Areas</h4>
                                    <p id="modalKitchenAreas" class="text-gray-600">
                                        <?= htmlspecialchars($viewingKitchen['service_areas'] ?? 'No service areas specified') ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Description</h4>
                                <p id="modalKitchenDescription" class="text-gray-600">
                                    <?= htmlspecialchars($viewingKitchen['description'] ?? 'No description provided') ?>
                                </p>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Status</h4>
                                <span id="modalKitchenStatus"
                                    class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$viewingKitchen['is_approved']]['class'] ?>">
                                    <?= $statusClasses[$viewingKitchen['is_approved']]['text'] ?>
                                </span>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700">Registration Date</h4>
                                <p id="modalKitchenCreatedAt" class="text-gray-600">
                                    <?= date('M j, Y \a\t g:i a', strtotime($viewingKitchen['created_at'])) ?>
                                </p>
                            </div>

                            <?php if ($viewingKitchen['is_approved'] == 1 && isset($viewingKitchen['avg_rating'])): ?>
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700">Rating</h4>
                                    <div id="modalKitchenRating" class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        <span id="ratingValue"><?= round($viewingKitchen['avg_rating'], 1) ?></span>
                                        <span id="reviewCount"
                                            class="text-gray-500 ml-1">(<?= $viewingKitchen['review_count'] ?> reviews)</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="?"
                            class="px-4 py-2 border rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                            Close
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Filter and Search Functionality
    document.getElementById('filterAll').addEventListener('click', () => filterKitchens('all'));
    document.getElementById('filterPending').addEventListener('click', () => filterKitchens(0));
    document.getElementById('kitchenSearch').addEventListener('input', searchKitchens);

    function filterKitchens(status) {
        const kitchenCards = document.querySelectorAll('#kitchenGrid > div[data-status]');

        kitchenCards.forEach(card => {
            if (status === 'all' || card.dataset.status == status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function searchKitchens() {
        const searchTerm = document.getElementById('kitchenSearch').value.toLowerCase();
        const kitchenCards = document.querySelectorAll('#kitchenGrid > div[data-search]');

        kitchenCards.forEach(card => {
            const searchText = card.dataset.search;
            if (searchText.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white flex items-center ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>