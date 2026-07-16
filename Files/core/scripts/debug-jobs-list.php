<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Job;
use App\Models\User;
use App\Constants\Status;
use App\Lib\JobMatchingService;

$user = User::where('email', 'john.doe@example.com')->first();
$allPublished = Job::published()->approved()->biddingOpen()->count();
$withMatching = JobMatchingService::applyProviderMatching(
    Job::published()->approved()->biddingOpen(),
    $user
)->count();

echo "User: {$user->email}\n";
echo "provider_approved: {$user->provider_approved}\n";
echo "subcategory_ids: " . json_encode($user->subcategory_ids) . "\n";
echo "service_areas: " . ($user->service_areas ?? 'null') . "\n";
echo "Jobs (base query): {$allPublished}\n";
echo "Jobs (after matching): {$withMatching}\n";

Job::published()->approved()->get(['id', 'title', 'subcategory_id', 'deadline'])->each(function ($j) {
    echo "  Job #{$j->id} subcat={$j->subcategory_id} deadline={$j->deadline} {$j->title}\n";
});
