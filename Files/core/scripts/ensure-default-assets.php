<?php

/**
 * Create missing default public assets (avatar, default image, sidebar shape).
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$assetsRoot = realpath(__DIR__ . '/../../assets');
if (!$assetsRoot) {
    echo "Assets root not found\n";
    exit(1);
}

function ensureDir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

function writePlaceholderPng(string $path, int $r, int $g, int $b): void
{
    if (is_file($path)) {
        echo "Exists: {$path}\n";
        return;
    }

    ensureDir(dirname($path));
    $image = imagecreatetruecolor(200, 200);
    $color = imagecolorallocate($image, $r, $g, $b);
    imagefill($image, 0, 0, $color);
    imagepng($image, $path);
    imagedestroy($image);
    echo "Created: {$path}\n";
}

writePlaceholderPng("{$assetsRoot}/images/user/avatar.png", 37, 99, 235);
writePlaceholderPng("{$assetsRoot}/images/default.png", 226, 232, 240);
writePlaceholderPng("{$assetsRoot}/templates/basic/shape/d-shape.png", 37, 99, 235);

function removeNearWhiteBackground(string $path, int $threshold = 245): bool
{
    if (!is_file($path)) {
        return false;
    }

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $image = match ($extension) {
        'jpg', 'jpeg' => @imagecreatefromjpeg($path),
        'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
        default => @imagecreatefrompng($path),
    };

    if (!$image) {
        echo "Skip (unreadable): {$path}\n";
        return false;
    }

    imagesavealpha($image, true);
    imagealphablending($image, false);

    $width = imagesx($image);
    $height = imagesy($image);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($image, $x, $y);
            $alpha = ($rgb & 0x7F000000) >> 24;
            if ($alpha >= 120) {
                continue;
            }

            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            if ($r >= $threshold && $g >= $threshold && $b >= $threshold) {
                imagesetpixel($image, $x, $y, $transparent);
            }
        }
    }

    imagepng($image, $path);
    imagedestroy($image);
    echo "Transparent background: {$path}\n";

    return true;
}

$logoDir = "{$assetsRoot}/images/logo_icon";
ensureDir($logoDir);
if (!is_file("{$logoDir}/logo.png")) {
    copy("{$assetsRoot}/images/user/avatar.png", "{$logoDir}/logo.png");
    echo "Created: {$logoDir}/logo.png\n";
}

$logoDarkPath = "{$logoDir}/logo_dark.png";
if (!is_file($logoDarkPath) && is_file("{$logoDir}/logo.png")) {
    copy("{$logoDir}/logo.png", $logoDarkPath);
    echo "Created: {$logoDarkPath}\n";
}

if (is_file($logoDarkPath)) {
    removeNearWhiteBackground($logoDarkPath);
}

$logoPath = "{$logoDir}/logo.png";
if (is_file($logoPath)) {
    removeNearWhiteBackground($logoPath);
}

echo "Default assets ready.\n";
