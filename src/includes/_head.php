<?php
$pageTitle = $pageTitle ?? 'TiffinCraft';
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>

<meta name="description" content="TiffinCraft – Authentic home-cooked meals from local chefs.">
<meta name="author" content="TiffinCraft">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:image" content="/assets/images/og-image.jpg">
<!-- <meta property="og:image" content="https://tiffincraft.local/assets/images/og-image.jpg"> -->

<link rel="icon" href="/favicon.ico" type="image/x-icon">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Tailwind & Custom Theme -->
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#fefce8',
                        100: '#fef9c3',
                        200: '#fef08a',
                        300: '#fde047',
                        400: '#facc15',
                        500: '#eab308',
                        600: '#ca8a04',
                        700: '#a16207',
                        800: '#854d0e',
                        900: '#713f12',
                    }
                }
            }
        }
    }
</script>

<!-- Your CSS -->
<link rel="stylesheet" href="/assets/css/style.css">