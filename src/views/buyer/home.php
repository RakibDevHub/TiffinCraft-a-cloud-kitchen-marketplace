<?php
define('BASE_PATH', dirname(__DIR__, 3));
$pageTitle = "TiffinCraft";
ob_start();
?>

<!-- Hero Section with Value Proposition -->
<section
    class="relative h-screen flex items-center justify-center bg-cover bg-center bg-[url(/assets/images/HeroBG.jpeg)]">
    <div class="absolute inset-0 bg-black bg-opacity-70"></div>
    <div class="relative text-center px-4 z-10 max-w-4xl">
        <h1 class="text-white text-5xl md:text-6xl font-bold mb-6 leading-tight">Authentic Homemade Meals Delivered to
            Your Door</h1>
        <p class="text-white text-xl md:text-2xl mb-8">Discover local home chefs preparing fresh, traditional meals with
            love and care</p>
        <div class="flex flex-wrap justify-center items-center gap-4">
            <a href="#explore"
                class=" bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                Browse Our Menu
            </a>
            <a href="#how-it-works"
                class=" bg-transparent border-2 border-white hover:bg-white hover:text-orange-500 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:scale-105">
                How It Works
            </a>
        </div>
    </div>
    <div
        class="z-0 w-full aspect-[960/300] bg-no-repeat bg-center bg-cover bg-[url('../assets/images/layer3.svg')] absolute bottom-0 h-auto -mb-4">
    </div>
</section>

<!-- Value Proposition Cards -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="bg-orange-50 p-8 rounded-xl text-center">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
                <p class="text-gray-600">Fresh meals delivered in under 45 minutes</p>
            </div>

            <div class="bg-orange-50 p-8 rounded-xl text-center">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Quality Assured</h3>
                <p class="text-gray-600">All home chefs pass rigorous quality checks</p>
            </div>

            <div class="bg-orange-50 p-8 rounded-xl text-center">
                <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Affordable Prices</h3>
                <p class="text-gray-600">Home-cooked meals at restaurant quality prices</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section id="explore" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Explore Our Menu Categories</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">From traditional thalis to regional specialties, discover
                authentic homemade flavors</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <img src="/assets/images/category-rice.jpeg" alt="Rice Meals"
                    class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <h3 class="text-white text-xl font-semibold">Rice Meals</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <img src="/assets/images/category-roti.jpeg" alt="Roti Combos"
                    class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <h3 class="text-white text-xl font-semibold">Roti Combos</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <img src="/assets/images/category-snacks.jpeg" alt="Snacks"
                    class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <h3 class="text-white text-xl font-semibold">Snacks</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition duration-300">
                <img src="/assets/images/category-special.jpeg" alt="Special Meals"
                    class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                    <h3 class="text-white text-xl font-semibold">Special Meals</h3>
                </div>
            </div>
        </div>
    </div>
    <?php include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- How It Works -->
<section id="how-it-works" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">How TiffinCraft Works</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Getting homemade food has never been easier</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="text-center p-6">
                <div class="relative inline-flex mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        1</div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Browse Local Chefs</h3>
                <p class="text-gray-600">Explore menus from home chefs in your neighborhood, with photos, ratings, and
                    detailed descriptions.</p>
            </div>

            <div class="text-center p-6">
                <div class="relative inline-flex mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        2</div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Place Your Order</h3>
                <p class="text-gray-600">Select your favorite dishes, choose delivery time, and checkout securely with
                    multiple payment options.</p>
            </div>

            <div class="text-center p-6">
                <div class="relative inline-flex mb-6">
                    <div
                        class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        3</div>
                </div>
                <h3 class="text-xl font-semibold mb-3">Enjoy Homemade Goodness</h3>
                <p class="text-gray-600">Receive fresh, hot meals delivered to your doorstep and savor authentic
                    homemade flavors.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-orange-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">What Our Customers Say</h2>
            <p class="text-gray-600">Thousands of satisfied customers across India</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <div class="bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/customer1.jpg" alt="Customer"
                        class="w-12 h-12 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-semibold">Ananya Patel</h4>
                        <div class="flex">
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"As a working professional, TiffinCraft has been a lifesaver. The food tastes
                    just like home and arrives piping hot. I've discovered amazing home chefs in my area!"</p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <img src="/assets/images/customer2.jpg" alt="Customer"
                        class="w-12 h-12 rounded-full object-cover mr-4">
                    <div>
                        <h4 class="font-semibold">Rahul Sharma</h4>
                        <div class="flex">
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                            <span class="text-yellow-400">★</span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"I love the variety of regional cuisines available. The portion sizes are
                    generous and the prices are very reasonable compared to restaurants. Highly recommended!"</p>
            </div>
        </div>
    </div>
    <?php $color = '#ffffff';
    include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- App Download CTA -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div
            class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h2 class="text-3xl font-bold text-white mb-4">Get the TiffinCraft App</h2>
                <p class="text-orange-100 mb-6">Download our app for faster ordering, exclusive offers, and to track
                    your delivery in real-time.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#"
                        class="bg-black hover:bg-gray-900 text-white flex items-center justify-center py-3 px-6 rounded-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-1.57 2.31-2.71 3.89-2.73 1.55-.03 3.17.91 3.9 2.27-3.35 1.99-2.56 6.04.54 7.14-.78 1.92-1.8 3.83-3.41 5.29zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z">
                            </path>
                        </svg>
                        App Store
                    </a>
                    <a href="#"
                        class="bg-black hover:bg-gray-900 text-white flex items-center justify-center py-3 px-6 rounded-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z">
                            </path>
                        </svg>
                        Google Play
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="/assets/images/app-screenshot.png" alt="App Screenshot"
                    class="max-h-80 rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
    <!-- Shape Divider -->
    <?php $color = '#f97316';
    include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- Final CTA -->
<section class="bg-orange-500 py-16 text-center text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6">Ready to Experience Homemade Goodness?</h2>
        <p class="text-orange-100 text-xl mb-8 max-w-2xl mx-auto">Join thousands of happy customers enjoying authentic
            home-cooked meals today</p>
        <a href="#explore"
            class="inline-block bg-white text-orange-500 font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105 hover:bg-gray-100">
            Order Now
        </a>
    </div>
</section>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>