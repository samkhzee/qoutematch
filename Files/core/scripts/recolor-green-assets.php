<?php

/**
 * Recolor green pixels to blue and fix checkerboard backgrounds in PNG assets.
 */
require __DIR__ . '/../vendor/autoload.php';

$filesRoot = realpath(__DIR__ . '/../../assets');
$shapeDir = $filesRoot . '/templates/basic/shape';
$frontendDir = $filesRoot . '/images/frontend';

function processPng(string $path, bool $stripCheckerboard = false): bool
{
    if (!is_file($path) || !function_exists('imagecreatefrompng')) {
        return false;
    }

    $img = @imagecreatefrompng($path);
    if (!$img) {
        return false;
    }

    imagesavealpha($img, true);
    imagealphablending($img, false);

    $width = imagesx($img);
    $height = imagesy($img);
    $blue = imagecolorallocatealpha($img, 37, 99, 235, 0); // #2563eb

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgba = imagecolorat($img, $x, $y);
            $a = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;

            if ($stripCheckerboard) {
                // Light gray checkerboard / near-white backdrop → transparent
                $isGray = abs($r - $g) < 12 && abs($g - $b) < 12 && $r > 180 && $r < 245;
                if ($isGray) {
                    imagesetpixel($img, $x, $y, imagecolorallocatealpha($img, 0, 0, 0, 127));
                    continue;
                }
            }

            // Green-ish pixels → blue
            if ($g > 90 && $g > $r + 25 && $g > $b + 25) {
                $newAlpha = 127 - (int) round((255 - $a) * (255 - $a) / 255);
                $newAlpha = max(0, min(127, $newAlpha));
                $col = imagecolorallocatealpha($img, 37, 99, 235, $newAlpha);
                imagesetpixel($img, $x, $y, $col);
            }
        }
    }

    imagepng($img, $path);
    imagedestroy($img);

    return true;
}

function copyIfExists(string $src, string $dest): void
{
    if (is_file($src)) {
        @copy($src, $dest);
        echo "Copied: {$dest}\n";
    }
}

$cursorAssets = 'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets';

// Replace key template shapes with generated blue versions
copyIfExists("{$cursorAssets}/how-work-arrow-blue.png", "{$shapeDir}/how-work.png");
copyIfExists("{$cursorAssets}/banner-shape-blue.png", "{$filesRoot}/images/frontend/banner/banner-shape-blue.png");

// Batch recolor all PNGs under assets/templates/basic/shape and assets/images/frontend
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($filesRoot));
$count = 0;
foreach ($iterator as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'png') {
        continue;
    }
    $path = $file->getPathname();
    $isHero = str_contains($path, 'quotematch-hero');
    if (processPng($path, $stripCheckerboard = $isHero)) {
        $count++;
    }
}

echo "Processed {$count} PNG files for green→blue recolor.\n";
