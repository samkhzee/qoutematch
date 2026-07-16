<?php

/**
 * Deploy QuoteMatch blueprint-aligned images and CMS content.
 * Source: Complete Blueprint PDF — builders + freight/logistics quote comparison.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$srcDir = 'C:/Users/Administrator/.cursor/projects/c-laragon-www-codecanyon-MNzPySlM-olance-global-freelancing-marketplace-Files-core/assets';
$assetsRoot = realpath(__DIR__ . '/../../assets');
$ts = time();

function deployImage(string $srcDir, string $destDir, string $srcName, string $prefix): string
{
    global $assetsRoot, $ts;

    $src = "{$srcDir}/{$srcName}";
    if (!is_file($src)) {
        echo "Missing source: {$src}\n";
        return '';
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $filename = "{$prefix}-{$ts}.png";
    copy($src, "{$destDir}/{$filename}");
    echo "Deployed: {$filename}\n";

    return $filename;
}

function updateContent(string $key, array $fields): void
{
    $row = App\Models\Frontend::where('data_keys', $key)->first();
    if (!$row) {
        echo "CMS row missing: {$key}\n";
        return;
    }

    $data = (array) $row->data_values;
    foreach ($fields as $field => $value) {
        $data[$field] = $value;
    }

    $row->data_values = $data;
    $row->save();
    echo "Updated: {$key}\n";
}

function updateElements(string $prefix, array $items, string $matchField = 'title'): void
{
    $rows = App\Models\Frontend::where('data_keys', "{$prefix}.element")->orderBy('id')->get();

    foreach ($items as $index => $fields) {
        $row = $rows[$index] ?? null;
        if (!$row) {
            continue;
        }

        $data = (array) $row->data_values;
        foreach ($fields as $field => $value) {
            $data[$field] = $value;
        }

        $row->data_values = $data;
        $row->save();
        echo "Updated element {$prefix} #{$index}: " . ($fields[$matchField] ?? $fields['find_step'] ?? '') . "\n";
    }
}

// ── Deploy images ──────────────────────────────────────────────────────────
$heroFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/banner", 'qm-hero-workers.png', 'qm-hero');
$findTaskFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/find_task", 'qm-find-task-workers.png', 'qm-find-task');
$facilityFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/facility", 'qm-facility-workers.png', 'qm-facility');
$providerFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/account", 'qm-account-provider.png', 'qm-provider');
$customerFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/account", 'qm-account-customer.png', 'qm-customer');
$subscribeFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/subscribe", 'qm-subscribe.png', 'qm-subscribe');
$loginFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/login", 'qm-login-auth.png', 'qm-login');
$registerFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/register", 'qm-register-auth.png', 'qm-register');
$faqFile = deployImage($srcDir, "{$assetsRoot}/images/frontend/faq", 'qm-faq-support.png', 'qm-faq');

// Logo (keep QuoteMatch branding)
$logoSrc = "{$srcDir}/quotematch-logo-large.png";
if (is_file($logoSrc)) {
    copy($logoSrc, "{$assetsRoot}/images/logo_icon/logo.png");
    copy($logoSrc, "{$assetsRoot}/images/logo_icon/logo_dark.png");
    echo "Logo updated\n";
}

// ── Banner ─────────────────────────────────────────────────────────────────
if ($heroFile) {
    updateContent('banner.content', [
        'heading' => 'Compare Quotes from Trusted Providers',
        'subheading' => 'Post your requirement and receive multiple quotes from verified builders and freight forwarders.',
        'subtitle' => 'Trusted by 1000+ Businesses',
        'feature_one' => 'Verified Providers',
        'feature_two' => 'Free to Post',
        'feature_three' => 'Compare Quotes',
        'image' => $heroFile,
    ]);
}

// ── How it works (Blueprint §34) ───────────────────────────────────────────
updateContent('how_work.content', [
    'heading' => 'How QuoteMatch Works',
    'subheading' => 'Post your requirement, receive quotes from verified providers, and compare clearly before you choose.',
]);

updateElements('how_work', [
    [
        'icon' => '<i class="fas fa-clipboard-list"></i>',
        'title' => 'Post Your Request',
        'content' => 'Select builders, freight forwarders, or other categories. Add details, location, budget, and upload photos or documents.',
    ],
    [
        'icon' => '<i class="fas fa-file-invoice-dollar"></i>',
        'title' => 'Receive Quotes',
        'content' => 'Verified service providers review your request and submit structured quotes with price, timeline, and terms.',
    ],
    [
        'icon' => '<i class="fas fa-columns"></i>',
        'title' => 'Compare & Choose',
        'content' => 'Compare quotes side by side — price, ratings, verification status, availability, and inclusions before you decide.',
    ],
    [
        'icon' => '<i class="fas fa-handshake"></i>',
        'title' => 'Connect & Complete',
        'content' => 'Message providers, accept your chosen quote, and leave a review after the job or shipment is complete.',
    ],
]);

// ── Account / Provider CTA (Blueprint §34.5) ───────────────────────────────
if ($providerFile && $customerFile) {
    updateContent('account.content', [
        'freelancer_title' => 'Join as a Service Provider',
        'freelancer_content' => 'Are you a builder, tradesperson, or freight forwarder? Register, get verified, and receive matching customer requests.',
        'freelancer_button_name' => 'Register as Provider',
        'buyer_title' => 'Post Your Requirement',
        'buyer_content' => 'Need building work or freight quotes? Post free, compare multiple quotes, and choose the best verified provider.',
        'buyer_button_name' => 'Get Free Quotes',
        'freelancer' => $providerFile,
        'buyer' => $customerFile,
    ]);
}

// ── Why choose (Blueprint §34.4) ─────────────────────────────────────────────
updateContent('why_choose.content', [
    'heading' => 'Why Use QuoteMatch',
    'subheading' => 'Save time, compare prices clearly, and choose from verified builders and freight forwarders with confidence.',
]);

updateElements('why_choose', [
    ['title' => 'Save Time', 'content' => 'Post once and receive multiple quotes instead of chasing providers individually.'],
    ['title' => 'Compare Prices', 'content' => 'See total price, labour, materials, and delivery costs in a clear side-by-side comparison.'],
    ['title' => 'Verified Providers', 'content' => 'Providers can be verified for company details, insurance, and trade certificates.'],
    ['title' => 'Clear Quote Breakdowns', 'content' => 'Structured quote forms show inclusions, exclusions, payment terms, and validity dates.'],
    ['title' => 'Reviews & Ratings', 'content' => 'Read genuine reviews from customers who accepted quotes through the platform.'],
    ['title' => 'Secure Messaging', 'content' => 'Communicate with providers safely inside the platform before accepting a quote.'],
]);

// ── Find task ──────────────────────────────────────────────────────────────
if ($findTaskFile) {
    updateContent('find_task.content', [
        'subtitle' => 'Get Quotes',
        'heading' => 'Builders & Freight Forwarders in One Place',
        'subheading' => 'Whether you need a kitchen fitter, extension builder, or sea freight quote — post your requirement and compare verified providers.',
        'button_name' => 'Get Quotes Now',
        'image' => $findTaskFile,
    ]);
}

updateElements('find_task', [
    ['find_step' => 'Post your builder or freight requirement with photos and details'],
    ['find_step' => 'Receive multiple structured quotes from verified providers'],
    ['find_step' => 'Compare price, ratings, and availability — then choose with confidence'],
]);

// ── Facility ─────────────────────────────────────────────────────────────────
if ($facilityFile) {
    updateContent('facility.content', [
        'heading' => 'Why Choose QuoteMatch',
        'subheading' => 'A smart comparison platform for builders, freight forwarders, and verified service providers across the UK.',
        'image' => $facilityFile,
    ]);
}

updateElements('facility', [
    [
        'title' => 'Verified Providers Only',
        'content' => 'Builders and freight forwarders can upload insurance, certificates, and company documents for admin verification.',
    ],
    [
        'title' => 'Structured Quote Comparison',
        'content' => 'Compare total price, start dates, completion time, inclusions, and exclusions in one clear table.',
    ],
    [
        'title' => 'Free for Customers',
        'content' => 'Posting a requirement and comparing quotes is free. Customers choose the best provider with no upfront cost.',
    ],
]);

// ── Subscribe ────────────────────────────────────────────────────────────────
if ($subscribeFile) {
    updateContent('subscribe.content', [
        'heading' => 'Stay Updated on QuoteMatch',
        'subheading' => 'Get tips on comparing builders, freight quotes, and verified providers.',
        'image' => $subscribeFile,
    ]);
}

// ── Auth pages ───────────────────────────────────────────────────────────────
if ($loginFile) {
    updateContent('login.content', [
        'heading' => 'Welcome Back to QuoteMatch',
        'image' => $loginFile,
    ]);
}

if ($registerFile) {
    updateContent('register.content', [
        'heading' => 'Create Your QuoteMatch Account',
        'image' => $registerFile,
    ]);
}

// ── FAQ ──────────────────────────────────────────────────────────────────────
if ($faqFile) {
    updateContent('faq.content', [
        'heading' => 'Frequently Asked Questions',
        'subheading' => 'Everything you need to know about posting requirements and comparing quotes on QuoteMatch.',
        'image' => $faqFile,
    ]);
}

// ── SEO ──────────────────────────────────────────────────────────────────────
$seo = App\Models\Frontend::where('data_keys', 'seo.data')->first();
if ($seo) {
    $data = (array) $seo->data_values;
    $data['social_title'] = 'QuoteMatch — Compare Builder & Freight Quotes';
    $data['description'] = 'QuoteMatch helps customers post requirements and compare quotes from verified builders and freight forwarders. Free to post, compare prices, choose with confidence.';
    $data['social_description'] = $data['description'];
    $data['keywords'] = ['quotematch', 'compare quotes', 'builders', 'freight forwarders', 'home improvement', 'logistics', 'verified providers'];
    $seo->data_values = $data;
    $seo->save();
    echo "Updated SEO\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Done. All blueprint images and content deployed.\n";
