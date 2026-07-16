<?php

/**
 * Module 28 — Provider match score UI on job browse
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Lib\InertiaResource;
use Illuminate\Support\Facades\Cache;

echo "Module 28 — Provider match score UI\n";
echo str_repeat('-', 40) . "\n";

echo file_exists(__DIR__ . '/../resources/js/Components/Jobs/JobCard.jsx') ? "OK  JobCard.jsx\n" : "MISSING JobCard.jsx\n";

$jobCard = file_get_contents(__DIR__ . '/../app/Lib/InertiaResource.php');
echo str_contains($jobCard, 'matchScore') ? "OK  InertiaResource::jobCard matchScore\n" : "MISSING matchScore in InertiaResource\n";

$explore = file_get_contents(__DIR__ . '/../app/Http/Controllers/JobExploreController.php');
echo str_contains($explore, 'InertiaResource::jobs($jobs, auth()->user())') ? "OK  JobExploreController passes provider\n" : "CHECK JobExploreController\n";

$ref = new ReflectionMethod(InertiaResource::class, 'jobCard');
echo $ref->getNumberOfParameters() >= 2 ? "OK  jobCard accepts provider\n" : "MISSING provider param on jobCard\n";

Cache::flush();

echo "\nModule 28 deliverables:\n";
echo "  Match score bars on /freelance-jobs and job detail for logged-in providers\n";
echo "\nModule 28 applied.\n";
