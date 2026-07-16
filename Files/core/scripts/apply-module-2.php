<?php

/**
 * Module 2 — Launch categories (Blueprint §5, §10, §28)
 * Seeds Builders & Home Improvement + Freight Forwarding & Logistics with subcategories.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

$assetsRoot = realpath(__DIR__ . '/../../assets');
$categoryDir = "{$assetsRoot}/images/category";
if (!is_dir($categoryDir)) {
    mkdir($categoryDir, 0755, true);
}

function copyCategoryImage(string $assetsRoot, string $sourceFolder, string $destName): string
{
    global $categoryDir;

    $srcDir = "{$assetsRoot}/images/frontend/{$sourceFolder}";
    if (!is_dir($srcDir)) {
        return '';
    }

    $files = glob("{$srcDir}/*.png");
    if (!$files) {
        return '';
    }

    usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));
    $filename = $destName . '-' . time() . '.png';
    copy($files[0], "{$categoryDir}/{$filename}");
    echo "Image: {$filename} (from {$sourceFolder})\n";

    return $filename;
}

function upsertCategory(array $data, array $subcategories): void
{
    $category = Category::where('slug', $data['slug'])->first() ?? new Category();

    $category->name = $data['name'];
    $category->slug = $data['slug'];
    $category->description = $data['description'];
    $category->seo_title = $data['seo_title'];
    $category->seo_description = $data['seo_description'];
    $category->status = Status::ENABLE;
    $category->is_featured = Status::YES;

    if (!empty($data['image'])) {
        $category->image = $data['image'];
    }

    $category->save();
    echo "Category: {$category->name}\n";

    foreach ($subcategories as $sub) {
        $row = Subcategory::where('category_id', $category->id)
            ->where('slug', $sub['slug'])
            ->first() ?? new Subcategory();

        $row->category_id = $category->id;
        $row->name = $sub['name'];
        $row->slug = $sub['slug'];
        $row->description = $sub['description'] ?? null;
        $row->seo_title = $sub['seo_title'] ?? "{$sub['name']} Quotes | QuoteMatch";
        $row->seo_description = $sub['seo_description'] ?? "Compare {$sub['name']} quotes from verified providers on QuoteMatch.";
        $row->status = Status::ENABLE;
        $row->is_featured = Status::YES;
        $row->save();
    }

    echo "  → " . count($subcategories) . " subcategories\n";
}

$buildersImage = copyCategoryImage($assetsRoot, 'user_types', 'builders-home-improvement')
    ?: copyCategoryImage($assetsRoot, 'banner', 'builders-home-improvement');
$freightImage = copyCategoryImage($assetsRoot, 'account', 'freight-logistics')
    ?: copyCategoryImage($assetsRoot, 'find_task', 'freight-logistics');

upsertCategory([
    'name' => 'Builders and Home Improvement',
    'slug' => 'builders-home-improvement',
    'description' => 'Post building, renovation, and home improvement requirements. Compare quotes from verified builders, tradespeople, and specialists.',
    'seo_title' => 'Compare Builder Quotes | QuoteMatch',
    'seo_description' => 'Get free quotes from verified builders and tradespeople. Compare kitchen fitting, extensions, plumbing, electrical work, and more.',
    'image' => $buildersImage,
], [
    ['name' => 'General building work', 'slug' => 'general-building-work'],
    ['name' => 'Extensions', 'slug' => 'extensions'],
    ['name' => 'Loft conversions', 'slug' => 'loft-conversions'],
    ['name' => 'House renovation', 'slug' => 'house-renovation'],
    ['name' => 'Kitchen fitting', 'slug' => 'kitchen-fitting'],
    ['name' => 'Bathroom fitting', 'slug' => 'bathroom-fitting'],
    ['name' => 'Joinery and carpentry', 'slug' => 'joinery-and-carpentry'],
    ['name' => 'Painting and decorating', 'slug' => 'painting-and-decorating'],
    ['name' => 'Plastering', 'slug' => 'plastering'],
    ['name' => 'Roofing', 'slug' => 'roofing'],
    ['name' => 'Plumbing', 'slug' => 'plumbing'],
    ['name' => 'Electrical work', 'slug' => 'electrical-work'],
    ['name' => 'Flooring', 'slug' => 'flooring'],
    ['name' => 'Windows and doors', 'slug' => 'windows-and-doors'],
    ['name' => 'Landscaping', 'slug' => 'landscaping'],
    ['name' => 'Driveways', 'slug' => 'driveways'],
    ['name' => 'Demolition', 'slug' => 'demolition'],
    ['name' => 'Waste removal', 'slug' => 'waste-removal'],
    ['name' => 'Air conditioning and heat pump installation', 'slug' => 'air-conditioning-heat-pump'],
    ['name' => 'Stairs and bespoke joinery', 'slug' => 'stairs-bespoke-joinery'],
]);

upsertCategory([
    'name' => 'Freight Forwarding and Logistics',
    'slug' => 'freight-forwarding-logistics',
    'description' => 'Compare freight, customs, and logistics quotes from verified forwarders. Air, sea, road, warehousing, and delivery services.',
    'seo_title' => 'Compare Freight & Logistics Quotes | QuoteMatch',
    'seo_description' => 'Get quotes for air freight, sea freight, customs clearance, pallet delivery, warehousing, and more from verified logistics providers.',
    'image' => $freightImage,
], [
    ['name' => 'Air freight', 'slug' => 'air-freight'],
    ['name' => 'Sea freight', 'slug' => 'sea-freight'],
    ['name' => 'Road freight', 'slug' => 'road-freight'],
    ['name' => 'Customs clearance', 'slug' => 'customs-clearance'],
    ['name' => 'Import service', 'slug' => 'import-service'],
    ['name' => 'Export service', 'slug' => 'export-service'],
    ['name' => 'Pallet delivery', 'slug' => 'pallet-delivery'],
    ['name' => 'Container delivery', 'slug' => 'container-delivery'],
    ['name' => 'Warehousing', 'slug' => 'warehousing'],
    ['name' => 'Fulfilment', 'slug' => 'fulfilment'],
    ['name' => 'Amazon FBA delivery', 'slug' => 'amazon-fba-delivery'],
    ['name' => 'Tail lift delivery', 'slug' => 'tail-lift-delivery'],
    ['name' => 'Heavy goods delivery', 'slug' => 'heavy-goods-delivery'],
    ['name' => 'Courier service', 'slug' => 'courier-service'],
    ['name' => 'Duty deferment service', 'slug' => 'duty-deferment-service'],
    ['name' => 'DDP/DDU shipping', 'slug' => 'ddp-ddu-shipping'],
    ['name' => 'Pallet exchange service', 'slug' => 'pallet-exchange-service'],
]);

Illuminate\Support\Facades\Cache::flush();
echo "Module 2 applied: " . Category::count() . " categories, " . Subcategory::count() . " subcategories.\n";
