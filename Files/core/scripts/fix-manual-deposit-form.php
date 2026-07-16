<?php

/**
 * Ensures manual deposit gateways have a linked form (fixes Pay Now 500 error).
 * Usage: php scripts/fix-manual-deposit-form.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Form;
use App\Models\Gateway;

function manualDepositFormData(): array
{
    return [
        'transaction_reference' => [
            'name' => 'Transaction Reference',
            'label' => 'transaction_reference',
            'is_required' => 'optional',
            'instruction' => 'Optional: enter your bank transfer reference number.',
            'extensions' => '',
            'options' => [],
            'type' => 'text',
            'width' => '12',
        ],
    ];
}

$form = Form::where('act', 'manual_deposit')->first();
if (!$form) {
    $form = new Form();
    $form->act = 'manual_deposit';
    $form->form_data = manualDepositFormData();
    $form->save();
    echo "Created manual_deposit form #{$form->id}\n";
} else {
    echo "Using existing manual_deposit form #{$form->id}\n";
}

$fixed = 0;
Gateway::manual()->where(function ($q) {
    $q->whereNull('form_id')->orWhere('form_id', 0);
})->each(function (Gateway $gateway) use ($form, &$fixed) {
    $gateway->form_id = $form->id;
    $gateway->save();
    $fixed++;
    echo "Linked gateway #{$gateway->id} ({$gateway->name}) to form #{$form->id}\n";
});

echo $fixed ? "Fixed {$fixed} gateway(s).\n" : "All manual gateways already have a form.\n";
