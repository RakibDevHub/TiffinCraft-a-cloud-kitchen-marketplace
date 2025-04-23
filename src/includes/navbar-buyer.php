<!-- CTA Bar -->
<div id="ctaBar"
    class="fixed top-0 left-0 w-full bg-orange-100 text-orange-800 z-50 flex items-center justify-between px-4 py-2 shadow transition-all duration-300 transform -translate-y-full opacity-0">
    <div>
        <span class="font-semibold">Own a Tiffin Business?</span>
        <a href="/business" class="ml-2 text-orange-700 underline hover:text-orange-900">Join TiffinCraft Business</a>
    </div>
    <button id="closeCta" class="ml-4 text-orange-800 hover:text-orange-900 text-xl font-bold">&times;</button>
</div>

<!-- Fixed Navbar -->
<nav id="mainNav" class="fixed top-0 left-0 w-full bg-white shadow z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/index.php" class="text-xl font-bold text-gray-800">TiffinCraft</a>
        <ul class="flex space-x-6 text-gray-600 font-medium">
            <li><a href="/auth/login.php" class="hover:underline">Login</a></li>
            <li><a href="/auth/register.php" class="rounded  hover:bg-orange-400 hover:text-white">Register</a></li>
            <!-- <li><a href="#" class="hover:text-gray-900">Services</a></li>
            <li><a href="#" class="hover:text-gray-900">Contact</a></li> -->
        </ul>
    </div>
</nav>

<script>
    const ctaBar = document.getElementById('ctaBar');
    const closeCta = document.getElementById('closeCta');
    const mainNav = document.getElementById('mainNav');

    let ctaClosed = false;
    let ctaVisible = false;

    const showCTA = () => {
        ctaBar.classList.remove('-translate-y-full', 'opacity-0');
        ctaBar.classList.add('translate-y-0', 'opacity-100');
        mainNav.style.top = `${ctaBar.offsetHeight}px`;
        ctaVisible = true;
    };

    const hideCTA = () => {
        ctaBar.classList.add('-translate-y-full', 'opacity-0');
        ctaBar.classList.remove('translate-y-0', 'opacity-100');
        mainNav.style.top = '0';
        ctaVisible = false;
    };

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50 && !ctaClosed && !ctaVisible) {
            showCTA();
        } else if (window.scrollY <= 50 && !ctaClosed && ctaVisible) {
            hideCTA();
        }
    });

    closeCta.addEventListener('click', () => {
        ctaClosed = true;
        hideCTA();
    });
</script>