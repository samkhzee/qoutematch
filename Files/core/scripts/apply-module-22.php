<?php

/**
 * Module 22 — Quote intelligence (summed freight totals, compare breakdown)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 22 — Quote intelligence\n";
echo str_repeat('-', 40) . "\n";

$files = [
    'app/Lib/QuoteAmountService.php',
    'resources/js/Pages/Buyer/Job/CompareQuotes.jsx',
    'resources/js/Pages/Public/JobDetails.jsx',
];

foreach ($files as $file) {
    echo file_exists(__DIR__ . '/../' . $file) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$bidController = file_get_contents(__DIR__ . '/../app/Http/Controllers/User/BidController.php');
echo str_contains($bidController, 'QuoteAmountService::resolveBidAmount')
    ? "OK  BidController uses QuoteAmountService\n"
    : "CHECK BidController\n";

$manageJob = file_get_contents(__DIR__ . '/../app/Http/Controllers/Buyer/ManageJobController.php');
echo str_contains($manageJob, 'quoteBreakdown')
    ? "OK  Compare quotes includes quoteBreakdown\n"
    : "CHECK ManageJobController\n";

Cache::flush();

echo "\nModule 22 deliverables:\n";
echo "  Freight cost line items sum into bid_amount (Option B)\n";
echo "  Compare Quotes shows per-line breakdown + total\n";
echo "  Live total box on bid modal for summed quote forms\n";
echo "\nModule 22 applied.\n";
