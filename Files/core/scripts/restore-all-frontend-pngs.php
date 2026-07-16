<?php

/**
 * Restore all PNG assets under shape/ and images/frontend/ from original zip.
 */
$zipPath = 'C:/laragon/www/codecanyon-MNzPySlM-olance-global-freelancing-marketplace.zip';
$projectRoot = realpath(__DIR__ . '/../..');

$zip = new ZipArchive();
$zip->open($zipPath);

$prefixes = [
    'Files/assets/templates/basic/shape/',
    'Files/assets/images/frontend/',
];

$count = 0;
for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    if (!str_ends_with(strtolower($name), '.png')) {
        continue;
    }

    $matched = false;
    foreach ($prefixes as $prefix) {
        if (str_starts_with($name, $prefix)) {
            $matched = true;
            break;
        }
    }

    if (!$matched) {
        continue;
    }

    $contents = $zip->getFromIndex($i);
    $dest = $projectRoot . '/' . substr($name, strlen('Files/'));
    $dir = dirname($dest);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($dest, $contents);
    $count++;
}

$zip->close();
echo "Restored {$count} PNG files.\n";
