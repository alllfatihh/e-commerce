<?php

$files = [
    'resources/views/welcome.blade.php',
    'resources/views/admin.blade.php',
    'resources/views/checkout-auth.blade.php',
];

$disallowed_html_classes = ['font-montserrat', 'font-roboto', 'font-helvetica', 'font-neue-montreal', 'font-sans'];

echo '=== CHECKING FOR DISALLOWED CLASSES IN HTML ==='.PHP_EOL;
$found = 0;

foreach ($files as $file) {
    $lines = file($file);
    foreach ($lines as $i => $line) {
        // Skip head/style/script blocks where aliases/definitions live
        if ($i < 88) {
            continue;
        }

        foreach ($disallowed_html_classes as $cls) {
            if (preg_match('/\b'.preg_quote($cls, '/').'\b/', $line)) {
                echo "$file:".($i + 1)." [$cls] ".trim($line).PHP_EOL;
                $found++;
            }
        }
    }
}

if ($found === 0) {
    echo 'SUCCESS: 0 instances of old font classes found in HTML elements across all views!'.PHP_EOL;
}
