<?php
$pageTitle = "Browse Kitchens";

$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : null;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 9;

$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$selectedLocation = isset($_GET['location']) ? urldecode(trim($_GET['location'])) : null;

$kitchens = $data['kitchens'] ?? [];
$serviceAreas = $data['locations'] ?? [];
$totalItems = $data['totalItems'] ?? 0;
$totalPages = $data['totalPages'] ?? 1;
$page = $data['page'] ?? 1;


ob_start();
?>
<section class="pt-8 pb-12 bg-gray-50 min-h-[92vh]">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Page Header -->
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Discover Local Kitchens</h2>
            <p class="text-gray-600">Explore meals prepared by talented home cooks in your community.</p>
        </div>

        <!-- Filters Section -->
        <div class="flex flex-col justify-between items-center gap-6 bg-white p-4 rounded-lg shadow-sm mb-4">
            <!-- Sorting Options -->
            <div class="flex flex-wrap gap-2 w-full items-center justify-center">
                <a href="?<?= http_build_query(array_filter([
                    'search' => $searchTerm,
                    'location' => $selectedLocation,
                    'sort' => null
                ])) ?>"
                    class="px-4 py-2 rounded-full text-sm font-medium <?= !$sortBy || $sortBy === 'newest' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border hover:bg-gray-100' ?>">
                    Newest
                </a>
                <a href="?<?= http_build_query(array_filter([
                    'search' => $searchTerm,
                    'location' => $selectedLocation,
                    'sort' => 'top_rated'
                ])) ?>"
                    class="px-4 py-2 rounded-full text-sm font-medium <?= $sortBy === 'top_rated' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border hover:bg-gray-100' ?>">
                    Top Rated
                </a>
                <a href="?<?= http_build_query(array_filter([
                    'search' => $searchTerm,
                    'location' => $selectedLocation,
                    'sort' => 'most_reviews'
                ])) ?>"
                    class="px-4 py-2 rounded-full text-sm font-medium <?= $sortBy === 'most_reviews' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border hover:bg-gray-100' ?>">
                    Most Reviews
                </a>
                <a href="?<?= http_build_query(array_filter([
                    'search' => $searchTerm,
                    'location' => $selectedLocation,
                    'sort' => 'oldest'
                ])) ?>"
                    class="px-4 py-2 rounded-full text-sm font-medium <?= $sortBy === 'oldest' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border hover:bg-gray-100' ?>">
                    Oldest
                </a>
            </div>

            <!-- Advanced Filters -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 w-full">
                <!-- Search Box -->
                <div class="w-full">
                    <label class="block text-sm text-gray-500 mb-1">Search Kitchens</label>
                    <form method="get" action="/kitchens" class="relative inline-block w-full w-full">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" name="search" placeholder="Search..."
                                value="<?= htmlspecialchars($searchTerm) ?>"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <?php if ($searchTerm): ?>
                                <a href="?<?= http_build_query(array_filter([
                                    'location' => $selectedLocation,
                                    'sort' => $sortBy
                                ])) ?>"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    aria-label="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Preserve other filters in hidden fields -->
                        <?php if ($sortBy): ?>
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>">
                        <?php endif; ?>
                        <?php if ($selectedLocation): ?>
                            <input type="hidden" name="location" value="<?= htmlspecialchars($selectedLocation) ?>">
                        <?php endif; ?>
                    </form>
                </div>
                <div class="flex flex-row gap-4 w-full justify-start">
                    <!-- Location Filter -->
                    <!-- <div class="w-full sm:w-2/5"> -->
                    <div class="w-full">
                        <label class="block text-sm text-gray-500 mb-1">Select a Location: </label>
                        <select id="locationFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">All Locations</option>
                            <?php foreach ($serviceAreas as $area): ?>
                                <option value="<?= htmlspecialchars($area['name']) ?>" <?= $selectedLocation === $area['name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($area['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Clear All Filters Button -->
                    <div class="flex items-end">
                        <a href="/kitchens"
                            class="px-8 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-300 text-center">
                            Clear
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        <?php if ($selectedLocation || $sortBy): ?>
            <div class="flex flex-wrap gap-2 mb-6">
                <?php if ($selectedLocation): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-gray-800">
                        Location: <?= htmlspecialchars($selectedLocation) ?>
                        <a href="?<?= http_build_query(array_filter([
                            'search' => $searchTerm,
                            'sort' => $sortBy
                        ])) ?>" class="ml-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($sortBy && $sortBy !== 'newest'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-gray-800">
                        Sort: <?= ucfirst(str_replace('_', ' ', $sortBy)) ?>
                        <a href="?<?= http_build_query(array_filter([
                            'search' => $searchTerm,
                            'location' => $selectedLocation
                        ])) ?>" class="ml-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($kitchens)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mx-auto">
                <?php foreach ($kitchens as $kitchen): ?>
                    <div
                        class="group bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 border border-gray-100 hover:border-orange-100 h-full flex flex-col hover:shadow-md">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                                alt="<?= htmlspecialchars($kitchen['name']) ?>"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">

                            <!-- Rating Badge -->
                            <?php if ($kitchen['avg_rating']): ?>
                                <div class="absolute top-3 left-3 bg-white/90 px-2 py-1 rounded-full flex items-center shadow-sm">
                                    <i class="fas fa-star text-yellow-400 mr-1 text-sm"></i>
                                    <span class="text-sm font-medium text-gray-800">
                                        <?= round($kitchen['avg_rating'], 1) ?>
                                    </span>
                                    <span class="text-xs text-gray-500 ml-1">
                                        (<?= $kitchen['review_count'] ?>)
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-grow justify-between">
                            <div class="space-y-2">
                                <!-- Name and Owner -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($kitchen['name']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fa-solid fa-user mr-1.5 text-green-500"></i>
                                        <?= htmlspecialchars($kitchen['owner_name']) ?>
                                    </p>
                                </div>

                                <!-- Description -->
                                <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                                    <?= htmlspecialchars($kitchen['description']) ?>
                                </p>
                            </div>

                            <div class="">
                                <!-- Service Areas -->
                                <?php if (!empty($kitchen['service_areas'])): ?>
                                    <div class="mt-2 pt-2 border-t border-gray-100">
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fa-solid fa-person-biking text-orange-500 mr-1"></i>
                                            <span class="line-clamp-1" title="<?= htmlspecialchars($kitchen['service_areas']) ?>">
                                                <?= htmlspecialchars($kitchen['service_areas']) ?>
                                            </span>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- Footer -->
                                <div class="mt-auto pt-2">
                                    <div class="flex justify-between items-center">
                                        <!-- Address -->
                                        <?php if (!empty($kitchen['address'])): ?>
                                            <p class="text-xs text-gray-500 flex items-center max-w-[130px]">
                                                <i class="fa-solid fa-location-dot mr-1"></i>
                                                <span class="line-clamp-1" title="<?= htmlspecialchars($kitchen['address']) ?>">
                                                    <?= htmlspecialchars($kitchen['address']) ?>
                                                </span>
                                            </p>
                                        <?php endif; ?>

                                        <!-- View Button -->
                                        <a href="/kitchens/profile?view=<?= $kitchen['kitchen_id'] ?>"
                                            class="px-4 py-2 bg-orange-500 text-white rounded-md text-sm hover:bg-orange-600">
                                            View Menu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
        <?php else: ?>
            <div class="text-center text-gray-500 mt-32">
                <i class="fas fa-utensils text-5xl mb-4"></i>
                <p class="text-lg">No kitchens found</p>
                <a href="/kitchens" class="text-orange-500 hover:underline mt-2 inline-block">Clear all filters</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // Function to update URL with new parameters
    function updateUrl() {
        const params = new URLSearchParams(window.location.search);

        // Get current values
        const location = document.getElementById('locationFilter').value;

        // Update parameters
        if (location) {
            params.set('location', location);
        } else {
            params.delete('location');
        }

        // Remove page parameter when filters change
        params.delete('page');

        // Update URL
        window.location.search = params.toString();
    }

    // Clear search button functionality
    document.getElementById('clearSearchBtn')?.addEventListener('click', function () {
        const form = this.closest('form');
        const searchInput = form.querySelector('#searchInput');
        searchInput.value = '';

        // Submit the form while preserving other filters
        form.submit();
    });

    // Event listeners for filter changes
    document.getElementById('locationFilter').addEventListener('change', updateUrl);
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>