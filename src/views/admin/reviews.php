<?php
$pageTitle = "Manage Reviews";
$reviews = $data['reviews'] ?? [];

ob_start();
?>

<!-- Flash Messages -->
<?php if ($success): ?>
    <div id="toast-success" class="fixed top-12 right-6 flex items-center w-full max-w-xs p-4 mb-4 text-green-700 bg-white border border-green-200 rounded-lg shadow-md z-50">
        <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
            <i class="fas fa-check text-green-600"></i>
        </div>
        <div class="ms-3 text-sm font-medium flex-1"><?= htmlspecialchars($success) ?></div>
        <button onclick="this.parentElement.remove()" class="ms-auto text-gray-500 hover:text-gray-800 rounded-lg p-1.5">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div id="toast-error" class="fixed top-12 right-6 flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-white border border-red-200 rounded-lg shadow-md z-50">
        <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
            <i class="fas fa-exclamation-circle text-red-600"></i>
        </div>
        <div class="ms-3 text-sm font-medium flex-1"><?= htmlspecialchars($error) ?></div>
        <button onclick="this.parentElement.remove()" class="ms-auto text-gray-500 hover:text-gray-800 rounded-lg p-1.5">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
<?php endif; ?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Manage Reviews</h1>
        <div class="flex space-x-3">
            <select id="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Reviews</option>
                <option value="pending">Pending Reviews</option>
                <option value="active">Active Reviews</option>
                <option value="hidden">Hidden Reviews</option>
            </select>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search reviews..." class="pl-10 pr-4 py-2 border rounded-lg text-sm w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium">
                <tr>
                    <th class="px-6 py-3">Reviewer</th>
                    <th class="px-6 py-3">Rating</th>
                    <th class="px-6 py-3">Comment</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody id="reviewTable" class="divide-y divide-gray-200">
                <?php foreach ($reviews as $review): ?>
                    <tr class="hover:bg-gray-50" 
                        data-status="<?= htmlspecialchars($review['status']) ?>"
                        data-text="<?= strtolower($review['reviewer_name'] . ' ' . $review['comments']) ?>">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="<?= htmlspecialchars($review['reviewer_image'] ?? '/assets/images/default-user.png') ?>" class="w-8 h-8 rounded-full">
                            <?= htmlspecialchars($review['reviewer_name']) ?>
                        </td>
                        <td class="px-6 py-4 text-yellow-500">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?= $i <= $review['rating'] ? '' : '-o' ?>"></i>
                            <?php endfor; ?>
                        </td>
                        <td class="px-6 py-4"><?= htmlspecialchars($review['comments']) ?></td>
                        <td class="px-6 py-4 capitalize"><?= htmlspecialchars($review['status']) ?></td>
                        <td class="px-6 py-4"><?= date("d M Y", strtotime($review['created_at'])) ?></td>
                        <td class="px-6 py-4">
                            <form action="/admin/reviews/update" method="POST" class="flex gap-1">
                                <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                <?php foreach (['active', 'pending', 'hidden'] as $state): ?>
                                    <?php if ($review['status'] !== $state): ?>
                                        <button name="status" value="<?= $state ?>" class="text-xs px-2 py-1 bg-gray-100 border rounded hover:bg-orange-100">
                                            <?= ucfirst($state) ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#reviewTable tr');

    function filterReviews() {
        const keyword = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        rows.forEach(row => {
            const matchesStatus = !status || row.dataset.status === status;
            const matchesSearch = !keyword || row.dataset.text.includes(keyword);
            row.style.display = (matchesStatus && matchesSearch) ? 'table-row' : 'none';
        });
    }

    searchInput.addEventListener('input', filterReviews);
    statusFilter.addEventListener('change', filterReviews);
});
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
