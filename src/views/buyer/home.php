<?php
$pageTitle = "TiffinCraft";
$categories = $data['categories'] ?? [];
$kitchens = $data['kitchens'] ?? [];

$isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);

ob_start();
?>

<!-- Hero Section with Value Proposition -->
<section
    class="relative top-[-56px] h-screen flex items-center justify-center bg-cover bg-center bg-[url(/assets/images/HeroBG.jpeg)]">
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
            <?php foreach ($categories as $category): ?>
                <a href="/dishes?categories=<?= htmlspecialchars(urlencode(strtolower($category['name']))) ?>"
                    class="block focus:outline-none focus:ring-4 focus:ring-amber-400 rounded-xl"
                    aria-label="View dishes in <?= htmlspecialchars($category['name']) ?> category">
                    <div
                        class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-lg transition duration-300">
                        <?php if ($category['image']): ?>
                            <img src="<?= htmlspecialchars($category['image']) ?>"
                                alt="<?= htmlspecialchars($category['name']) ?>"
                                class="w-full h-64 object-cover transform group-hover:scale-105 transition duration-500">
                        <?php else: ?>
                            <div
                                class="w-full h-64 flex items-center justify-center transform group-hover:scale-105 transition duration-500">
                                <i class="fa-solid fa-book-open text-6xl text-orange-500"></i>
                            </div>
                        <?php endif ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
                            <h3 class="text-white text-xl font-semibold"><?= htmlspecialchars($category['name']) ?></h3>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include BASE_PATH . '/src/includes/shape-divider.php' ?>
</section>

<!-- Local Cooks -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <?php if ($hasRatings): ?>
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Meet Top Rated Local Kitchens</h2>
            <?php else: ?>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Our Newest Local Kitchens</h2>
                <p class="text-sm text-gray-600 max-w-2xl mx-auto">No reviews yet — be the first to rate these kitchens!</p>
            <?php endif; ?>
        </div>

        <!-- Swiper -->
        <!-- <div class="relative max-w-6xl mx-auto"> -->
        <div class="swiper myKitchensSwiper relative max-w-6xl mx-auto">
            <div class="swiper-wrapper">
                <?php foreach ($kitchens as $kitchen): ?>
                    <div class="swiper-slide">
                        <a href="/kitchens/profile?view=<?= $kitchen['kitchen_id'] ?>" class="group">
                            <div
                                class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 border border-gray-100 hover:border-orange-100 h-[380px] flex flex-col">

                                <!-- Image -->
                                <div class="relative h-48 overflow-hidden">
                                    <img src="<?= htmlspecialchars($kitchen['kitchen_image']) ?>"
                                        alt="<?= htmlspecialchars($kitchen['name']) ?>"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </div>

                                <!-- Content -->
                                <div class="p-4 flex flex-col justify-between flex-grow">
                                    <div class="space-y-2">
                                        <!-- Name and Owner -->
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800 truncate">
                                                <?= htmlspecialchars($kitchen['name']) ?>
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

                                    <!-- Footer:Service Areas, Rating + Address -->
                                    <div class="">
                                        <!-- Service Areas -->
                                        <?php if (!empty($kitchen['service_areas'])): ?>
                                            <p class="text-xs text-gray-500 flex items-center line-clamp-1">
                                                <i class="fa-solid fa-person-biking text-orange-500 mr-1"></i>
                                                <?= htmlspecialchars($kitchen['service_areas']) ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="flex justify-between items-center mt-2">
                                            <!-- Rating -->
                                            <div class="flex items-center">
                                                <?php if ($kitchen['avg_rating']): ?>
                                                    <div class="flex items-center bg-orange-50 px-2 py-1 rounded-md">
                                                        <i class="fa-solid fa-star text-orange-400 mr-1 text-sm"></i>
                                                        <span class="text-sm font-medium text-gray-800">
                                                            <?= round($kitchen['avg_rating'], 1) ?>
                                                        </span>
                                                        <span class="text-xs text-gray-500 ml-1">
                                                            (<?= $kitchen['review_count'] ?>)
                                                        </span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded">
                                                        No ratings yet
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Address -->
                                            <?php if (!empty($kitchen['address'])): ?>
                                                <p class="text-xs text-gray-500 flex items-center max-w-[130px] text-right">
                                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                                    <span class="line-clamp-1"
                                                        title="<?= htmlspecialchars($kitchen['address']) ?>">
                                                        <?= htmlspecialchars($kitchen['address']) ?>
                                                    </span>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation -->
            <div class="swiper-button-prev !text-orange-500 -mt-12 !left-1 !top-1/2 !-translate-y-1/2 absolute z-10">
            </div>
            <div class="swiper-button-next !text-orange-500 -mt-12 !right-1 !top-1/2 !-translate-y-1/2 absolute z-10">
            </div>

            <!-- Pagination -->
            <div class="relative swiper-pagination mt-4"></div>
        </div>
        <!-- </div> -->

        <div class="text-center mt-2">
            <a href="/kitchens"
                class="inline-block bg-orange-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-orange-600 transition">
                View All Kitchens
            </a>
        </div>
    </div>
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
            <p class="text-gray-600">Thousands of satisfied customers across Bangladesh</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
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
        <?php if ($isLoggedIn): ?>
            <div class="max-w-2xl mx-auto mt-16 bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Share Your Experience</h3>
                <form action="/submit_platform_review.php" method="POST">
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Your Rating</label>
                    <select name="rating" id="rating" required
                        class="w-full mb-4 px-4 py-2 border rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select rating</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>

                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                    <textarea name="comment" id="comment" rows="4" required
                        class="w-full mb-4 px-4 py-2 border rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Write your experience with TiffinCraft..."></textarea>

                    <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-md font-medium text-sm">
                        Submit Review
                    </button>
                </form>
            </div>
        <?php endif ?>
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

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const swiper = new Swiper('.myKitchensSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
                1280: { slidesPerView: 4 },
            },
        });
    });

</script>

<?php

$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';

?>