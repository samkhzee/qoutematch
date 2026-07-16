<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = App\Models\Frontend::all(['id', 'data_keys', 'data_values']);
foreach ($rows as $row) {
    $json = json_encode($row->data_values);
    if (preg_match('/\.(png|jpg|jpeg|svg|webp)/i', $json)) {
        echo $row->data_keys . "\n";
        echo $json . "\n\n";
    }
}
