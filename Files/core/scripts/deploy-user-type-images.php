<?php

/**
 * Deploy user-provided worker photos and seed Core User Types CMS (Blueprint §4).
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srcDir = 'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets';
$assetsRoot = realpath(__DIR__ . '/../../assets');
$ts = time();

$files = [
    'c__Users_Administrator_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_pexels-wal_-172619-2156618639-37636256__1_-2d973e20-ebfa-4a63-b308-410f2df41cb6.png' => 'qm-workers-team.png',
    'c__Users_Administrator_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_pexels-james-frid-81279-901941-c098e248-976d-4342-9b41-42e7db0d570a.png' => 'qm-workers-site.png',
    'c__Users_Administrator_AppData_Roaming_Cursor_User_workspaceStorage_empty-window_images_zeebolos-worker-7043152_1920-acf6b59f-b1f3-466a-80e9-87b5377d6e0c.png' => 'qm-worker-portrait.png',
];

$deployed = [];
foreach ($files as $srcName => $destName) {
    $src = "{$srcDir}/{$srcName}";
    if (!is_file($src)) {
        echo "Missing: {$srcName}\n";
        continue;
    }

    foreach (['user_types', 'banner', 'find_task', 'account'] as $folder) {
        $dir = "{$assetsRoot}/images/frontend/{$folder}";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    $filename = str_replace('.png', "-{$ts}.png", $destName);
    copy($src, "{$assetsRoot}/images/frontend/user_types/{$filename}");
    $deployed[$destName] = $filename;
    echo "Deployed: {$filename}\n";
}

if (count($deployed) < 3) {
    echo "Not all images found.\n";
    exit(1);
}

$teamFile = $deployed['qm-workers-team.png'];
$siteFile = $deployed['qm-workers-site.png'];
$portraitFile = $deployed['qm-worker-portrait.png'];

copy("{$assetsRoot}/images/frontend/user_types/{$teamFile}", "{$assetsRoot}/images/frontend/banner/{$teamFile}");
copy("{$assetsRoot}/images/frontend/user_types/{$siteFile}", "{$assetsRoot}/images/frontend/find_task/{$siteFile}");
copy("{$assetsRoot}/images/frontend/user_types/{$portraitFile}", "{$assetsRoot}/images/frontend/account/{$portraitFile}");
copy("{$assetsRoot}/images/frontend/user_types/{$siteFile}", "{$assetsRoot}/images/frontend/account/qm-customer-{$ts}.png");

$customerAccountFile = "qm-customer-{$ts}.png";

// ── user_types CMS ───────────────────────────────────────────────────────────
$content = App\Models\Frontend::where('data_keys', 'user_types.content')->first();
if (!$content) {
    $content = new App\Models\Frontend();
    $content->data_keys = 'user_types.content';
}
$content->tempname = 'basic';
$content->data_values = [
    'heading' => 'Core User Types',
    'subheading' => 'QuoteMatch connects customers who need quotes with verified service providers — and gives admins full platform control.',
    'banner_image' => $teamFile,
];
$content->save();
echo "Saved user_types.content\n";

$elements = [
    [
        'label' => '4.1 Customer',
        'title' => 'Customer',
        'content' => 'Any person or business looking for a service, product, quote, or supplier. Post a requirement, compare quotes, and choose the best provider.',
        'examples' => 'Homeowner looking for a builder|Business needing a freight forwarder|Company seeking a packaging supplier|Importer needing customs clearance',
        'image' => $siteFile,
        'route_key' => 'customer',
        'button_text' => 'Get Free Quotes',
    ],
    [
        'label' => '4.2 Service Provider',
        'title' => 'Service Provider',
        'content' => 'Builders, tradespeople, freight forwarders, and suppliers who submit structured quotes for matching customer requests.',
        'examples' => 'Builder or kitchen fitter|Painter, plumber, or electrician|Freight forwarder or customs broker|Courier or warehouse provider',
        'image' => $portraitFile,
        'route_key' => 'provider',
        'button_text' => 'Join as Provider',
    ],
    [
        'label' => '4.3 Admin',
        'title' => 'Admin',
        'content' => 'The platform team approves providers, manages categories, monitors quotes, verifies documents, and keeps the marketplace trusted.',
        'examples' => 'Approve or reject providers|Manage categories & forms|Review disputes & reviews|View analytics & reports',
        'icon' => 'las la-user-shield',
        'route_key' => 'admin',
        'button_text' => '',
    ],
];

App\Models\Frontend::where('data_keys', 'user_types.element')->delete();

foreach ($elements as $fields) {
    $row = new App\Models\Frontend();
    $row->data_keys = 'user_types.element';
    $row->tempname = 'basic';
    $row->data_values = $fields;
    $row->save();
    echo "Saved user_types.element: {$fields['title']}\n";
}

// ── Banner hero — team photo ─────────────────────────────────────────────────
$banner = App\Models\Frontend::where('data_keys', 'banner.content')->first();
if ($banner) {
    $data = (array) $banner->data_values;
    $data['image'] = $teamFile;
    $data['heading'] = 'Compare Quotes from Trusted Providers';
    $data['subheading'] = 'Post your requirement and receive multiple quotes from verified builders and freight forwarders.';
    $data['feature_one'] = 'Verified Providers';
    $data['feature_two'] = 'Free to Post';
    $data['feature_three'] = 'Compare Quotes';
    $banner->data_values = $data;
    $banner->save();
    echo "Updated banner hero image\n";
}

// ── Find task — site workers ─────────────────────────────────────────────────
$findTask = App\Models\Frontend::where('data_keys', 'find_task.content')->first();
if ($findTask) {
    $data = (array) $findTask->data_values;
    $data['image'] = $siteFile;
    $data['heading'] = 'Builders & Trades at Work';
    $data['subheading'] = 'From extensions and kitchen fitting to freight and logistics — post your requirement and compare quotes from verified providers.';
    $findTask->data_values = $data;
    $findTask->save();
    echo "Updated find_task image\n";
}

// ── Account section — provider + customer photos ───────────────────────────
$account = App\Models\Frontend::where('data_keys', 'account.content')->first();
if ($account) {
    $data = (array) $account->data_values;
    $data['freelancer_title'] = 'Join as a Service Provider';
    $data['freelancer_content'] = 'Register as a builder, tradesperson, or freight forwarder. Get verified and receive matching customer requests.';
    $data['freelancer_button_name'] = 'Register as Provider';
    $data['buyer_title'] = 'Post as a Customer';
    $data['buyer_content'] = 'Post your building or logistics requirement for free. Compare quotes and choose with confidence.';
    $data['buyer_button_name'] = 'Get Free Quotes';
    $data['freelancer'] = $portraitFile;
    $data['buyer'] = $customerAccountFile;
    $account->data_values = $data;
    $account->save();
    echo "Updated account section images\n";
}

// ── Homepage: add user_types after how_work ───────────────────────────────────
$page = App\Models\Page::where('slug', '/')->first();
if ($page) {
    $secs = json_decode($page->secs, true) ?: [];
    if (!in_array('user_types', $secs, true)) {
        $index = array_search('how_work', $secs, true);
        if ($index === false) {
            array_unshift($secs, 'user_types');
        } else {
            array_splice($secs, $index + 1, 0, ['user_types']);
        }
        $page->secs = json_encode(array_values($secs));
        $page->save();
        echo 'Homepage sections: ' . $page->secs . "\n";
    }
}

Illuminate\Support\Facades\Cache::flush();
echo "Done.\n";
