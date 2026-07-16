<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$filesRoot = realpath(__DIR__ . '/../../assets');
$cursorAssets = 'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets';
$shapeDir = $filesRoot . '/templates/basic/shape';

function deployCopy(string $src, string $dest): void
{
    if (!is_file($src)) {
        echo "Missing: {$src}\n";
        return;
    }
    @mkdir(dirname($dest), 0777, true);
    copy($src, $dest);
    echo "Deployed: {$dest}\n";
}

function processPng(string $path, bool $stripCheckerboard = false): bool
{
    if (!is_file($path)) {
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

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $rgba = imagecolorat($img, $x, $y);
            $a = ($rgba >> 24) & 0x7F;
            $r = ($rgba >> 16) & 0xFF;
            $g = ($rgba >> 8) & 0xFF;
            $b = $rgba & 0xFF;

            if ($stripCheckerboard) {
                $isGray = abs($r - $g) < 15 && abs($g - $b) < 15 && $r > 175 && $r < 250;
                if ($isGray) {
                    imagesetpixel($img, $x, $y, imagecolorallocatealpha($img, 0, 0, 0, 127));
                    continue;
                }
            }

            if ($g > 85 && $g > $r + 20 && $g > $b + 20) {
                $opacity = 255 - $a;
                $newAlpha = max(0, min(127, 127 - (int) round($opacity * 127 / 255)));
                imagesetpixel($img, $x, $y, imagecolorallocatealpha($img, 37, 99, 235, $newAlpha));
            }
        }
    }

    imagepng($img, $path);
    imagedestroy($img);

    return true;
}

// --- Deploy generated blue assets ---
deployCopy("{$cursorAssets}/how-work-arrow-blue.png", "{$shapeDir}/how-work.png");
deployCopy("{$cursorAssets}/banner-shape-blue.png", "{$shapeDir}/banner.png");

$bannerShapeName = 'banner-shape-blue-' . time() . '.png';
deployCopy("{$cursorAssets}/banner-shape-blue.png", "{$filesRoot}/images/frontend/banner/{$bannerShapeName}");

$heroName = 'quotematch-hero-v2-' . time() . '.png';
deployCopy("{$cursorAssets}/hero-person-cutout.png", "{$filesRoot}/images/frontend/banner/{$heroName}");

$subscribeName = 'subscribe-blue-' . time() . '.png';
deployCopy("{$cursorAssets}/subscribe-newsletter-blue.png", "{$filesRoot}/images/frontend/subscribe/{$subscribeName}");

$findTaskName = 'find-task-blue-' . time() . '.png';
deployCopy("{$cursorAssets}/find-task-blue.png", "{$filesRoot}/images/frontend/find_task/{$findTaskName}");

// Replace subscribe decorative shape with blue banner shape variant
$subscribeShapeName = 'subscribe-shape-blue-' . time() . '.png';
deployCopy("{$cursorAssets}/banner-shape-blue.png", "{$filesRoot}/images/frontend/subscribe/{$subscribeShapeName}");
deployCopy("{$cursorAssets}/banner-shape-blue.png", "{$shapeDir}/subscribe.png");

// --- Update CMS ---
$banner = App\Models\Frontend::where('data_keys', 'banner.content')->first();
if ($banner) {
    $data = (array) $banner->data_values;
    $data['shape'] = $bannerShapeName;
    $data['image'] = $heroName;
    $banner->data_values = $data;
    $banner->save();
}

$subscribe = App\Models\Frontend::where('data_keys', 'subscribe.content')->first();
if ($subscribe) {
    $data = (array) $subscribe->data_values;
    $data['image'] = $subscribeName;
    $data['shape'] = $subscribeShapeName;
    $subscribe->data_values = $data;
    $subscribe->save();
}

$findTask = App\Models\Frontend::where('data_keys', 'find_task.content')->first();
if ($findTask) {
    $data = (array) $findTask->data_values;
    $data['image'] = $findTaskName;
    $findTask->data_values = $data;
    $findTask->save();
}

// --- Recolor remaining PNG assets site-wide ---
$count = 0;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($filesRoot));
foreach ($iterator as $file) {
    if (!$file->isFile() || strtolower($file->getExtension()) !== 'png') {
        continue;
    }
    $path = $file->getPathname();
    if (str_contains($path, 'logo_icon')) {
        continue; // keep logo colors
    }
    $strip = str_contains($path, 'banner') || str_contains($path, 'hero');
    if (processPng($path, $strip)) {
        $count++;
    }
}

Illuminate\Support\Facades\Cache::forget('GeneralSetting');
echo "Recolored {$count} PNG files.\nDone.\n";
