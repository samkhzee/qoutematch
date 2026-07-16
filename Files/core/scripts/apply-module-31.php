<?php
/** Module 31 — Admin React: Categories */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 31 — Admin React categories\n" . str_repeat('-', 40) . "\n";
foreach (['resources/js/Pages/Admin/Categories/Index.jsx', 'resources/js/Pages/Admin/Categories/Subcategories.jsx', 'resources/js/Pages/Admin/Categories/Skills.jsx'] as $f) {
    echo file_exists(__DIR__ . '/../' . $f) ? "OK  $f\n" : "MISSING $f\n";
}
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ConfigCategoryController.php');
echo str_contains($c, "Inertia::render('Admin/Categories/") ? "OK  ConfigCategoryController\n" : "CHECK ConfigCategoryController\n";
echo "Module 31 applied.\n";
