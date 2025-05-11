<?php
// src/includes/_dashboardSidebar.php
?>

<!-- Dashboard Sidebar -->
<aside class="fixed inset-y-0 left-0 bg-white shadow-md transition-all duration-300 ease-in-out z-40"
    id="dashboard-sidebar">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-3 h-16 border-b">
        <a href="/admin" class="flex items-center" id="sidebar-logo">
            <img src="/assets/images/TiffinCraft.png" alt="TiffinCraft Logo" class="h-10 mr-2">
            <!-- <span class="sidebar-text">Admin</span> -->
        </a>
        <button id="sidebar-toggle" class="p-1 text-gray-500 hover:text-gray-700">
            <i class="fas fa-chevron-left" id="toggle-icon"></i>
        </button>
    </div>

    <!-- Sidebar Content -->
    <div class="overflow-y-auto h-[calc(100%-4rem)] py-4 px-3">
        <ul class="space-y-2">
            <li>
                <a href="#" class="flex items-center p-2 text-base font-medium text-white rounded-lg bg-primary-500">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#"
                    class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-utensils mr-3"></i>
                    <span class="sidebar-text">Dishes</span>
                </a>
            </li>
            <!-- Other menu items with same pattern -->
            <li>
                <a href="#"
                    class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-store mr-3"></i>
                    <span class="sidebar-text">Manage Users</span>
                </a>
            </li>
            <!-- More items... -->

            <div class="border-t border-gray-100"></div>

            <li>
                <a href="/" target="_blank" rel="noopener noreferrer"
                    class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-external-link-alt mr-3"></i>
                    <span class="sidebar-text">TiffinCraft Buyer</span>
                </a>
            </li>
            <li>
                <a href="/business" target="_blank" rel="noopener noreferrer"
                    class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-external-link-alt mr-3"></i>
                    <span class="sidebar-text">TiffinCraft Seller</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Mobile Menu Button (only shown on mobile) -->
<div class="md:hidden fixed top-0 left-0 z-30 p-2">
    <button id="mobile-sidebar-toggle" class="p-2 text-gray-500 hover:text-gray-600">
        <i class="fas fa-bars text-xl"></i>
    </button>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="md:hidden fixed inset-0 z-40 hidden" id="mobile-sidebar-overlay">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" id="mobile-sidebar-backdrop"></div>
    <div class="relative flex flex-col w-full max-w-xs bg-white h-full">
        <div class="flex items-center justify-between px-4 h-16 border-b">
            <img src="/assets/images/TiffinCraft.png" alt="TiffinCraft Logo" class="h-10">
            <button id="mobile-sidebar-close" class="p-1 rounded-md text-gray-400 hover:text-gray-500">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto py-4 px-3">
            <ul class="space-y-2">
                <li>
                    <a href="#"
                        class="flex items-center p-2 text-base font-medium text-white rounded-lg bg-primary-500">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Full mobile menu items... -->
            </ul>
        </div>
    </div>
</div>