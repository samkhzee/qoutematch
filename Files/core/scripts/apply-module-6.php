<?php

/**
 * Module 6 — Provider matching verifier (Blueprint §25, §42.9)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use App\Lib\JobMatchingService;
use Illuminate\Support\Facades\Schema;

echo "Module 6 — Provider matching\n";
echo str_repeat('-', 40) . "\n";

$ok = true;

if (!Schema::hasColumn('users', 'subcategory_ids')) {
    echo "MISSING users.subcategory_ids — run Module 3 migration\n";
    $ok = false;
} else {
    echo "OK  users.subcategory_ids column\n";
}

if (!Schema::hasColumn('users', 'service_areas')) {
    echo "MISSING users.service_areas — run Module 3 migration\n";
    $ok = false;
} else {
    echo "OK  users.service_areas column\n";
}

if (!Schema::hasColumn('jobs', 'request_data')) {
    echo "MISSING jobs.request_data — run Module 4 migration\n";
    $ok = false;
} else {
    echo "OK  jobs.request_data column\n";
}

$featuredCategories = Category::active()->where('is_featured', Status::YES)->count();
echo "Featured categories: {$featuredCategories}\n";

$approvedProviders = User::where('provider_approved', Status::YES)->count();
echo "Approved providers: {$approvedProviders}\n";

$publishedJobs = Job::published()->approved()->count();
echo "Published requests: {$publishedJobs}\n";

if (class_exists(JobMatchingService::class)) {
    echo "OK  JobMatchingService loaded\n";
    echo "  - Listing filter: applyProviderMatching()\n";
    echo "  - Strong match hint: jobStronglyMatchesProvider()\n";
} else {
    echo "MISSING JobMatchingService\n";
    $ok = false;
}

echo "\nMatching behaviour (Blueprint §25):\n";
echo "  - Browse/filter uses provider subcategories + service areas\n";
echo "  - Providers may quote outside match with on-page notice\n";

echo $ok ? "\nModule 6 verified.\n" : "\nModule 6 has missing prerequisites.\n";
exit($ok ? 0 : 1);
