<?php

/**
 * Module 11 — Admin form builder (Blueprint §11, §28)
 *
 * Request and quote forms are managed in Admin → Manage Categories → Form Builder.
 * This script verifies seeded forms from Modules 4–5 exist and reports category links.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Form;

$expectedActs = [
    'request_builders',
    'request_freight',
    'quote_builders',
    'quote_freight',
];

echo "Module 11 — Admin form builder\n";
echo str_repeat('-', 40) . "\n";

foreach ($expectedActs as $act) {
    $form = Form::where('act', $act)->first();
    if ($form) {
        echo "OK  {$act} ({$form->fieldCount()} fields)\n";
    } else {
        echo "MISSING {$act} — run apply-module-4.php and apply-module-5.php\n";
    }
}

echo "\nCategory form links:\n";

Category::query()
    ->with(['requestForm', 'quoteForm'])
    ->orderBy('name')
    ->get()
    ->each(function (Category $category) {
        $request = $category->requestForm?->act ?? '—';
        $quote = $category->quoteForm?->act ?? '—';
        echo "  {$category->name}: request={$request}, quote={$quote}\n";
    });

echo "\nAdmin UI: /admin/marketplace-forms\n";
echo "Done.\n";
