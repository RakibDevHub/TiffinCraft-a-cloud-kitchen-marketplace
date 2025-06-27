<?php
$pageTitle = htmlspecialchars($kitchen['name']) . " | Kitchen Profile";
ob_start();
?>
<section class="bg-gray-50 py-10 min-h-[92vh]">
    <div class="container mx-auto px-4 max-w-5xl">
        <!-- Kitchen Header -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden mb-8">
            <div class="relative h-64 bg-gray-100">
                <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                    alt="<?= htmlspecialchars($kitchen['name']) ?>" class="w-full h-full object-cover">
            </div>
            <div class="px-6 py-2 relative">
                <div class="flex flex-row gap-4">
                    <img src="<?= htmlspecialchars($kitchen['owner_image']) ?>"
                        alt="<?= htmlspecialchars($kitchen['owner_name']) ?>"
                        class="w-28 h-28 rounded-md -mt-10 object-cover border-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($kitchen['name']) ?></h1>
                        <div class="flex text-sm gap-4 text-gray-500">
                            <p class="flex items-center"><i class="fas fa-user text-green-500 mr-1 text-xs"></i>
                                <?= htmlspecialchars($kitchen['owner_name']) ?></p>
                            <p class="flex items-center"><i class="fas fa-envelope text-blue-500 mr-1 text-xs"></i>
                                <?= htmlspecialchars($kitchen['owner_email']) ?></p>
                            <p class="flex items-center"><i class="fas fa-phone text-indigo-500 mr-1 text-xs"></i>
                                <?= htmlspecialchars($kitchen['owner_phone']) ?></p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 mt-2"><?= htmlspecialchars($kitchen['description']) ?></p>
                    <?php if (!empty($kitchen['service_areas'])): ?>
                        <p class="mt-2 text-sm text-orange-600">
                            <i class="fas fa-person-biking mr-1"></i>
                            Delivers to: <?= htmlspecialchars($kitchen['service_areas']) ?>
                        </p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500"><i
                            class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($kitchen['address']) ?></p>
                </div>
            </div>
        </div>

        <!-- Owner Info -->
        <div class="bg-white shadow-sm rounded-xl p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Owner Information</h2>
            <p><i class="fas fa-user text-green-500 mr-1"></i> <?= htmlspecialchars($kitchen['owner_name']) ?></p>
            <p><i class="fas fa-envelope text-blue-500 mr-1"></i> <?= htmlspecialchars($kitchen['owner_email']) ?></p>
            <p><i class="fas fa-phone text-indigo-500 mr-1"></i> <?= htmlspecialchars($kitchen['owner_phone']) ?></p>
        </div>

        <!-- Menu Items -->
        <div class="bg-white shadow-sm rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Menu Items</h2>
            <?php if (!empty($menuItems)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($menuItems as $item): ?>
                        <div
                            class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col">
                            <img src="<?= htmlspecialchars($item['item_image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                                class="h-40 object-cover w-full">
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2"><?= htmlspecialchars($item['description']) ?>
                                </p>
                                <p class="mt-auto text-orange-600 font-semibold text-sm mt-3">৳<?= $item['price'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center">No menu items found for this kitchen.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>