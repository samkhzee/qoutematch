<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$updated = App\Models\Frontend::where('data_keys', 'like', 'user_types.%')
    ->update(['tempname' => activeTemplateName()]);

Illuminate\Support\Facades\Cache::flush();

echo "Updated {$updated} user_types rows with tempname=" . activeTemplateName() . "\n";

$content = getContent('user_types.content', true);
echo $content ? "getContent now works\n" : "getContent still null\n";
