<?php
/** Module 38 — Admin React: Marketplace forms index */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 38 — Admin React marketplace forms\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/MarketplaceFormController.php');
echo str_contains($c, "Inertia::render('Admin/MarketplaceForms/Index'") ? "OK  forms index Inertia\n" : "CHECK index\n";
echo str_contains($c, "InertiaBridge::admin('admin.marketplace_forms.edit'") ? "OK  form edit remains Blade\n" : "INFO edit migrated\n";
echo "Module 38 applied.\n";
