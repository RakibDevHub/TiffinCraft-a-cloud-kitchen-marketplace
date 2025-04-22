<?php include '../src/includes/header.php'; ?>

<!-- Hero Section -->
<section
    class="relative h-screen flex items-center justify-center bg-cover bg-center bg-[url(/assets/images/HeroBG.jpeg)]">
    <div class="absolute inset-0 bg-black bg-opacity-55"></div>
    <h1 class="relative text-white text-5xl font-extrabold text-center px-4">Welcome to TiffinCraft</h1>
    <!-- <div class="cShape layer"></div> -->
    <div
        class="w-full aspect-[960/300] bg-no-repeat bg-center bg-cover bg-[url('../assets/images/layer3.svg')] absolute bottom-0 h-auto -mb-4">
    </div>


</section>

<section class="bg-orange-50 py-16 relative">
    <div class="container mx-auto text-center z-10 relative">
        <h2 class="text-3xl font-bold text-blue-800 mb-4">Explore Homemade Dishes</h2>
        <p class="text-gray-600">Fresh. Local. Delicious.</p>
    </div>

    <!-- Shape Divider -->
    <?php
    $direction = 'bottom';
    // $color = 'white';
    include '../src/includes/shape-divider.php'; ?>

</section>

<?php include '../src/includes/footer.php' ?>