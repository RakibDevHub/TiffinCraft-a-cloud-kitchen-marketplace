<?php
$pageTitle = "User Management";
$users = $data['users'];
$error = $data['error'];
ob_start();

// Status configuration
$statusClasses = [
    0 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'],
    1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Active'],
    2 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Suspended'],
];

// Role configuration
$roleClasses = [
    'admin' => 'bg-purple-100 text-purple-800',
    'seller' => 'bg-blue-100 text-blue-800',
    'buyer' => 'bg-green-100 text-green-800'
];

$helper = new App\Utils\Helper();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">User Management</h1>
        <div class="flex space-x-3">
            <!-- Search Input -->
            <div class="relative">
                <input type="text" placeholder="Search users..." id="userSearch"
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm w-64 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>

            <!-- Role Filter -->
            <div class="relative">
                <select id="roleFilter"
                    class="appearance-none pl-3 pr-8 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="all">All Roles</option>
                    <option value="buyer">Buyers</option>
                    <option value="seller">Sellers</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ERROR MESSAGE -->
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <p class="font-semibold">Database Error</p>
                    <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- MAIN CONTENT -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php if (empty($users)): ?>
            <!-- Empty State -->
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-users-slash text-4xl mb-3"></i>
                <p class="text-lg">No users found</p>
                <p class="text-sm">Please check your database connection or try again later.</p>
            </div>
        <?php else: ?>
            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Registered</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50" data-role="<?= strtolower($user['role']) ?>">
                                <!-- User Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <?php if (!empty($user['profile_image'])): ?>
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="<?= htmlspecialchars($user['profile_image']) ?>" alt="">
                                            <?php else: ?>
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($user['name']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">ID: <?= htmlspecialchars($user['user_id']) ?>
                                            </div>
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

                                <!-- Address Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <?= !empty($user['address']) ? htmlspecialchars($user['address']) : 'N/A' ?>
                                    </div>
                                </td>

                                <!-- Role Column -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $roleClasses[strtolower($user['role'])] ?? 'bg-gray-100 text-gray-800' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full <?= $statusClasses[$user['status']]['class'] ?>">
                                        <?= $statusClasses[$user['status']]['text'] ?>
                                    </span>
                                </td>

                                <!-- Registration Date Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php
                                    try {
                                        $date = DateTime::createFromFormat('d-M-y H.i.s.u A', $user['created_at']);
                                        echo $date ? $date->format('M j, Y \a\t g:i A') : 'N/A';
                                    } catch (Exception $e) {
                                        echo 'Date error';
                                    }
                                    ?>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openUserModal(<?= htmlspecialchars(json_encode($user)) ?>)"
                                        class="text-orange-600 hover:text-orange-900 mr-3">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="px-6 py-4 border-t flex items-center justify-between">
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
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Initialize event listeners
    document.getElementById('userSearch').addEventListener('input', filterUsers);
    document.getElementById('roleFilter').addEventListener('change', filterUsers);

    /**
     * Filters users based on search term and role selection
     */
    function filterUsers() {
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value;

        document.querySelectorAll('#userTableBody tr').forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowRole = row.getAttribute('data-role');

            const showRow = (searchTerm === '' || rowText.includes(searchTerm)) &&
                (roleFilter === 'all' || rowRole === roleFilter);

            row.style.display = showRow ? '' : 'none';
        });
    }

    function openUserModal(user) {
        console.log('Editing user:', user);
        // Modal implementation would go here
    }
</script>

<?php
// Output the content
$content = ob_get_clean();
include BASE_PATH . '/src/views/dashboard.php';
?>