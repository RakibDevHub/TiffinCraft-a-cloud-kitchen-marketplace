<?php
$pageTitle = "User Management";
$users = $data['users'];
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;
ob_start();

// Status configuration
$statusClasses = [
    0 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'],
    1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Active'],
    2 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Suspended'],
];

// Role configuration
$roleClasses = [
    'admin' => ['class' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-shield-alt'],
    'seller' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-store'],
    'buyer' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-shopping-bag']
];

$helper = new App\Utils\Helper();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}

// Count users by role
$roleCounts = [
    'all' => count($users),
    'buyer' => count(array_filter($users, fn($u) => strtolower($u['role']) === 'buyer')),
    'seller' => count(array_filter($users, fn($u) => strtolower($u['role']) === 'seller')),
    'admin' => count(array_filter($users, fn($u) => strtolower($u['role']) === 'admin'))
];
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

<?php if ($error): ?>
    <div id="toast-error"
        class="fixed top-12 right-6 flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-white border border-red-200 rounded-lg shadow-md z-50"
        role="alert">
        <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
            <i class="fas fa-exclamation-circle text-red-600"></i>
        </div>
        <div class="ms-3 text-sm font-medium flex-1"><?= htmlspecialchars($error) ?></div>
        <button onclick="this.parentElement.remove()" type="button"
            class="ms-auto text-gray-500 hover:text-gray-800 rounded-lg p-1.5 focus:ring-2 focus:ring-gray-300"
            aria-label="Close">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
<?php endif; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 bg-white rounded-lg shadow-sm p-6">
        <div class="w-full flex flex-row justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
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
                    <input id="userSearch" type="text" placeholder="Search Users..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" />
                </div>

                <!-- Role Filter -->
                <select id="roleFilter"
                    class="w-full sm:w-40 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="all">All Users (<?= $roleCounts['all'] ?>)</option>
                    <option value="buyer">Buyers (<?= $roleCounts['buyer'] ?>)</option>
                    <option value="seller">Sellers (<?= $roleCounts['seller'] ?>)</option>
                    <option value="admin">Admins (<?= $roleCounts['admin'] ?>)</option>
                </select>

            </div>
        </div>
    </div>

    <?php if (empty($users)): ?>
        <div class="col-span-full text-center py-10">
            <i class="fas fa-users-slash text-4xl text-gray-400 mb-3"></i>
            <p class="text-lg text-gray-500">No users found</p>
            <p class="text-sm text-gray-400">All users are approved or no new registrations</p>
        </div>
    <?php else: ?>
        <!-- User Cards View -->
        <div id="cardView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($users as $user): ?>
                <div class="user-card bg-white rounded-lg shadow hover:shadow-md transition-shadow"
                    data-role="<?= strtolower($user['role']) ?>"
                    data-search="<?= strtolower(htmlspecialchars($user['name'] . ' ' . $user['email'] . ' ' . $user['phone_number'] . ' ' . $user['address'])) ?>">

                    <!-- User Header -->
                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                        <div class="flex items-center">
                            <span
                                class="px-2 py-1 text-xs rounded-full <?= $roleClasses[strtolower($user['role'])]['class'] ?>">
                                <i class="<?= $roleClasses[strtolower($user['role'])]['icon'] ?> mr-1"></i>
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$user['status']]['class'] ?>">
                            <?= $statusClasses[$user['status']]['text'] ?>
                        </span>
                    </div>

                    <!-- User Body -->
                    <div class="p-4">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img class="h-16 w-16 rounded-full object-cover"
                                        src="<?= htmlspecialchars($user['profile_image']) ?>" alt="">
                                <?php else: ?>
                                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></h3>
                                <p class="text-sm text-gray-500">ID: <?= htmlspecialchars($user['user_id']) ?></p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope mr-2 w-5 text-center"></i>
                                <span class="truncate"><?= htmlspecialchars($user['email']) ?></span>
                            </div>

                            <?php if (!empty($user['phone_number'])): ?>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone mr-2 w-5 text-center"></i>
                                    <span><?= htmlspecialchars($user['phone_number']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user['address'])): ?>
                                <div class="flex items-start text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 mt-1 w-5 text-center"></i>
                                    <span class="truncate"><?= htmlspecialchars($user['address']) ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 w-5 text-center"></i>
                                <span>
                                    <?php
                                    try {
                                        $date = new DateTime($user['created_at']);
                                        echo $date ? $date->format('M j, Y') : 'N/A';
                                    } catch (Exception $e) {
                                        echo 'Date error';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2 mt-4">
                            <button onclick="openUserModal(<?= $user['user_id'] ?>)"
                                class="flex-1 w-full p-2 bg-blue-500 text-white text-xs rounded-lg flex items-center justify-center gap-1">
                                <i class="fas fa-eye mr-1"></i>
                                <span>View</span>
                            </button>

                            <div class="relative flex-1">
                                <button id="dropdownActionButton_<?= $user['user_id'] ?>"
                                    class="w-full p-2 bg-gray-500 text-white text-xs rounded-lg flex items-center justify-center gap-1">
                                    <i class="fas fa-ellipsis-h mr-1"></i>
                                    <span>Action</span>
                                </button>

                                <div id="dropdownAction_<?= $user['user_id'] ?>"
                                    class="z-10 hidden absolute top-full left-0 mt-1 bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-40">
                                    <ul class="py-2 text-sm text-gray-700">
                                        <?php if ($user['status'] == 0): ?>
                                            <li>
                                                <form method="post"
                                                    action="/admin/dashboard/users/activate/<?= $user['user_id'] ?>">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                        <i class="fas fa-check-circle mr-2 text-green-500"></i> Activate
                                                    </button>
                                                </form>
                                            </li>
                                        <?php elseif ($user['status'] == 1): ?>
                                            <li>
                                                <form method="post"
                                                    action="/admin/dashboard/users/deactivate/<?= $user['user_id'] ?>">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                        <i class="fas fa-times-circle w-[14px] text-center mr-2"></i> Deactivate
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <button onclick="openSuspendUserModal('<?= $user['user_id'] ?>')"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                    <i class="fas fa-pause w-[14px] text-center mr-2"></i> Suspend
                                                </button>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <form method="post"
                                                    action="/admin/dashboard/users/activate/<?= $user['user_id'] ?>">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                        <i class="fas fa-check-circle w-[14px] text-center mr-2"></i> Reactivate
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- User List View -->
        <div id="listView" class="hidden bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registered</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                    <?php foreach ($users as $user): ?>
                        <tr class="user-row hover:bg-gray-50" data-role="<?= strtolower($user['role']) ?>"
                            data-search="<?= strtolower(htmlspecialchars($user['name'] . ' ' . $user['email'] . ' ' . $user['phone_number'] . ' ' . $user['address'])) ?>">
                            <!-- User Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if (!empty($user['profile_image'])): ?>
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="<?= htmlspecialchars($user['profile_image']) ?>" alt="">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">ID: <?= htmlspecialchars($user['user_id']) ?></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                                <div class="text-sm text-gray-500">
                                    <?= !empty($user['phone_number']) ? htmlspecialchars($user['phone_number']) : 'N/A' ?>
                                </div>
                            </td>

                            <!-- Role Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full <?= $roleClasses[strtolower($user['role'])]['class'] ?>">
                                    <i class="<?= $roleClasses[strtolower($user['role'])]['icon'] ?> mr-1"></i>
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$user['status']]['class'] ?>">
                                    <?= $statusClasses[$user['status']]['text'] ?>
                                </span>
                            </td>

                            <!-- Registration Date Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php
                                try {
                                    $date = new DateTime($user['created_at']);
                                    echo $date ? $date->format('M j, Y') : 'N/A';
                                } catch (Exception $e) {
                                    echo 'Date error';
                                }
                                ?>
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openUserModal(<?= $user['user_id'] ?>)"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>

                                <div class="relative inline-block">
                                    <button id="listDropdownButton_<?= $user['user_id'] ?>"
                                        class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div id="listDropdown_<?= $user['user_id'] ?>"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                        <div class="py-1">
                                            <?php if ($user['status'] == 0): ?>
                                                <form method="post" action="/admin/dashboard/users/activate/<?= $user['user_id'] ?>"
                                                    class="block">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-check-circle w-[14px] text-center mr-2"></i> Activate
                                                    </button>
                                                </form>
                                            <?php elseif ($user['status'] == 1): ?>
                                                <form method="post"
                                                    action="/admin/dashboard/users/deactivate/<?= $user['user_id'] ?>"
                                                    class="block">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-times-circle w-[14px] text-center mr-2"></i> Deactivate
                                                    </button>
                                                </form>
                                                <button onclick="openSuspendUserModal('<?= $user['user_id'] ?>')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-pause w-[14px] text-center mr-2"></i> Suspend
                                                </button>
                                            <?php else: ?>
                                                <form method="post" action="/admin/dashboard/users/activate/<?= $user['user_id'] ?>"
                                                    class="block">
                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                    <button type="submit"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-check-circle w-[14px] text-center mr-2"></i> Reactivate
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- PAGINATION -->
            <!-- <div class="px-6 py-4 border-t flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-medium">1</span> to <span class="font-medium"><?= count($users) ?></span> of
                    <span class="font-medium"><?= count($users) ?></span> users
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        disabled>
                        Previous
                    </button>
                    <button
                        class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-orange-500 hover:bg-orange-600">
                        1
                    </button>
                    <button class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                        disabled>
                        Next
                    </button>
                </div>
            </div> -->

        </div>
    <?php endif; ?>

    <!-- User Modal -->
    <div id="userModal"
        class="fixed !mt-0 inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div
            class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto animate-fade-in p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 id="modalUserName" class="text-xl font-bold">User Details</h3>
                <button onclick="closeUserModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors bg-gray-300 p-0.5 rounded-md h-8 w-8 absolute right-2 top-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <div class="h-48 bg-gray-200 rounded-lg mb-4" id="modalUserImageWrapper">
                        <img id="modalUserImage" src="" alt="User Image"
                            class="w-full h-full object-cover rounded-lg hidden">
                        <div id="modalUserFallbackImage"
                            class="w-full h-full flex items-center justify-center bg-gray-100 rounded-lg">
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <h4 class="font-semibold text-gray-700">Contact Information</h4>
                            <p class="text-gray-600">Email: <span id="modalUserEmail"></span></p>
                            <p class="text-gray-600">Phone: <span id="modalUserPhone"></span></p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-700">Address</h4>
                            <p class="text-gray-600" id="modalUserAddress"></p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">User ID</h4>
                        <p class="text-gray-600" id="modalUserId"></p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Role</h4>
                        <span id="modalUserRole" class="px-2 py-1 text-xs rounded-full"></span>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Status</h4>
                        <span id="modalUserStatus" class="px-2 py-1 text-xs rounded-full"></span>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700">Registration Date</h4>
                        <p class="text-gray-600" id="modalUserCreatedAt"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspend User Modal -->
    <div id="suspendUserModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Suspend User</h2>
            <form id="suspendUserForm" method="post" action="">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="user_id" id="modalSuspendUserId">

                <label for="suspendDuration" class="block mb-2 font-medium text-sm">Select Duration</label>
                <select name="duration" id="suspendDuration"
                    class="w-full border rounded-md p-2 mb-4 focus:outline-none focus:ring focus:border-blue-300">
                    <option value="24">24 hours</option>
                    <option value="48">48 hours</option>
                    <option value="72">72 hours</option>
                    <option value="168">1 week</option>
                    <option value="custom">Custom (days)</option>
                </select>

                <div id="customSuspendDaysContainer" class="hidden mb-4">
                    <input type="number" name="custom_days" min="1" placeholder="Enter number of days"
                        class="w-full border rounded-md p-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeSuspendUserModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Suspend</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Filter users based on search and role
    function filterUsers() {
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value;

        // Filter card view
        document.querySelectorAll('.user-card').forEach(card => {
            const matchesSearch = card.dataset.search.includes(searchTerm);
            const matchesRole = roleFilter === 'all' || card.dataset.role === roleFilter;

            card.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });

        // Filter list view
        document.querySelectorAll('.user-row').forEach(row => {
            const matchesSearch = row.dataset.search.includes(searchTerm);
            const matchesRole = roleFilter === 'all' || row.dataset.role === roleFilter;

            row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });
    }

    // User Modal
    function openUserModal(userId) {
        const user = window.usersData[userId];
        if (!user) return console.error("Kitchen not found:", userId);

        // Basic information
        document.getElementById('modalUserName').textContent = user.name || 'Unnamed User';
        document.getElementById('modalUserId').textContent = user.user_id || 'N/A';
        document.getElementById('modalUserEmail').textContent = user.email || 'Not available';
        document.getElementById('modalUserPhone').textContent = user.phone_number || 'Not available';
        document.getElementById('modalUserAddress').textContent = user.address || 'Not specified';

        // Image handling
        const imgEl = document.getElementById('modalUserImage');
        const fallbackEl = document.getElementById('modalUserFallbackImage');
        if (user.profile_image) {
            imgEl.src = user.profile_image;
            imgEl.classList.remove('hidden');
            fallbackEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            fallbackEl.classList.remove('hidden');
        }

        // Role badge
        const roleMap = {
            'admin': { text: 'Admin', class: 'bg-purple-100 text-purple-800', icon: 'fas fa-shield-alt' },
            'seller': { text: 'Seller', class: 'bg-blue-100 text-blue-800', icon: 'fas fa-store' },
            'buyer': { text: 'Buyer', class: 'bg-green-100 text-green-800', icon: 'fas fa-shopping-bag' }
        };
        const roleBadge = document.getElementById('modalUserRole');
        const role = roleMap[user.role.toLowerCase()] || { text: 'Unknown', class: 'bg-gray-100 text-gray-800', icon: 'fas fa-user' };
        roleBadge.innerHTML = `<i class="${role.icon} mr-1"></i> ${role.text}`;
        roleBadge.className = `px-2 py-1 text-xs rounded-full ${role.class}`;

        // Status badge
        const statusMap = {
            0: { text: 'Inactive', class: 'bg-red-100 text-red-800' },
            1: { text: 'Active', class: 'bg-green-100 text-green-800' },
            2: { text: 'Suspended', class: 'bg-yellow-100 text-yellow-800' }
        };
        const statusBadge = document.getElementById('modalUserStatus');
        const status = statusMap[user.status] || { text: 'Unknown', class: 'bg-gray-100 text-gray-800' };
        statusBadge.textContent = status.text;
        statusBadge.className = `px-2 py-1 text-xs rounded-full ${status.class}`;

        // Date formatting
        try {
            const date = new Date(user.created_at);
            document.getElementById('modalUserCreatedAt').textContent = date.toLocaleString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        } catch (e) {
            document.getElementById('modalUserCreatedAt').textContent = 'Unknown date';
        }

        // Show modal
        document.getElementById('userModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Suspend Modal
    function openSuspendUserModal(userId) {
        document.getElementById('modalSuspendUserId').value = userId;
        document.getElementById('suspendUserForm').action = `/admin/dashboard/users/suspend/${userId}`;
        document.getElementById('suspendUserModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSuspendUserModal() {
        document.getElementById('suspendUserModal').classList.add('hidden');
        document.body.style.overflow = '';
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
        const defaultView = localStorage.getItem('userView') || 'card';
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
            localStorage.setItem('userView', 'card');
        });

        listViewBtn.addEventListener('click', function () {
            cardView.classList.add('hidden');
            listView.classList.remove('hidden');
            cardViewBtn.classList.remove('bg-gray-200');
            listViewBtn.classList.add('bg-gray-200');
            localStorage.setItem('userView', 'list');
        });

        // Filter role
        const roleFilter = document.getElementById('roleFilter');
        roleFilter.addEventListener('change', function () {
            filterUsers();
        });

        // Search function
        const searchInput = document.getElementById('userSearch');
        searchInput.addEventListener('input', function () {
            filterUsers();
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

        // Close modals
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeUserModal();
                closeSuspendUserModal();
            }
        });

        // Suspend modal 
        const suspendDurationSelect = document.getElementById('suspendDuration');
        const customSuspendContainer = document.getElementById('customSuspendDaysContainer');

        suspendDurationSelect.addEventListener('change', function () {
            customSuspendContainer.classList.toggle('hidden', this.value !== 'custom');
        });
    });

    // Users data
    window.usersData = {
        <?php foreach ($users as $user): ?>'<?= $user['user_id'] ?>': {
                name: '<?= addslashes(htmlspecialchars($user['name'])) ?>',
                user_id: '<?= addslashes(htmlspecialchars($user['user_id'])) ?>',
                email: '<?= addslashes(htmlspecialchars($user['email'])) ?>',
                phone_number: '<?= addslashes(htmlspecialchars($user['phone_number'])) ?>',
                address: '<?= addslashes(htmlspecialchars($user['address'])) ?>',
                profile_image: '<?= addslashes(htmlspecialchars($user['profile_image'] ?? '')) ?>',
                role: '<?= addslashes(htmlspecialchars($user['role'])) ?>',
                status: <?= $user['status'] ?>,
                created_at: '<?= $user['created_at'] ?>'
            },
        <?php endforeach; ?>
    };
</script>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>