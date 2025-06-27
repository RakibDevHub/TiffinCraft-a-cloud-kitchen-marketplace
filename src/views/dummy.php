<?php
$pageTitle = "Dishes";

$selectedCategory = isset($_GET['categories']) ? urldecode(trim($_GET['categories'])) : null;
$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : null;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 9;

$priceSort = isset($_GET['price_sort']) ? $_GET['price_sort'] : null;
$selectedLocation = isset($_GET['location']) ? urldecode(trim($_GET['location'])) : null;

$dishes = $data['menuItems'] ?? [];
$categories = $data['categories'] ?? [];
$serviceAreas = $data['locations'] ?? [];

// Apply filters
if ($selectedCategory) {
    $dishes = array_filter($dishes, fn($dish) => strtolower(trim($dish['category_name'])) === strtolower(trim($selectedCategory)));
}

if ($searchTerm) {
    $dishes = array_filter($dishes, function ($dish) use ($searchTerm) {
        return str_contains(strtolower($dish['name']), $searchTerm) ||
            str_contains(strtolower($dish['description']), $searchTerm) ||
            str_contains(strtolower($dish['tags'] ?? ''), $searchTerm);
    });
}

if ($selectedLocation) {
    $dishes = array_filter($dishes, function ($dish) use ($selectedLocation) {
        if (empty($dish['service_areas']))
            return false;
        $areas = array_map('trim', explode(',', $dish['service_areas']));
        return in_array(strtolower(trim($selectedLocation)), array_map('strtolower', $areas));
    });
}

if ($priceSort === 'low_to_high') {
    usort($dishes, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($priceSort === 'high_to_low') {
    usort($dishes, fn($a, $b) => $b['price'] <=> $a['price']);
}

// Pagination logic
$totalItems = count($dishes);
$totalPages = ceil($totalItems / $perPage);
$page = max(1, min($page, $totalPages));
$offset = ($page - 1) * $perPage;
$dishes = array_slice($dishes, $offset, $perPage);

ob_start();
?>
<section class="pt-8 pb-12 bg-gray-50 min-h-[92vh]">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Page Header -->
        <div class="text-center mb-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">
                <?= $selectedCategory ? htmlspecialchars($selectedCategory) . ' Dishes' : 'Our Delicious Menu' ?>
            </h2>
            <p class="text-gray-600">Explore meals handcrafted by local home chefs.</p>
        </div>

        <!-- Filters Section -->
        <div class="flex flex-col justify-between items-center gap-6 bg-white p-4 rounded-lg shadow-sm mb-4">
            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="?<?= http_build_query(array_filter([
                    'search' => $searchTerm,
                    'location' => $selectedLocation,
                    'price_sort' => $priceSort
                ])) ?>"
                    class="px-4 py-2 rounded-full text-sm font-medium <?= !$selectedCategory ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border' ?>">
                    All
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="?<?= http_build_query(array_filter([
                        'categories' => $category['name'],
                        'search' => $searchTerm,
                        'location' => $selectedLocation,
                        'price_sort' => $priceSort
                    ])) ?>"
                        class="px-4 py-2 rounded-full text-sm font-medium <?= (strtolower($selectedCategory) === strtolower($category['name'])) ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border hover:bg-gray-100' ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Advanced Filters -->
            <div class="flex items-center justify-between gap-4 w-full">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Location Filter -->
                    <div>
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

                    <!-- Price Sort Filter -->
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Sort by Price: </label>
                        <select id="priceSort" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">None</option>
                            <option value="low_to_high" <?= $priceSort === 'low_to_high' ? 'selected' : '' ?>>Low to
                                High
                            </option>
                            <option value="high_to_low" <?= $priceSort === 'high_to_low' ? 'selected' : '' ?>>High to
                                Low
                            </option>
                        </select>
                    </div>

                    <!-- Clear All Filters Button -->
                    <div class="flex items-end">
                        <a href="/dishes"
                            class="px-8 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-300 text-center">
                            Clear
                        </a>
                    </div>
                </div>
                <!-- Search Box -->
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Search Dishes</label>
                    <form method="get" action="/dishes" class="relative inline-block w-full md:w-72">
                        <div class="relative">
                            <input type="text" id="searchInput" name="search" placeholder="Search..."
                                value="<?= htmlspecialchars($searchTerm) ?>"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <?php if ($searchTerm): ?>
                                <button type="button" id="clearSearchBtn"
                                    class="absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    aria-label="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            <?php endif; ?>
                            <button type="submit"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <!-- Preserve other filters in hidden fields -->
                        <?php if ($selectedCategory): ?>
                            <input type="hidden" name="categories" value="<?= htmlspecialchars($selectedCategory) ?>">
                        <?php endif; ?>
                        <?php if ($selectedLocation): ?>
                            <input type="hidden" name="location" value="<?= htmlspecialchars($selectedLocation) ?>">
                        <?php endif; ?>
                        <?php if ($priceSort): ?>
                            <input type="hidden" name="price_sort" value="<?= htmlspecialchars($priceSort) ?>">
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        <?php if ($selectedLocation || $priceSort): ?>
            <div class="flex flex-wrap gap-2 mb-6">
                <?php if ($selectedLocation): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-gray-800">
                        Location: <?= htmlspecialchars($selectedLocation) ?>
                        <a href="?<?= http_build_query(array_filter([
                            'categories' => $selectedCategory,
                            'search' => $searchTerm,
                            'price_sort' => $priceSort
                        ])) ?>" class="ml-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>

                <?php if ($priceSort): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-gray-800">
                        Price: <?= $priceSort === 'low_to_high' ? 'Low to High' : 'High to Low' ?>
                        <a href="?<?= http_build_query(array_filter([
                            'categories' => $selectedCategory,
                            'search' => $searchTerm,
                            'location' => $selectedLocation
                        ])) ?>" class="ml-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($dishes)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mx-auto">
                <?php foreach ($dishes as $dish): ?>
                    <div
                        class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition duration-300 flex flex-col">
                        <?php if ($dish['item_image']): ?>
                            <img src="<?= htmlspecialchars($dish['item_image']) ?>" alt="<?= htmlspecialchars($dish['name']) ?>"
                                class="w-full h-56 object-cover shadow-sm">
                        <?php else: ?>
                            <div
                                class="w-full h-56 flex items-center shadow-sm justify-center text-gray-400 group-hover:text-gray-500 transition-colors">
                                <i class="fas fa-utensils text-4xl"></i>
                            </div>
                        <?php endif ?>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($dish['name']) ?></h3>
                            <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($dish['category_name']) ?></p>

                            <p class="text-gray-600 mb-2 text-sm flex-grow max-h-20 line-clamp-2">
                                <?= htmlspecialchars($dish['description']) ?>
                            </p>

                            <?php if (!empty($dish['tags'])): ?>
                                <p class="mb-4 flex flex-wrap gap-1.5 max-h-10 py-1 line-clamp-2">
                                    <?php
                                    $tags = array_filter(array_map('trim', explode(',', $dish['tags'])));
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
                                </p>
                            <?php endif; ?>

                            <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-200">
                                <span class="text-orange-500 font-bold text-lg">৳<?= htmlspecialchars($dish['price']) ?></span>
                                <button class="px-4 py-2 bg-orange-500 text-white rounded-md text-sm hover:bg-orange-600">
                                    Order Now
                                </button>
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
                <p class="text-lg">No dishes found with the current filters</p>
                <a href="/dishes" class="text-orange-500 hover:underline mt-2 inline-block">Clear all filters</a>
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
        const priceSort = document.getElementById('priceSort').value;

        // Update parameters
        if (location) {
            params.set('location', location);
        } else {
            params.delete('location');
        }

        if (priceSort) {
            params.set('price_sort', priceSort);
        } else {
            params.delete('price_sort');
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
    document.getElementById('priceSort').addEventListener('change', updateUrl);
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>