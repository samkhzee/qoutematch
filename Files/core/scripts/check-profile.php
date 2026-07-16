<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::where('email', 'john.doe@example.com')
    ->with(['skills', 'educations', 'portfolios'])
    ->first();

if (!$u) {
    echo "User not found\n";
    exit(1);
}

echo "step={$u->step} work_complete={$u->work_profile_complete}\n";
echo "portfolios={$u->portfolios->count()} educations={$u->educations->count()} skills={$u->skills->count()}\n";
echo "about=" . (empty($u->about) ? 'no' : 'yes') . " image=" . (empty($u->image) ? 'no' : 'yes') . "\n";
echo "language=" . (empty($u->language) ? 'no' : 'yes') . " kv={$u->kv} tv={$u->tv}\n";
echo "completion=" . calculateProfileCompletion($u) . "\n";
