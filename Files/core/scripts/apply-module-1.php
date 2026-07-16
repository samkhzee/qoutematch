<?php

/**
 * Module 1 — Foundation & rebranding cleanup (Blueprint §1–3, §34, §42 #1)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$template = activeTemplate(); // templates.basic.
$templateName = activeTemplateName(); // basic

function saveContent(string $key, array $values, string $templateName): void
{
    $row = App\Models\Frontend::where('data_keys', $key)->where('tempname', $templateName)->first()
        ?? App\Models\Frontend::where('data_keys', $key)->first();

    if (!$row) {
        $row = new App\Models\Frontend();
        $row->data_keys = $key;
    }

    $row->tempname = $templateName;
    $row->data_values = $values;
    $row->save();
    echo "Saved {$key}\n";
}

function saveElements(string $prefix, array $items, string $templateName): void
{
    App\Models\Frontend::where('data_keys', "{$prefix}.element")->delete();

    foreach ($items as $fields) {
        $row = new App\Models\Frontend();
        $row->data_keys = "{$prefix}.element";
        $row->tempname = $templateName;
        $row->data_values = $fields;
        $row->save();
    }

    echo "Saved " . count($items) . " {$prefix}.element rows\n";
}

function savePage(string $name, string $slug, array $secs): void
{
    global $template;

    $page = App\Models\Page::where('slug', $slug)->where('tempname', $template)->first();
    if (!$page) {
        $page = new App\Models\Page();
        $page->name = $name;
        $page->slug = $slug;
        $page->tempname = $template;
        $page->is_default = 0;
    }

    $page->secs = json_encode($secs);
    $page->save();
    echo "Page: {$name} (/{$slug})\n";
}

// ── Banner (Hero §34.1) ─────────────────────────────────────────────────────
saveContent('banner.content', [
    'has_image' => '1',
    'heading' => 'Compare Quotes from Trusted Service Providers',
    'subheading' => 'Post your requirement and receive multiple quotes from verified builders and freight forwarders.',
    'subtitle' => 'Trusted by 1000+ Businesses',
    'feature_one' => 'Verified Providers',
    'feature_two' => 'Free to Post',
    'feature_three' => 'Compare Quotes',
    'image' => App\Models\Frontend::where('data_keys', 'banner.content')->first()?->data_values->image ?? '67d92c14906c01742285844.png',
    'shape' => App\Models\Frontend::where('data_keys', 'banner.content')->first()?->data_values->shape ?? '673af8b35ae361731918003.png',
], $templateName);

// ── Popular categories heading (§34.3) ──────────────────────────────────────
saveContent('category.content', [
    'heading' => 'Popular Categories',
    'subheading' => 'Browse builders, freight forwarders, and related services to get started.',
], $templateName);

// ── How it works — 3 steps (§34.2) ──────────────────────────────────────────
saveContent('how_work.content', [
    'heading' => 'How QuoteMatch Works',
    'subheading' => 'Post your requirement, receive quotes from verified providers, and compare clearly before you choose.',
], $templateName);

saveElements('how_work', [
    [
        'icon' => '<i class="fas fa-clipboard-list"></i>',
        'title' => 'Post Your Request',
        'content' => 'Select a category, add your location, budget, photos, and requirements. Posting is free for customers.',
    ],
    [
        'icon' => '<i class="fas fa-file-invoice-dollar"></i>',
        'title' => 'Receive Quotes',
        'content' => 'Verified service providers review your request and submit structured quotes with price, timeline, and terms.',
    ],
    [
        'icon' => '<i class="fas fa-columns"></i>',
        'title' => 'Compare & Choose',
        'content' => 'Compare quotes side by side — price, ratings, verification, availability, and inclusions — then choose confidently.',
    ],
], $templateName);

// ── Why use the platform (§34.4) ────────────────────────────────────────────
saveContent('why_choose.content', [
    'heading' => 'Why Use QuoteMatch',
    'subheading' => 'Save time, compare prices clearly, and choose from verified providers with confidence.',
], $templateName);

saveElements('why_choose', [
    ['has_image' => '1', 'title' => 'Save Time', 'content' => 'Post once and receive multiple quotes instead of chasing providers individually.', 'image' => '67d92d4630f5f1742286150.png'],
    ['has_image' => '1', 'title' => 'Compare Prices', 'content' => 'See total price, labour, materials, and delivery costs in a clear comparison.', 'image' => '67d92d3aea8ba1742286138.png'],
    ['has_image' => '1', 'title' => 'Verified Providers', 'content' => 'Providers can be verified for company details, insurance, and trade certificates.', 'image' => '67d92d322b52c1742286130.png'],
    ['has_image' => '1', 'title' => 'Clear Quote Breakdowns', 'content' => 'Structured quotes show inclusions, exclusions, payment terms, and validity dates.', 'image' => '67d92d2981d5d1742286121.png'],
    ['has_image' => '1', 'title' => 'Reviews & Ratings', 'content' => 'Read genuine reviews from customers who accepted quotes through the platform.', 'image' => '67d92d210a8a91742286113.png'],
    ['has_image' => '1', 'title' => 'Secure Messaging', 'content' => 'Communicate with providers safely inside the platform before accepting a quote.', 'image' => '67d92d169e8d61742286102.png'],
], $templateName);

// ── Provider CTA (§34.5) ────────────────────────────────────────────────────
saveContent('account.content', [
    'has_image' => '1',
    'freelancer_title' => 'Are You a Service Provider?',
    'freelancer_content' => 'Join QuoteMatch to receive matching customer requests. Builders, tradespeople, and freight forwarders welcome.',
    'freelancer_button_name' => 'Join as Provider',
    'buyer_title' => 'Need Quotes for Your Project?',
    'buyer_content' => 'Post your building or logistics requirement for free. Compare multiple quotes and choose the best verified provider.',
    'buyer_button_name' => 'Get Free Quotes',
    'freelancer' => App\Models\Frontend::where('data_keys', 'account.content')->first()?->data_values->freelancer ?? '67d929f13124f1742285297.png',
    'buyer' => App\Models\Frontend::where('data_keys', 'account.content')->first()?->data_values->buyer ?? '67d929f13efd31742285297.png',
], $templateName);

// ── Trust section (§34.6) ───────────────────────────────────────────────────
saveContent('trust.content', [
    'heading' => 'Trust & Safety',
    'subheading' => 'We help customers compare quotes with confidence through verification, secure messaging, and reviews.',
], $templateName);

saveElements('trust', [
    ['icon' => 'las la-user-check', 'title' => 'Verified Profiles', 'content' => 'Providers can upload insurance, certificates, and company documents for admin verification.'],
    ['icon' => 'las la-comment-dots', 'title' => 'Secure Messaging', 'content' => 'Communicate inside the platform. Contact details stay protected until quote rules are met.'],
    ['icon' => 'las la-star', 'title' => 'Review System', 'content' => 'Customers leave reviews after accepting a quote and completing work — building trust for everyone.'],
], $templateName);

// ── FAQ intro ─────────────────────────────────────────────────────────────────
saveContent('faq.content', [
    'heading' => 'Frequently Asked Questions',
    'subheading' => 'Everything you need to know about posting requirements and comparing quotes on QuoteMatch.',
    'has_image' => '1',
    'image' => App\Models\Frontend::where('data_keys', 'faq.content')->first()?->data_values->image ?? '67d9248a6a2f51742283914.png',
], $templateName);

// ── Public pages (§6.1) ─────────────────────────────────────────────────────
saveContent('for_customers.content', [
    'heading' => 'For Customers',
    'subheading' => 'Post your requirement, compare quotes, and choose the best verified provider — free to use.',
    'body' => '<p>QuoteMatch helps homeowners and businesses compare quotes from verified builders and freight forwarders in one place.</p>
<ul>
<li>Post building, renovation, or logistics requirements</li>
<li>Upload photos, plans, and documents</li>
<li>Receive multiple structured quotes</li>
<li>Compare price, availability, reviews, and verification</li>
<li>Message providers and accept the best quote</li>
<li>Leave a review after completion</li>
</ul>
<p><strong>Examples:</strong> kitchen fitting, extensions, sea freight, customs clearance, pallet delivery.</p>',
    'button_text' => 'Get Free Quotes',
    'button_route' => 'customer',
], $templateName);

saveContent('for_providers.content', [
    'heading' => 'For Service Providers',
    'subheading' => 'Register, get verified, and receive matching customer requests in your categories and locations.',
    'body' => '<p>Join QuoteMatch as a builder, tradesperson, freight forwarder, or logistics provider.</p>
<ul>
<li>Create a professional business profile</li>
<li>Select categories and service areas you cover</li>
<li>Upload insurance, certificates, and portfolio</li>
<li>View matching customer requests</li>
<li>Submit structured quotes with clear pricing</li>
<li>Track won and lost jobs from your dashboard</li>
</ul>
<p>Customer posting is free — you receive leads that match your trade and location.</p>',
    'button_text' => 'Join as Provider',
    'button_route' => 'provider',
], $templateName);

saveContent('pricing.content', [
    'heading' => 'Pricing',
    'subheading' => 'Simple and transparent — customers use QuoteMatch for free.',
    'body' => '<h5>For Customers</h5>
<p><strong>Free</strong> — post requirements, receive quotes, compare providers, and message providers at no cost.</p>
<h5>For Service Providers</h5>
<p><strong>Free at launch</strong> — register, create your profile, and submit quotes during our MVP phase.</p>
<p>Future options may include provider subscriptions or lead credits. Paid listings will always be clearly labelled.</p>
<p>We recommend starting with free provider registration while we validate demand in builders and freight categories.</p>',
    'button_text' => 'Get Started',
    'button_route' => 'customer',
], $templateName);

saveContent('trust_safety.content', [
    'heading' => 'Trust & Safety',
    'subheading' => 'How QuoteMatch keeps customers and providers safe.',
    'body' => '<h5>Provider Verification</h5>
<p>Admins can verify email, phone, company details, insurance, and trade certificates before providers appear as fully verified.</p>
<h5>Secure Messaging</h5>
<p>Keep communication on-platform. Contact details may be hidden until a quote is submitted or accepted, depending on platform rules.</p>
<h5>Reviews</h5>
<p>Reviews are allowed after a quote is accepted and work is marked complete — reducing fake feedback.</p>
<h5>Disputes</h5>
<p>Users can report fake quotes, no-shows, or incorrect information. Admins investigate and can suspend accounts when needed.</p>
<h5>GDPR</h5>
<p>We provide privacy policy, cookie policy, and terms. Customer and provider data is handled according to UK GDPR requirements.</p>',
    'button_text' => 'Contact Us',
    'button_route' => 'contact',
], $templateName);

savePage('For Customers', 'for-customers', ['for_customers']);
savePage('For Providers', 'for-providers', ['for_providers']);
savePage('Pricing', 'pricing', ['pricing']);
savePage('Trust & Safety', 'trust-and-safety', ['trust_safety']);

// ── Homepage section order (§34) ────────────────────────────────────────────
$home = App\Models\Page::where('slug', '/')->where('tempname', $template)->first();
if ($home) {
    $home->secs = json_encode([
        'category',
        'how_work',
        'why_choose',
        'account',
        'trust',
        'user_types',
        'testimonial',
        'faq',
        'subscribe',
        'blog',
    ]);
    $home->save();
    echo "Homepage sections updated\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Module 1 applied.\n";
