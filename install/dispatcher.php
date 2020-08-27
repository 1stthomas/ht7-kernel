<?php

if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'vendor')) {
    $dirVendor = __DIR__ . DIRECTORY_SEPARATOR . 'vendor';
} elseif (file_exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor')) {
    $dirVendor = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor';
} else {
    throw new RuntimeException('Missing valid vendor folder.');
}
/**
 * Setup the vendor autoloader.
 */
$loader = require $dirVendor . DIRECTORY_SEPARATOR . 'autoload.php';

use \Ht7\Kernel\Kernel;

$kernel = new Kernel(__DIR__, $dirVendor);

unset($dirVendor);

echo "<hr>";
echo "<p>";
echo "status: " . $kernel->getStatus();
echo "</p>";
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

$c = getC();

if (is_object($c)) {
    echo "<h1>container im dispatcher.php vorhanden</h1>";
    echo "<h3>class: " . get_class($c) . "</h3>";
    echo "<pre>";
    print_r($c);
    echo "</pre>";
}

echo "<hr>";
printStructure($_ENV);
echo "<hr>";
if (isset($argc)) {
    printLine('Anzahl Argumente: ' . $argc);
    printStructure($argv);
}

getCNew();

echo "<hr>";
echo "<h4>Stats:</h4>";
echo "Memory Peak Usage [B]: " . number_format((float) memory_get_peak_usage(), 0, '.', '\'') . "<br>";
echo "Memory Peak Usage [B]: " . number_format((float) memory_get_peak_usage(true), 0, '.', '\'') . "<br>";
echo "Memory Usage [B]: " . number_format((float) memory_get_usage(), 0, '.', '\'') . "<br>";
echo "Memory Usage [B]: " . number_format((float) memory_get_usage(true), 0, '.', '\'') . "<br>";
