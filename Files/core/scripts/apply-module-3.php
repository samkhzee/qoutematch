<?php

/**
 * Module 3 — Testimonials on homepage + registration/profile foundations
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$template = activeTemplate();
$templateName = activeTemplateName();

function saveContent(string $key, array $values, string $templateName): void
{
    $row = App\Models\Frontend::where('data_keys', $key)->where('tempname', $templateName)->first()
        ?? App\Models\Frontend::where('data_keys', $key)->first()
        ?? new App\Models\Frontend();

    $row->data_keys = $key;
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

// ── Testimonials (homepage slider) ──────────────────────────────────────────
saveContent('testimonial.content', [
    'heading' => 'What Our Customers Say',
    'subheading' => 'Homeowners and businesses use QuoteMatch to compare builder and freight quotes with confidence.',
], $templateName);

$existingImage = App\Models\Frontend::where('data_keys', 'testimonial.element')->first()?->data_values->image ?? '67d9248a6a2f51742283914.png';

saveElements('testimonial', [
    [
        'has_image' => '1',
        'name' => 'Sarah Mitchell',
        'country' => 'Manchester, UK',
        'quote' => 'We posted our kitchen renovation once and received four clear quotes within 48 hours. Comparing price and reviews side by side saved us weeks of phone calls.',
        'image' => $existingImage,
    ],
    [
        'has_image' => '1',
        'name' => 'James Patel',
        'country' => 'Birmingham, UK',
        'quote' => 'As a small importer, QuoteMatch helped us compare sea freight and customs quotes transparently. We chose a verified forwarder with confidence.',
        'image' => $existingImage,
    ],
    [
        'has_image' => '1',
        'name' => 'Emma Collins',
        'country' => 'Leeds, UK',
        'quote' => 'The platform made it easy to compare builder quotes for our extension. Every provider showed inclusions clearly — no hidden surprises.',
        'image' => $existingImage,
    ],
], $templateName);

// ── Registration switching labels ───────────────────────────────────────────
saveContent('switching_button.content', [
    'freelancer_login_button' => 'Provider Login',
    'buyer_login_button' => 'Customer Login',
    'freelancer_register_button' => 'Join as Provider',
    'buyer_register_button' => 'Join as Customer',
], $templateName);

saveContent('register.content', [
    'has_image' => '1',
    'heading' => 'Create Your QuoteMatch Account',
    'image' => App\Models\Frontend::where('data_keys', 'register.content')->first()?->data_values->image ?? '67d9248a6a2f51742283914.png',
], $templateName);

// ── Homepage includes testimonial slider ────────────────────────────────────
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
    echo "Homepage sections updated (testimonial added)\n";
}

Illuminate\Support\Facades\Cache::flush();

// Grandfather existing providers so they can keep submitting quotes
App\Models\User::query()->update(['provider_approved' => true]);
echo "Existing providers marked as approved\n";

echo "Module 3 CMS applied.\n";
