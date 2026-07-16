<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$general = App\Models\GeneralSetting::first();
if (!$general) {
    echo "No general settings found\n";
    exit(1);
}

echo "Before: {$general->site_name} | base={$general->base_color} | secondary={$general->secondary_color}\n";

$general->site_name = 'QuoteMatch';
$general->base_color = '2563eb';
$general->secondary_color = '1e3a8a';
$general->save();

Illuminate\Support\Facades\Cache::forget('GeneralSetting');

echo "After: {$general->site_name} | base={$general->base_color} | secondary={$general->secondary_color}\n";

// Update CMS content mentioning Olance where easy to find
$updates = [
    ['facility', 'heading', "Why Choose QuoteMatch"],
    ['facility', 'subheading', 'Discover the benefits of using QuoteMatch for comparing builders, freight forwarders, and verified service providers.'],
    ['banner', 'heading', 'Compare Quotes from Trusted Providers'],
    ['banner', 'subheading', 'Post your requirement and receive multiple quotes from verified builders and freight forwarders.'],
    ['banner', 'subtitle', 'Trusted by 1000+ Businesses'],
    ['find_task', 'heading', 'Find the Right Service for Your Needs'],
    ['find_task', 'subheading', 'Unlock value by comparing quotes from verified providers. Post your job, compare prices, and choose with confidence.'],
];

foreach ($updates as [$section, $field, $value]) {
    $content = App\Models\Frontend::where('data_keys', $section . '.content')->first();
    if (!$content) {
        continue;
    }
    $data = (array) $content->data_values;
    $data[$field] = $value;
    $content->data_values = $data;
    $content->save();
    echo "Updated frontend: {$section}.{$field}\n";
}

// SEO defaults if stored in frontend seo
$seo = App\Models\Frontend::where('data_keys', 'seo.data')->first();
if ($seo) {
    $data = (array) $seo->data_values;
    if (isset($data['social_title'])) {
        $data['social_title'] = str_ireplace('Olance', 'QuoteMatch', $data['social_title']);
    }
    if (isset($data['description'])) {
        $data['description'] = str_ireplace('Olance', 'QuoteMatch', $data['description']);
    }
    if (isset($data['social_description'])) {
        $data['social_description'] = str_ireplace('Olance', 'QuoteMatch', $data['social_description']);
    }
    if (isset($data['keywords']) && is_array($data['keywords'])) {
        $data['keywords'] = array_map(fn ($k) => str_ireplace('olance', 'quotematch', strtolower($k)) === 'olance' ? 'quotematch' : $k, $data['keywords']);
        $data['keywords'] = array_values(array_filter($data['keywords'], fn ($k) => strtolower($k) !== 'olance'));
        if (!in_array('quotematch', array_map('strtolower', $data['keywords']))) {
            array_unshift($data['keywords'], 'quotematch');
        }
    }
    $seo->data_values = $data;
    $seo->save();
    echo "Updated SEO data\n";
}

echo "Done.\n";
