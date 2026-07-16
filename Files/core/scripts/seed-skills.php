<?php

/**
 * Seed provider skills for QuoteMatch, scoped to categories.
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Skill;
use App\Constants\Status;
use Illuminate\Support\Facades\Schema;

if (! Schema::hasColumn('skills', 'category_id')) {
    Schema::table('skills', function ($table) {
        $table->unsignedInteger('category_id')->nullable()->after('name');
    });
    echo "Added skills.category_id column\n";
}

$builders = Category::where('name', 'like', '%Builder%')->value('id')
    ?: Category::orderBy('id')->value('id');
$freight = Category::where('name', 'like', '%Freight%')->value('id')
    ?: Category::orderBy('id')->skip(1)->value('id');

$byCategory = [
    $builders => [
        'Kitchen Fitting', 'Bathroom Installation', 'Home Extensions', 'Loft Conversions',
        'Roofing', 'Plumbing', 'Electrical Work', 'Plastering', 'Tiling', 'Painting & Decorating',
        'Bricklaying', 'Groundworks', 'Project Management', 'Architectural Design', 'Building Surveying',
        'Flooring',
    ],
    $freight => [
        'Sea Freight', 'Air Freight', 'Road Haulage', 'Customs Clearance', 'Warehousing',
        'Freight Forwarding', 'Import/Export Documentation', 'Last Mile Delivery', 'Cold Chain Logistics',
        'Container Shipping', 'Supply Chain Management',
    ],
];

$created = 0;
$updated = 0;

foreach ($byCategory as $categoryId => $names) {
    if (! $categoryId) {
        continue;
    }

    foreach ($names as $name) {
        $skill = Skill::where('name', $name)->first();
        if ($skill) {
            if ((int) $skill->category_id !== (int) $categoryId) {
                $skill->category_id = $categoryId;
                $skill->save();
                $updated++;
            }
            continue;
        }

        $skill = new Skill();
        $skill->name = $name;
        $skill->category_id = $categoryId;
        $skill->status = Status::ENABLE;
        $skill->save();
        $created++;
    }
}

echo "Skills ready. Created: {$created}, updated category: {$updated}, total active: " . Skill::active()->count() . "\n";
