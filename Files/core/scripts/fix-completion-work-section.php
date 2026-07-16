<?php

/**
 * Fix completion_work CMS copy and verify section image path.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$row = App\Models\Frontend::where('data_keys', 'completion_work.content')->first();

if (!$row) {
    echo "completion_work.content not found\n";
    exit(1);
}

$data = (array) $row->data_values;
$data['heading'] = 'Why customers choose us';
$data['subheading'] = 'Finishing work has never been more straightforward';

$image = $data['image'] ?? null;
$imagePath = $image ? base_path('../assets/images/frontend/completion_work/' . $image) : null;

if (!$imagePath || !is_file($imagePath)) {
    $fallbackDir = base_path('../assets/images/frontend/find_task');
    $fallback = collect(glob($fallbackDir . '/*.png'))->first()
        ?: collect(glob(base_path('../assets/images/frontend/banner/*.png')))->first();

    if ($fallback) {
        $destDir = base_path('../assets/images/frontend/completion_work');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = 'qm-completion-' . time() . '.png';
        copy($fallback, $destDir . '/' . $filename);
        $data['image'] = $filename;
        echo "Copied fallback image: {$filename}\n";
    } else {
        echo "Warning: no fallback image found; main photo may show placeholder\n";
    }
} else {
    echo "Completion work image OK: {$image}\n";
}

$row->data_values = $data;
$row->save();

echo "Updated completion_work.content heading/subheading\n";

$elements = [
    'Get matched with verified providers in minutes',
    'Dedicated support when you need help',
    'Compare quotes clearly before you choose',
];

$elementRows = App\Models\Frontend::where('data_keys', 'completion_work.element')->orderBy('id')->get();

foreach ($elements as $index => $step) {
    $element = $elementRows[$index] ?? null;
    if (!$element) {
        $element = new App\Models\Frontend();
        $element->data_keys = 'completion_work.element';
        $element->tempname = activeTemplateName();
    }

    $element->data_values = ['done_step' => $step];
    $element->save();
}

echo 'Updated ' . count($elements) . " completion_work.element rows\n";
echo "Done.\n";
