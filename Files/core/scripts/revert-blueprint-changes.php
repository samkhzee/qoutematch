<?php

/**
 * Revert deploy-blueprint-images.php changes.
 * Restores original CodeCanyon images + pre-blueprint CMS (keeps QuoteMatch branding text).
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Restore PNG assets from original zip.
passthru('"' . PHP_BINARY . '" "' . __DIR__ . '/restore-all-frontend-pngs.php"', $code);
if ($code !== 0) {
    exit($code);
}

passthru('"' . PHP_BINARY . '" "' . __DIR__ . '/restore-original-assets.php"', $code);
if ($code !== 0) {
    exit($code);
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
    echo "Reverted: {$key}\n";
}

function updateElements(string $prefix, array $items): void
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
        echo "Reverted element {$prefix} #{$index}\n";
    }
}

// how_work — original Olance steps
updateContent('how_work.content', [
    'heading' => "It's Easy to Get Work Done",
    'subheading' => 'Our platform connects clients with freelancers to get work done efficiently and securely.',
]);

updateElements('how_work', [
    [
        'icon' => '<i class="fas fa-briefcase"></i>',
        'title' => 'Post a Job',
        'content' => 'Posting a job is easy. Provide a detailed description of your project, set your budget, and specify any requirements.',
    ],
    [
        'icon' => '<i class="fas fa-user-graduate"></i>',
        'title' => 'Hire Freelancers',
        'content' => 'Browse through our extensive list of freelancers, review their profiles, and find the perfect match for your project.',
    ],
    [
        'icon' => '<i class="fas fa-check-square"></i>',
        'title' => 'Get Work Done',
        'content' => "Once you've hired, directly communicate through our platform to discuss project details and milestones.",
    ],
    [
        'icon' => '<i class="fas fa-hand-holding-usd"></i>',
        'title' => 'Make Secure Payments',
        'content' => 'We ensure that your transactions are safe and secure. Use our platform to make payments with confidence.',
    ],
]);

// account — original images + copy
updateContent('account.content', [
    'freelancer_title' => 'Sign Up as a Freelancer',
    'freelancer_content' => 'Showcase your skills, connect with buyers, and get hired.',
    'freelancer_button_name' => 'Create Freelance Account',
    'buyer_title' => 'Sign Up as a Buyer',
    'buyer_content' => 'Post jobs, hire skilled talent, and get projects done.',
    'buyer_button_name' => 'Create Buyer Account',
    'freelancer' => '67d929f13124f1742285297.png',
    'buyer' => '67d929f13efd31742285297.png',
]);

// why_choose
updateContent('why_choose.content', [
    'heading' => 'Why You Should Choose Us',
    'subheading' => 'Discover the benefits of using our platform for your freelancing and hiring needs.',
]);

updateElements('why_choose', [
    ['title' => 'Proof & Quality', 'content' => 'We ensure high-quality results with our proof of work and milestone system. Review completed work before releasing payments.', 'image' => '67d92d4630f5f1742286150.png'],
    ['title' => 'No Cost Until You Hire', 'content' => 'Enjoy our platform with zero upfront costs. You only pay when you hire a freelancer and paid if hastle free done your project.', 'image' => '67d92d3aea8ba1742286138.png'],
    ['title' => 'Safe and Secure', 'content' => 'Our platform uses advanced security measures to protect your data and transactions, ensuring a secure experience.', 'image' => '67d92d322b52c1742286130.png'],
    ['title' => 'Post Job & Hire a Pro', 'content' => 'Clients can easily post job and hire professionals. Provide detailed project requirements and attract proposals from qualified freelancers.', 'image' => '67d92d2981d5d1742286121.png'],
    ['title' => 'Bid to Find Jobs', 'content' => 'Freelancers can bid on jobs. Showcase your skills, submit proposals, and secure projects that match your expertise.', 'image' => '67d92d210a8a91742286113.png'],
    ['title' => 'Top Rated', 'content' => 'We host top-rated freelancers who are experts in their fields. Browse profiles & review ratings to find the best talents.', 'image' => '67d92d169e8d61742286102.png'],
]);

// find_task — keep QuoteMatch heading from apply-branding, restore original image/steps
updateContent('find_task.content', [
    'subtitle' => 'Find Your Task',
    'heading' => 'Find the Right Service for Your Needs',
    'subheading' => 'Unlock value by comparing quotes from verified providers. Post your job, compare prices, and choose with confidence.',
    'button_name' => 'Find Your Work',
    'image' => '67d930681c42a1742286952.png',
    'shape' => '673b2b970126d1731931031.png',
]);

updateElements('find_task', [
    ['find_step' => 'Access expert talent to fill your skill gaps'],
    ['find_step' => 'Control your workflow : bid & proved your skill'],
    ['find_step' => 'Always grow your skill & find job'],
]);

// facility — QuoteMatch heading, original image
updateContent('facility.content', [
    'heading' => 'Why Choose QuoteMatch',
    'subheading' => 'Discover the benefits of using QuoteMatch for comparing builders, freight forwarders, and verified service providers.',
    'image' => '67d9265b17fcb1742284379.png',
]);

updateElements('facility', [
    ['title' => 'Higher Quality Listings', 'content' => 'We ensure that our job listings are of the highest quality. Each listing is thoroughly vetted to ensure it meets our standards, providing you with the best opportunities to showcase your skills and expertise.'],
    ['title' => 'Unlimited Job Search Resources', 'content' => 'With Olance, you access unlimited job search resources. Use advanced filters, personalized job recommendations, and comprehensive listings to find the best match for your skills & goals.'],
    ['title' => 'Save Time', 'content' => 'We ensure that our job listings are of the highest quality. Each listing is thoroughly vetted to ensure it meets our standards, providing you with the best opportunities to showcase your skills and expertise.'],
]);

// subscribe, auth, faq
updateContent('subscribe.content', [
    'heading' => 'Subscribe Our Newsletter',
    'subheading' => '1000+ user subscribe our newsletter',
    'image' => '67d9340c2ee7b1742287884.png',
    'shape' => '673b4178ca7a71731936632.png',
]);

updateContent('login.content', [
    'heading' => 'Login Account',
    'image' => '673c528892dfc1732006536.png',
]);

updateContent('register.content', [
    'heading' => 'Register Account',
    'image' => '673c52aeac7991732006574.png',
]);

updateContent('faq.content', [
    'heading' => 'Frequently Asked Questions',
    'subheading' => 'Find clear answers to the most common questions about our services, process, and support.',
    'image' => '67d9248a6a2f51742283914.png',
]);

// banner — QuoteMatch text, original hero image
updateContent('banner.content', [
    'heading' => 'Compare Quotes from Trusted Providers',
    'subheading' => 'Post your requirement and receive multiple quotes from verified builders and freight forwarders.',
    'subtitle' => 'Trusted by 1000+ Businesses',
    'feature_one' => '100% Remote',
    'feature_two' => '6700+ Jobs Available',
    'feature_three' => 'Great Job',
    'image' => '67d92c14906c01742285844.png',
    'shape' => '673af8b35ae361731918003.png',
]);

// SEO — QuoteMatch (from apply-branding, not blueprint)
$seo = App\Models\Frontend::where('data_keys', 'seo.data')->first();
if ($seo) {
    $data = (array) $seo->data_values;
    $data['social_title'] = 'Global Freelancing Marketplace';
    $data['description'] = 'QuoteMatch is a dynamic freelancing platform connecting clients with skilled professionals across various industries. With secure transactions, a user-friendly interface, and advanced project management tools, QuoteMatch simplifies remote work and collaboration.';
    $data['social_description'] = $data['description'];
    $data['keywords'] = ['quotematch', 'freelancing', 'bid', 'job post', 'bid project', 'earning', 'viserlab'];
    $seo->data_values = $data;
    $seo->save();
    echo "Reverted SEO\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Blueprint changes reverted.\n";
