<?php
$isBusinessView = strpos($_SERVER['REQUEST_URI'], '/business') !== false;
?>

<footer class="<?= $isBusinessView ? 'bg-white text-gray-800' : 'bg-amber-50 text-gray-800'; ?>">
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col md:flex-row justify-between gap-8">
        <!-- Logo and Description -->
        <div class="flex-1 max-w-md">
            <div class="flex items-center">
                <img src="/assets/images/<?= $isBusinessView ? 'logo.png' : 'TiffinCraft.png'; ?>"
                    alt="TiffinCraft Logo" class="h-16 w-auto" />
                <?php if ($isBusinessView): ?>
                    <span class="font-semibold text-lg ml-3">TiffinCraft Business</span>
                <?php endif; ?>
            </div>
            <p class="text-sm text-gray-600 mt-3">
                Connecting home chefs with food lovers. Explore delicious homemade dishes crafted with care.
            </p>
        </div>

        <!-- Links Container -->
        <div class="flex flex-wrap gap-8 flex-1 justify-between max-w-2xl">
            <!-- Quick Links -->
            <div>
                <h3 class="font-semibold text-md mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <?php if ($isBusinessView): ?>
                        <li><a href="/" class="hover:underline">TiffinCraft</a></li>
                    <?php endif; ?>
                    <li><a href="/dishes" class="hover:underline">Browse Dishes</a></li>
                    <li><a href="/vendors" class="hover:underline">Browse Vendors</a></li>
                    <li><a href="/login" class="hover:underline">Login to Your Account</a></li>
                    <li><a href="/register" class="hover:underline">Register Now</a></li>
                </ul>
            </div>

            <!-- Business Links -->
            <div>
                <h3 class="font-semibold text-md mb-4">TiffinCraft Business</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/business" class="hover:underline">Sell on Our Platform</a></li>
                    <li><a href="/business/login" class="hover:underline">Login to Your Account</a></li>
                    <li><a href="/business/register" class="hover:underline">Open a Business Account</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="font-semibold text-md mb-4">Contact Us</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="mailto:info@tiffincraft.com" class="hover:underline">
                            info@tiffincraft.com
                        </a>
                    </li>
                    <li>Phone: +1-555-123-4567</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="border-t mt-8 py-6 px-6 bg-gray-100 text-sm text-gray-700">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p>&copy; <?= date('Y'); ?> TiffinCraft. All rights reserved.</p>

            <div class="flex items-center space-x-4">
                <span class="font-medium">Follow Us:</span>
                <a href="#" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                <a href="#" target="_blank" class="hover:text-sky-500"><i class="fab fa-twitter"></i></a>
                <a href="#" target="_blank" class="hover:text-pink-500"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank" class="hover:text-blue-700"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>