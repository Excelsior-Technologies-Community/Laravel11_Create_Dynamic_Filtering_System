<?php
require 'vendor/autoload.php';
$classmap = require 'vendor/composer/autoload_classmap.php';
$file = $classmap['Spatie\Activitylog\Models\Concerns\LogsActivity'];
echo "Loading: $file\n";
try {
    require $file;
    echo "Loaded without error\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
echo "Trait exists: " . (trait_exists('Spatie\Activitylog\Models\Concerns\LogsActivity') ? 'yes' : 'no') . "\n";
