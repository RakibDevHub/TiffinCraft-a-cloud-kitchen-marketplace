<?php
$pageTitle = htmlspecialchars($kitchen['name']) . " | Kitchen Profile";
$kitchen = $data['kitchen'] ?? [];
$menuItems = $data['menuItems'] ?? [];
$kitchenReviews = $data['kitchenReviews'] ?? [];
$categories = $data['categories'] ?? [];

ob_start();
?>
<section class="bg-gray-50 py-10 min-h-[92vh]">
    <div class="container mx-auto px-4 max-w-5xl">
        <!-- Kitchen Header -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden mb-10">
            <!-- Cover Image -->
            <div class="relative h-64 bg-gray-100">
                <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                    alt="<?= htmlspecialchars($kitchen['name']) ?>" class="w-full h-full object-cover">
            </div>

            <!-- Content -->
            <div class="relative px-6 pb-6 pt-4">
                <!-- Owner Image & Kitchen Name -->
                <div class="flex items-start gap-6 -mt-16">
                    <img src="<?= htmlspecialchars($kitchen['owner_image']) ?>"
                        alt="<?= htmlspecialchars($kitchen['owner_name']) ?>"
                        class="w-28 h-28 rounded-lg border-4 border-white object-cover shadow-sm">

                    <div class="flex flex-1 gap-4 mt-16">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 ">
                            <?= htmlspecialchars($kitchen['name']) ?>
                        </h1>

                        <!-- Rating -->
                        <?php if ($kitchen['avg_rating']): ?>
                            <div class="mt-1 flex items-center text-sm text-yellow-600 font-medium">
                                <i class="fas fa-star mr-1 text-yellow-400"></i>
                                <?= round($kitchen['avg_rating'], 1) ?>
                                <span class="ml-2 text-gray-500">(<?= $kitchen['review_count'] ?> reviews)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Owner and Kitchen Info -->
                <div
                    class="mt-6 flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:flex-wrap sm:gap-x-8 sm:gap-y-1">
                    <p><i class="fas fa-user text-green-500 mr-1"></i><?= htmlspecialchars($kitchen['owner_name']) ?>
                    </p>
                    <p><i
                            class="fas fa-envelope text-blue-500 mr-1"></i><?= htmlspecialchars($kitchen['owner_email']) ?>
                    </p>
                    <p><i class="fas fa-phone text-indigo-500 mr-1"></i><?= htmlspecialchars($kitchen['owner_phone']) ?>
                    </p>
                    <p><i
                            class="fas fa-map-marker-alt text-orange-500 mr-1"></i><?= htmlspecialchars($kitchen['address']) ?>
                    </p>
                    <?php if (!empty($kitchen['service_areas'])): ?>
                        <p><i class="fas fa-person-biking text-green-500 mr-1"></i>Delivers to:
                            <?= htmlspecialchars($kitchen['service_areas']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <?php if (!empty($kitchen['description'])): ?>
                    <div class="mt-4 text-gray-700 leading-relaxed text-justify">
                        <?= nl2br(htmlspecialchars($kitchen['description'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="bg-white shadow-sm rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Menu Items</h2>
            <!-- Filter/Search Options -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Search -->
                <input type="text" id="searchInput" placeholder="Search menu items..."
                    class="w-full sm:w-64 px-4 py-2 border rounded-md shadow-sm text-sm focus:ring-orange-500 focus:border-orange-500">

                <!-- Filters -->
                <div class="flex flex-wrap gap-3 text-sm">
                    <select id="categoryFilter" class="px-3 py-2 border rounded-md shadow-sm">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= strtolower($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="priceFilter" class="px-3 py-2 border rounded-md shadow-sm">
                        <option value="">Sort by Price</option>
                        <option value="low">Low to High</option>
                        <option value="high">High to Low</option>
                    </select>
                    <button id="clearFilters"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-sm">
                        Clear Filters
                    </button>
                </div>
            </div>
            <?php if (!empty($menuItems)): ?>
                <div id="menuGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($menuItems as $item): ?>
                        <div class="menu-item bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition duration-300 flex flex-col"
                            data-name="<?= strtolower($item['name']) ?>"
                            data-description="<?= strtolower($item['description']) ?>"
                            data-tags="<?= strtolower($item['tags'] ?? '') ?>"
                            data-category="<?= strtolower($item['category_name']) ?>" data-price="<?= $item['price'] ?>">
                            <?php if ($item['item_image']): ?>
                                <img src=" <?= htmlspecialchars($item['item_image']) ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-56 object-cover shadow-sm">
                            <?php else: ?>
                                <div
                                    class="w-full h-56 flex items-center shadow-sm justify-center text-gray-400 group-hover:text-gray-500 transition-colors">
                                    <i class="fas fa-utensils text-4xl"></i>
                                </div>
                            <?php endif ?>

                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($item['category_name']) ?></p>

                                <p class="text-gray-600 mb-2 text-sm flex-grow max-h-20 line-clamp-2">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>

                                <?php if (!empty($item['tags'])): ?>
                                    <p class="mb-2 flex flex-wrap gap-1.5 max-h-10 py-1 line-clamp-2">
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
                                    </p>
                                <?php endif; ?>

                                <!-- Rating -->
                                <?php if ($item['avg_rating']): ?>
                                    <div class="mb-2 flex items-center text-sm text-yellow-600 font-medium">
                                        <i class="fas fa-star mr-1 text-yellow-400"></i>
                                        <?= round($item['avg_rating'], 1) ?>
                                        <span class="ml-2 text-gray-500">(<?= $item['review_count'] ?> reviews)</span>
                                    </div>
                                <?php endif; ?>

                                <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-200">
                                    <span
                                        class="text-orange-500 font-bold text-lg">৳<?= htmlspecialchars($item['price']) ?></span>
                                    <button class="px-4 py-2 bg-orange-500 text-white rounded-md text-sm hover:bg-orange-600">
                                        Order Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="noResultsMessage" class="text-gray-500 text-center hidden mt-4">No menu items match your criteria.
                </p>

            <?php else: ?>
                <p class="text-gray-500 text-center">No menu items found for this kitchen.</p>
            <?php endif; ?>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white shadow-sm rounded-xl p-6 mt-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Reviews</h2>

            <?php if (!empty($reviews)): ?>
                <div class="space-y-6">
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-center mb-1">
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($review['reviewer_name']) ?></p>
                                <div class="text-yellow-500 text-sm">
                                    <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($review['comment']) ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= date("F j, Y", strtotime($review['created_at'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No reviews yet. Be the first to review!</p>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const priceFilter = document.getElementById('priceFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const menuItems = document.querySelectorAll('.menu-item');

        function filterItems() {
            const search = searchInput.value.toLowerCase();
            const category = categoryFilter.value.toLowerCase();
            const sortByPrice = priceFilter.value;

            let items = Array.from(menuItems);

            // Filters
            items.forEach(item => {
                const name = item.dataset.name;
                const desc = item.dataset.description;
                const tags = item.dataset.tags;
                const cat = item.dataset.category;
                const matchesSearch = name.includes(search) || desc.includes(search) || tags.includes(search);
                const matchesCategory = !category || cat.toLowerCase() === category;

                item.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
            });

            let visibleItems = items.filter(item => item.style.display !== 'none');

            if (sortByPrice) {
                visibleItems.sort((a, b) => {
                    let pa = parseFloat(a.dataset.price), pb = parseFloat(b.dataset.price);
                    return sortByPrice === 'low' ? pa - pb : pb - pa;
                });
            }

            const grid = document.getElementById('menuGrid');
            const noResults = document.getElementById('noResultsMessage');

            if (visibleItems.length === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }

            visibleItems.forEach(item => grid.appendChild(item));

        }

        [searchInput, priceFilter, categoryFilter].forEach(el => {
            el.addEventListener('input', filterItems);
            el.addEventListener('change', filterItems);
        });

        const clearBtn = document.getElementById('clearFilters');

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            categoryFilter.value = '';
            priceFilter.value = '';

            filterItems();
        });
    });
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>