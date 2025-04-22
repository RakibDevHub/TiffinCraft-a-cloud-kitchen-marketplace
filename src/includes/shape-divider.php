<!-- shape-divider.php -->
<?php
$direction = $direction ?? 'bottom'; // default to bottom
$rotationClass = $direction === 'top' ? 'rotate-180' : 'bottom-[-64px]';
$color = $color ?? '#fff7ed';
$colorClass = $color === 'white' ? '#ffffff' : '';
?>

<div class="absolute left-0 w-full overflow-hidden leading-[0] <?php echo $rotationClass ?>">
    <svg class="relative block w-[calc(100%+1.3px)] h-24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
        preserveAspectRatio="none">
        <path fill=<?php echo $color ?>
            d="M0,224L48,208C96,192,192,160,288,154.7C384,149,480,171,576,186.7C672,203,768,213,864,192C960,171,1056,117,1152,112C1248,107,1344,149,1392,170.7L1440,192V0H0Z" />
    </svg>
</div>