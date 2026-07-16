<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$projectAssets = dirname(__DIR__, 2) . '/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets';
$coreAssets = dirname(__DIR__) . '/../assets/images';

// Fallback if cursor assets path differs
$candidates = [
    $projectAssets,
    'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets',
];

$srcDir = null;
foreach ($candidates as $dir) {
    if (is_file($dir . '/quotematch-logo.png')) {
        $srcDir = $dir;
        break;
    }
}

if (!$srcDir) {
    echo "Source assets not found\n";
    exit(1);
}

$logoDir = $coreAssets . '/logo_icon';
$bannerDir = $coreAssets . '/frontend/banner';
@mkdir($logoDir, 0777, true);
@mkdir($bannerDir, 0777, true);

copy($srcDir . '/quotematch-logo.png', $logoDir . '/logo.png');
copy($srcDir . '/quotematch-logo.png', $logoDir . '/logo_dark.png');
copy($srcDir . '/quotematch-favicon.png', $logoDir . '/favicon.png');

$bannerFilename = 'quotematch-hero-' . time() . '.png';
copy($srcDir . '/quotematch-hero-banner.png', $bannerDir . '/' . $bannerFilename);

$general = App\Models\GeneralSetting::first();
$general->site_name = 'QuoteMatch';
$general->base_color = '2563eb';
$general->secondary_color = '1e3a8a';
$general->save();

$banner = App\Models\Frontend::where('data_keys', 'banner.content')->first();
if ($banner) {
    $data = (array) $banner->data_values;
    $data['image'] = $bannerFilename;
    $banner->data_values = $data;
    $banner->save();
    echo "Banner image updated to: {$bannerFilename}\n";
}

Illuminate\Support\Facades\Cache::forget('GeneralSetting');

echo "Logo deployed to: {$logoDir}\n";
echo "Site name: {$general->site_name}\n";
echo "Done.\n";
