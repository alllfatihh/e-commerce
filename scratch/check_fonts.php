<?php

foreach (glob('resources/views/*.blade.php') as $f) {
    $lines = file($f);
    foreach ($lines as $num => $line) {
        if (stripos($line, 'font-family') !== false || stripos($line, 'fontFamily') !== false) {
            echo "$f:".($num + 1).': '.trim($line).PHP_EOL;
        }
    }
}
