<?php

/**
 * Module 15 — Public SEO & legal (Blueprint §27, §30, §33)
 *
 * Location landing pages, category+location SEO URLs, legal policies,
 * GDPR cookie consent, sitemap.xml, and robots.txt.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Frontend;
use App\Models\SeoLocation;
use App\Lib\SeoSitemapService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

$templateName = activeTemplateName();

function upsertPolicy(string $slug, string $title, string $details, string $templateName): void
{
    $row = Frontend::where('data_keys', 'policy_pages.element')
        ->where('tempname', $templateName)
        ->where('slug', $slug)
        ->first() ?? new Frontend();

    $row->data_keys = 'policy_pages.element';
    $row->tempname = $templateName;
    $row->slug = $slug;
    $row->data_values = [
        'title' => $title,
        'details' => $details,
    ];

    if (!$row->seo_content) {
        $row->seo_content = [
            'description' => Str::limit(strip_tags($details), 160, ''),
            'keywords' => [],
            'social_title' => $title,
            'social_description' => Str::limit(strip_tags($details), 160, ''),
        ];
    }

    $row->save();
    echo "Policy saved: {$title} (/policy/{$slug})\n";
}

function upsertLocation(array $data): void
{
    $row = SeoLocation::where('slug', $data['slug'])->first() ?? new SeoLocation();
    $row->name = $data['name'];
    $row->slug = $data['slug'];
    $row->region = $data['region'] ?? null;
    $row->seo_title = $data['seo_title'] ?? null;
    $row->seo_description = $data['seo_description'] ?? null;
    $row->intro = $data['intro'] ?? null;
    $row->is_featured = Status::YES;
    $row->status = Status::ENABLE;
    $row->save();

    echo "Location: {$row->name}\n";
}

echo "Module 15 — Public SEO & legal\n";
echo str_repeat('-', 40) . "\n";

if (!Schema::hasTable('seo_locations')) {
    echo "MISSING seo_locations table — run:\n";
    echo "  php artisan migrate --path=database/migrations/2026_07_06_000010_add_module15_seo_locations.php --force\n";
    exit(1);
}

$locations = [
    [
        'name' => 'London',
        'slug' => 'london',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in London | QuoteMatch',
        'seo_description' => 'Find verified builders, tradespeople, and freight providers in London. Post your requirement free and compare quotes.',
        'intro' => 'Compare quotes from verified builders and logistics providers across London.',
    ],
    [
        'name' => 'Manchester',
        'slug' => 'manchester',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Manchester | QuoteMatch',
        'seo_description' => 'Get free quotes from verified providers in Manchester for building, renovation, and freight services.',
        'intro' => 'Post requirements in Manchester and compare quotes from local verified providers.',
    ],
    [
        'name' => 'Birmingham',
        'slug' => 'birmingham',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Birmingham | QuoteMatch',
        'seo_description' => 'Compare builder and freight quotes in Birmingham from verified QuoteMatch providers.',
        'intro' => 'Find trusted builders and freight forwarders serving Birmingham and the West Midlands.',
    ],
    [
        'name' => 'Leeds',
        'slug' => 'leeds',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Leeds | QuoteMatch',
        'seo_description' => 'Compare quotes for building and logistics services in Leeds from verified providers.',
        'intro' => 'Get competitive quotes from verified providers across Leeds and Yorkshire.',
    ],
    [
        'name' => 'Bristol',
        'slug' => 'bristol',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Bristol | QuoteMatch',
        'seo_description' => 'Post your requirement in Bristol and compare quotes from verified builders and freight providers.',
        'intro' => 'Browse popular categories and compare provider quotes in Bristol.',
    ],
    [
        'name' => 'Liverpool',
        'slug' => 'liverpool',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Liverpool | QuoteMatch',
        'seo_description' => 'Find verified builders and logistics providers in Liverpool on QuoteMatch.',
        'intro' => 'Compare free quotes from verified providers serving Liverpool and Merseyside.',
    ],
    [
        'name' => 'Sheffield',
        'slug' => 'sheffield',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Sheffield | QuoteMatch',
        'seo_description' => 'Compare building and freight quotes in Sheffield from verified QuoteMatch providers.',
        'intro' => 'Post your project in Sheffield and receive structured quotes from verified providers.',
    ],
    [
        'name' => 'Newcastle upon Tyne',
        'slug' => 'newcastle-upon-tyne',
        'region' => 'England',
        'seo_title' => 'Compare Service Quotes in Newcastle | QuoteMatch',
        'seo_description' => 'Get quotes from verified builders and freight providers in Newcastle upon Tyne.',
        'intro' => 'Compare provider quotes across Newcastle and the North East.',
    ],
    [
        'name' => 'Glasgow',
        'slug' => 'glasgow',
        'region' => 'Scotland',
        'seo_title' => 'Compare Service Quotes in Glasgow | QuoteMatch',
        'seo_description' => 'Find verified builders and logistics providers in Glasgow. Compare quotes free on QuoteMatch.',
        'intro' => 'Browse categories and compare quotes from verified providers in Glasgow.',
    ],
    [
        'name' => 'Edinburgh',
        'slug' => 'edinburgh',
        'region' => 'Scotland',
        'seo_title' => 'Compare Service Quotes in Edinburgh | QuoteMatch',
        'seo_description' => 'Post requirements in Edinburgh and compare quotes from verified builders and freight providers.',
        'intro' => 'Get competitive quotes from verified providers across Edinburgh and the Lothians.',
    ],
];

foreach ($locations as $location) {
    upsertLocation($location);
}

echo "\nSeeded " . count($locations) . " locations\n\n";

upsertPolicy(
    'privacy-policy',
    'Privacy Policy',
    '<h4>Introduction</h4>
<p>QuoteMatch respects your privacy and processes personal data in line with UK GDPR and the Data Protection Act 2018.</p>
<h4>Data we collect</h4>
<ul>
<li>Account details such as name, email, phone, and business information</li>
<li>Request and quote content you submit on the platform</li>
<li>Messages, reviews, verification documents, and support tickets</li>
<li>Technical data such as IP address, browser type, and cookie preferences</li>
</ul>
<h4>How we use data</h4>
<p>We use your information to operate the marketplace, match requests with providers, send service notifications, improve security, and comply with legal obligations.</p>
<h4>Your rights</h4>
<p>You may request access, correction, deletion, or restriction of your personal data. Contact us using the details on our Contact page.</p>
<h4>Retention</h4>
<p>We retain account and transaction records only for as long as needed to provide the service, resolve disputes, and meet legal requirements.</p>',
    $templateName
);

upsertPolicy(
    'customer-terms',
    'Customer Terms',
    '<h4>Using QuoteMatch as a customer</h4>
<p>By posting a requirement or accepting a quote on QuoteMatch, you agree to provide accurate information and communicate respectfully with providers.</p>
<h4>Posting requirements</h4>
<p>Customer posting is free during the MVP phase. You are responsible for the accuracy of project details, uploads, and contact information.</p>
<h4>Quotes and acceptance</h4>
<p>Quotes are submitted by independent providers. QuoteMatch facilitates comparison and messaging but does not guarantee provider performance unless expressly stated.</p>
<h4>Reviews and disputes</h4>
<p>Reviews should reflect genuine experiences after accepted work. Report misleading quotes, abuse, or safety concerns through the platform dispute process.</p>',
    $templateName
);

upsertPolicy(
    'provider-terms',
    'Provider Terms',
    '<h4>Using QuoteMatch as a provider</h4>
<p>Providers must supply accurate business details, maintain professional communication, and submit honest structured quotes.</p>
<h4>Profile and verification</h4>
<p>You agree to keep your profile, categories, service areas, and verification documents up to date. Admin approval may be required before you receive matching leads.</p>
<h4>Quotes and messaging</h4>
<p>Quotes must clearly state pricing, scope, exclusions, and validity where applicable. Do not share prohibited contact details in messages before platform rules allow it.</p>
<h4>Lead credits and subscriptions</h4>
<p>If monetisation is enabled by the platform operator, paid lead credits or subscriptions will be clearly labelled before purchase.</p>
<h4>Suspension</h4>
<p>QuoteMatch may suspend accounts that submit fraudulent quotes, harass users, or breach these terms.</p>',
    $templateName
);

$cookie = Frontend::where('data_keys', 'cookie.data')->first() ?? new Frontend();
$cookie->data_keys = 'cookie.data';
$cookie->tempname = $templateName;
$cookie->data_values = [
    'short_desc' => 'We use cookies to improve your experience, remember preferences, and analyse site traffic.',
    'description' => '<h4>Cookie Policy</h4>
<p>QuoteMatch uses essential and analytics cookies to keep you signed in, remember consent choices, and improve the marketplace experience.</p>
<h4>Essential cookies</h4>
<p>These cookies are required for login sessions, security tokens, and remembering your cookie consent choice.</p>
<h4>Analytics cookies</h4>
<p>We may use analytics cookies to understand how visitors use public pages and dashboards. These are only set when you allow cookies.</p>
<h4>Managing cookies</h4>
<p>You can accept or reject non-essential cookies using the banner on your first visit. You can also clear cookies through your browser settings.</p>
<h4>Contact</h4>
<p>For privacy questions, contact us via the Contact page or review our Privacy Policy.</p>',
    'status' => Status::ENABLE,
];
$cookie->save();
echo "Cookie policy/GDPR banner updated\n";

$sitemapPath = dirname(__DIR__) . '/sitemap.xml';
file_put_contents($sitemapPath, SeoSitemapService::generate());
echo "Sitemap written: {$sitemapPath}\n";

$robotsPath = dirname(__DIR__) . '/robots.txt';
file_put_contents($robotsPath, "User-agent: *\nAllow: /\nSitemap: " . route('sitemap') . "\n");
echo "Robots written: {$robotsPath}\n";

Cache::flush();

echo "\nPublic SEO routes:\n";
echo "  Locations index:  /locations\n";
echo "  Location detail:  /locations/{slug}\n";
echo "  Category+area:    /services/{category}/in/{location}\n";
echo "  Sitemap:          /sitemap.xml\n";
echo "  Robots:           /robots.txt\n";
echo "\nLegal pages:\n";
echo "  /policy/privacy-policy\n";
echo "  /policy/customer-terms\n";
echo "  /policy/provider-terms\n";
echo "  /cookie-policy\n";
echo "\nModule 15 applied.\n";
