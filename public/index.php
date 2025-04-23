<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TiffinCraft - Homemade Meals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans">
    <?php include '../src/includes/header.php'; ?>

    <!-- Hero Section -->
    <section
        class="relative h-screen flex items-center justify-center bg-cover bg-center bg-[url(/assets/images/HeroBG.jpeg)]">
        <div class="absolute inset-0 bg-black bg-opacity-55"></div>
        <div class="relative text-center px-4">
            <h1 class="text-white text-5xl font-extrabold mb-4">Welcome to TiffinCraft</h1>
            <p class="text-white text-xl mb-6">Discover Homemade Tiffins Near You</p>
            <a href="#explore"
                class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition">Browse
                Tiffins</a>
        </div>
        <div
            class="w-full aspect-[960/300] bg-no-repeat bg-center bg-cover bg-[url('../assets/images/layer3.svg')] absolute bottom-0 h-auto -mb-4">
        </div>
    </section>

    <!-- Explore Dishes -->
    <section id="explore" class="bg-orange-50 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">Explore Homemade Dishes</h2>
            <p class="text-gray-600 mb-8">Fresh. Local. Delicious.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <img src="/assets/images/category-rice.jpeg" alt="Rice Meals"
                        class="rounded-md h-40 w-full object-cover mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Rice Meals</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <img src="/assets/images/category-roti.jpeg" alt="Roti Combos"
                        class="rounded-md h-40 w-full object-cover mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Roti Combos</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <img src="/assets/images/category-snacks.jpeg" alt="Snacks"
                        class="rounded-md h-40 w-full object-cover mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Snacks</h3>
                </div>
            </div>
        </div>
        <!-- Shape Divider -->
        <?php include '../src/components/shape-divider.php'; ?>
    </section>

    <!-- How It Works -->
    <section class="bg-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">How It Works</h2>
            <p class="text-gray-600 mb-10">Simple steps to enjoy homemade food</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div>
                    <div class="text-orange-500 text-4xl font-bold mb-2">1</div>
                    <h3 class="text-lg font-semibold mb-2">Browse Tiffins</h3>
                    <p class="text-gray-600">Explore a variety of home-cooked meals from local sellers.</p>
                </div>
                <div>
                    <div class="text-orange-500 text-4xl font-bold mb-2">2</div>
                    <h3 class="text-lg font-semibold mb-2">Place Your Order</h3>
                    <p class="text-gray-600">Choose your favorite dish and place the order in a few clicks.</p>
                </div>
                <div>
                    <div class="text-orange-500 text-4xl font-bold mb-2">3</div>
                    <h3 class="text-lg font-semibold mb-2">Enjoy the Meal</h3>
                    <p class="text-gray-600">Get fresh food delivered to your door and enjoy every bite.</p>
                </div>
            </div>
        </div>
        <!-- Shape Divider -->
        <?php $color = '#fff7ed';
        include '../src/components/shape-divider.php'; ?>
    </section>

    <!-- Featured Sellers -->
    <section class="bg-orange-50 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">Featured Tiffins</h2>
            <p class="text-gray-600 mb-10">From our trusted home chefs</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <!-- Card Example -->
                <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <img src="/assets/images/featured-dish.jpg" alt="Dish" class="w-full h-40 object-cover">
                    <div class="p-4 text-left">
                        <h3 class="font-semibold text-lg text-gray-800">Paneer Masala Combo</h3>
                        <p class="text-sm text-gray-500">by Rina's Kitchen</p>
                        <p class="text-orange-500 font-semibold mt-2">₹150</p>
                    </div>
                </div>
                <!-- Repeat more cards -->
            </div>
        </div>
        <!-- Shape Divider -->
        <?php $color = "#ffffff";
        include '../src/components/shape-divider.php'; ?>
    </section>

    <!-- Why Choose Us -->
    <section class="bg-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose TiffinCraft?</h2>
            <p class="text-gray-600 mb-6 max-w-3xl mx-auto">We connect you with passionate home chefs offering fresh,
                hygienic, and affordable meals made with love. Support local and enjoy food that feels like home.</p>
        </div>
        <!-- Shape Divider -->
        <?php $color = '#fff7ed';
        include '../src/components/shape-divider.php'; ?>
    </section>

    <!-- CTA Footer Section -->
    <section class="bg-orange-500 py-12 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ready to taste the best homemade food?</h2>
        <a href="#explore"
            class="bg-white text-orange-500 font-semibold py-3 px-6 rounded-lg transition hover:bg-gray-100">Start
            Ordering Now</a>
    </section>

    <?php include '../src/includes/footer.php' ?>
</body>

</html>