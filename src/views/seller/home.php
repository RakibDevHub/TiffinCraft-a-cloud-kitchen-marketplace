<?php
define('BASE_PATH', dirname(__DIR__, 3));
$pageTitle = "TiffinCraft Business";
ob_start();
?>

<!-- Hero Section for Sellers -->
<section
    class="relative h-screen flex items-center justify-center bg-cover bg-center bg-[url(/assets/images/BusinessHeroBG.jpg)]">
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>
    <div class="relative text-center px-4 z-10">
        <h1 class="text-white text-5xl font-extrabold mb-4">Grow Your Home Kitchen Business</h1>
        <p class="text-white text-xl mb-6">Reach more customers and manage your tiffin service effortlessly</p>
        <div class="flex flex-wrap justify-center items-center gap-4">
            <a href="/business/register"
                class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                Get Started
            </a>
            <a href="#features"
                class="bg-transparent border-2 border-white hover:bg-white hover:text-orange-500 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                Learn More
            </a>
        </div>
    </div>
    <div
        class="z-0 w-full aspect-[960/300] bg-no-repeat bg-center bg-cover bg-[url('../assets/images/layer3.svg')] absolute bottom-0 h-auto -mb-4">
    </div>
</section>

<!-- Business Benefits -->
<section id="features" class="bg-gray-50 py-16">
    <div class="container mx-auto text-center">
        <h2 class="text-3xl font-bold text-blue-800 mb-4">Why Sell on TiffinCraft?</h2>
        <p class="text-gray-600 mb-8">Expand your home kitchen business with our platform</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-4">
            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Grow Your Customer Base</h3>
                <p class="text-gray-600">Reach hundreds of food lovers in your area looking for homemade meals.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Easy Order Management</h3>
                <p class="text-gray-600">Our dashboard helps you track orders, payments, and customer feedback in one
                    place.</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Fair Earnings</h3>
                <p class="text-gray-600">Keep most of what you earn with our low commission rates.</p>
            </div>
        </div>
    </div>
    <!-- Shape Divider -->
    <?php include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- How It Works for Sellers -->
<section class="bg-white py-16">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Start Selling in 3 Simple Steps</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Join our community of home chefs and turn your passion into
                profit</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto px-4">
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        1</div>
                </div>
                <h3 class="text-xl font-semibold mb-2">Create Your Profile</h3>
                <p class="text-gray-600">Set up your seller profile with your kitchen details and food specialties.</p>
            </div>

            <div class="flex flex-col items-center text-center">
                <div class="relative mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        2</div>
                </div>
                <h3 class="text-xl font-semibold mb-2">Add Your Menu</h3>
                <p class="text-gray-600">Upload photos and descriptions of your dishes with prices and availability.</p>
            </div>

            <div class="flex flex-col items-center text-center">
                <div class="relative mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        3</div>
                </div>
                <h3 class="text-xl font-semibold mb-2">Start Receiving Orders</h3>
                <p class="text-gray-600">Manage incoming orders through our seller dashboard and grow your business.</p>
            </div>
        </div>
    </div>
    <!-- Shape Divider -->
    <?php $color = '#ffedd5';
    include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- Success Stories -->
<section class="bg-orange-50 py-16">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">Success Stories</h2>
            <p class="text-gray-600">Hear from our top home chefs</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto px-4">
            <div class="bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/seller1.jpg" alt="Seller" class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-semibold">Priya Sharma</h4>
                        <p class="text-orange-500 text-sm">Home Chef since 2021</p>
                    </div>
                </div>
                <p class="text-gray-600">"TiffinCraft helped me turn my passion for cooking into a thriving business. I
                    now serve 50+ customers daily and earn twice what I made at my office job!"</p>
                <div class="flex mt-4">
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/seller2.jpg" alt="Seller" class="w-16 h-16 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-semibold">Rajesh Patel</h4>
                        <p class="text-orange-500 text-sm">Home Chef since 2022</p>
                    </div>
                </div>
                <p class="text-gray-600">"The platform is so easy to use. I love how I can manage orders and communicate
                    with customers all in one place. My income has increased by 70% in just 6 months."</p>
                <div class="flex mt-4">
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Shape Divider -->
    <?php $color = '#ffffff';
    include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- Pricing Section -->
<section class="bg-white py-16">
    <div class="container mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Simple, Transparent Pricing</h2>
            <p class="text-gray-600">We succeed when you succeed</p>
        </div>

        <div class="max-w-4xl mx-auto bg-orange-50 rounded-xl p-8 shadow-inner">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-orange-500 mb-2">15% Commission</h3>
                    <p class="text-gray-600 mb-4">Only on completed orders</p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Free seller profile</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Order management tools</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Customer support</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-2xl font-bold text-orange-500 mb-2">0% Commission*</h3>
                    <p class="text-gray-600 mb-4">For the first month</p>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>All standard features included</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Perfect for testing the platform</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>No long-term commitment</span>
                        </li>
                    </ul>
                    <p class="text-sm text-gray-500 mt-4">*Applies to new sellers only</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Shape Divider -->
    <?php $color = '#f97316';
    include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- CTA Section -->
<section class="bg-orange-500 pt-12 pb-20 text-center text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6">Ready to grow your home kitchen business?</h2>
        <p class="text-orange-100 max-w-2xl mx-auto mb-8">Join hundreds of home chefs who are earning more by sharing
            their passion for cooking.</p>
        <div class="flex items-center justify-center flex-col sm:flex-row gap-4">
            <a href="/business/register"
                class="bg-white text-orange-500 font-semibold py-3 px-8 rounded-lg transition hover:bg-gray-100 inline-block">
                Sign Up Now - It's Free
            </a>
            <a href="/business/login"
                class="bg-transparent border-2 border-white text-white font-semibold py-3 px-8 rounded-lg transition hover:bg-white hover:text-orange-500 inline-block">
                Login to Your Account
            </a>
        </div>
    </div>
</section>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>