<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$banner = App\Models\Frontend::where('data_keys', 'banner.content')->first();
$data = (array) $banner->data_values;
$heroFile = $data['image'] ?? null;
$path = realpath(__DIR__ . '/../../assets/images/frontend/banner/' . $heroFile);

if (!$path || !is_file($path)) {
    echo "Hero not found\n";
    exit(1);
}

$img = imagecreatefrompng($path);
imagesavealpha($img, true);
imagealphablending($img, false);

$w = imagesx($img);
$h = imagesy($img);

for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $rgba = imagecolorat($img, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        // White / near-white backdrop → fully transparent
        if ($r > 240 && $g > 240 && $b > 240) {
            imagesetpixel($img, $x, $y, imagecolorallocatealpha($img, 0, 0, 0, 127));
        }
    }
}

imagepng($img, $path);
imagedestroy($img);

echo "Hero background made transparent: {$path}\n";
