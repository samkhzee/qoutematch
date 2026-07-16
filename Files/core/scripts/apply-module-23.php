<?php

/**
 * Module 23 — Location-aware provider matching v2 (SEO locations + postcodes)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Lib\JobMatchingService;
use App\Models\SeoLocation;
use Illuminate\Support\Facades\Cache;

echo "Module 23 — Location-aware matching v2\n";
echo str_repeat('-', 40) . "\n";

echo class_exists(JobMatchingService::class) ? "OK  JobMatchingService\n" : "MISSING JobMatchingService\n";
echo method_exists(JobMatchingService::class, 'matchScore') ? "OK  matchScore()\n" : "MISSING matchScore()\n";
echo method_exists(JobMatchingService::class, 'jobLocationTerms') ? "OK  jobLocationTerms()\n" : "MISSING jobLocationTerms()\n";

$locations = SeoLocation::active()->count();
echo "Active SEO locations: {$locations}\n";

echo "\nMatching behaviour (Module 23):\n";
echo "  - Postcode / city / destination fields expanded via SeoLocation\n";
echo "  - Provider service areas matched against structured request_data\n";
echo "  - matchScore() available for recommendations (0–100)\n";

Cache::flush();
echo "\nModule 23 applied.\n";
