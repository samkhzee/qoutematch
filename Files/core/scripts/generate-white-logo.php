<?php

/**
 * Builds logo_dark.png — white QuoteMatch mark on transparent background.
 */
$source = __DIR__ . '/../../assets/images/logo_icon/logo.png';
$output = __DIR__ . '/../../assets/images/logo_icon/logo_dark.png';

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension is required.\n");
    exit(1);
}

if (!file_exists($source)) {
    fwrite(STDERR, "Source logo not found: {$source}\n");
    exit(1);
}

$sourceImage = imagecreatefrompng($source);
if (!$sourceImage) {
    fwrite(STDERR, "Unable to read source logo.\n");
    exit(1);
}

$width = imagesx($sourceImage);
$height = imagesy($sourceImage);

$outputImage = imagecreatetruecolor($width, $height);
if (!$outputImage) {
    fwrite(STDERR, "Unable to create output image.\n");
    exit(1);
}

imagesavealpha($outputImage, true);
imagealphablending($outputImage, false);

$transparent = imagecolorallocatealpha($outputImage, 0, 0, 0, 127);
$white = imagecolorallocatealpha($outputImage, 255, 255, 255, 0);

imagefilledrectangle($outputImage, 0, 0, $width, $height, $transparent);

$isBackground = static function (int $red, int $green, int $blue, int $alpha): bool {
    if ($alpha > 100) {
        return true;
    }

    return ($red + $green + $blue) < 40;
};

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $color = imagecolorat($sourceImage, $x, $y);
        $alpha = ($color >> 24) & 0x7F;
        $red = ($color >> 16) & 0xFF;
        $green = ($color >> 8) & 0xFF;
        $blue = $color & 0xFF;

        if ($isBackground($red, $green, $blue, $alpha)) {
            imagesetpixel($outputImage, $x, $y, $transparent);
            continue;
        }

        // Keep inner check / quote marks visible as transparent cutouts.
        if (($red + $green + $blue) < 80) {
            imagesetpixel($outputImage, $x, $y, $transparent);
            continue;
        }

        imagesetpixel($outputImage, $x, $y, $white);
    }
}

imagepng($outputImage, $output, 6);
imagedestroy($sourceImage);
imagedestroy($outputImage);

echo "White transparent logo saved: {$output}\n";
