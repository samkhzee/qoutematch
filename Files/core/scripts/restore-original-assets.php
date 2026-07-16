<?php

/**
 * Restore corrupted homepage assets from the original CodeCanyon zip.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$zipPath = 'C:/laragon/www/codecanyon-MNzPySlM-olance-global-freelancing-marketplace.zip';
$projectRoot = realpath(__DIR__ . '/../..');

if (!is_file($zipPath)) {
    echo "Zip not found: {$zipPath}\n";
    exit(1);
}

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    echo "Cannot open zip\n";
    exit(1);
}

$assets = [
    'Files/assets/templates/basic/shape/how-work.png',
    'Files/assets/templates/basic/shape/subscribe.png',
    'Files/assets/templates/basic/shape/banner.png',
    'Files/assets/templates/basic/shape/subs-2.png',
    'Files/assets/images/frontend/banner/673af8b35ae361731918003.png',
    'Files/assets/images/frontend/banner/67d92c14906c01742285844.png',
    'Files/assets/images/frontend/subscribe/67d9340c2ee7b1742287884.png',
    'Files/assets/images/frontend/subscribe/673b4178ca7a71731936632.png',
    'Files/assets/images/frontend/find_task/67d930681c42a1742286952.png',
    'Files/assets/images/frontend/find_task/673b2b970126d1731931031.png',
];

foreach ($assets as $entry) {
    $contents = $zip->getFromName($entry);
    if ($contents === false) {
        echo "Missing in zip: {$entry}\n";
        continue;
    }

    $dest = $projectRoot . '/' . substr($entry, strlen('Files/'));
    $dir = dirname($dest);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($dest, $contents);
    echo "Restored: {$dest}\n";
}

$zip->close();

// Revert CMS image references to original filenames.
$updates = [
    'banner.content' => [
        'image' => '67d92c14906c01742285844.png',
        'shape' => '673af8b35ae361731918003.png',
    ],
    'subscribe.content' => [
        'image' => '67d9340c2ee7b1742287884.png',
        'shape' => '673b4178ca7a71731936632.png',
    ],
    'find_task.content' => [
        'image' => '67d930681c42a1742286952.png',
        'shape' => '673b2b970126d1731931031.png',
    ],
];

foreach ($updates as $key => $fields) {
    $row = App\Models\Frontend::where('data_keys', $key)->first();
    if (!$row) {
        echo "CMS row missing: {$key}\n";
        continue;
    }

    $data = (array) $row->data_values;
    foreach ($fields as $field => $value) {
        $data[$field] = $value;
    }

    $row->data_values = $data;
    $row->save();
    echo "Updated CMS: {$key}\n";
}

// Keep blue theme colors in general settings (already applied).
Illuminate\Support\Facades\Cache::flush();
echo "Done. Cache cleared.\n";
