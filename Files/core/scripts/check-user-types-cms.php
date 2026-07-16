<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = App\Models\Frontend::where('data_keys', 'like', 'user_types.%')->get();
foreach ($rows as $row) {
    echo $row->data_keys . ' tempname=' . var_export($row->tempname, true) . PHP_EOL;
}

$banner = App\Models\Frontend::where('data_keys', 'banner.content')->first();
echo 'banner tempname=' . var_export($banner?->tempname, true) . PHP_EOL;
echo 'activeTemplate=' . activeTemplateName() . PHP_EOL;
