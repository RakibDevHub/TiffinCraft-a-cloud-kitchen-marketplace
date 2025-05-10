<?php
$color = $color ?? ' #ffffff';
$rotate = $rotate ?? ' rotate-180';
$bottom = $bottom ?? ' bottom-[-64px]';
$bg = $bg ?? ' ';
?>

<div class="relative left-0 w-full overflow-hidden leading-[0] <?= $bottom,
    $rotate ?>">
    <svg class="relative block w-[calc(100%+1.3px)] h-24 <?= $bg ?>" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="<?= $color ?>"
            d="M0,224L48,208C96,192,192,160,288,154.7C384,149,480,171,576,186.7C672,203,768,213,864,192C960,171,1056,117,1152,112C1248,107,1344,149,1392,170.7L1440,192V0H0Z" />
    </svg>
</div>