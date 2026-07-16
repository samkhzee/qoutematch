<?php

/**
 * Seeds default provider and buyer KYC forms (forms.act = kyc / kyc_buyer).
 *
 * Run standalone: php scripts/seed-kyc-forms.php
 * Included from: scripts/apply-module-9.php
 */
if (!defined('KYC_FORMS_ALREADY_BOOTSTRAPPED')) {
    require __DIR__ . '/../vendor/autoload.php';

    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}

use App\Models\Form;

function upsertKycForm(string $act, array $fields): Form
{
    $formData = [];

    foreach ($fields as $field) {
        $label = titleToKey($field['name']);
        $formData[$label] = [
            'name' => $field['name'],
            'label' => $label,
            'is_required' => $field['required'] ? 'required' : 'optional',
            'instruction' => $field['instruction'] ?? '',
            'extensions' => $field['extensions'] ?? '',
            'options' => $field['options'] ?? [],
            'type' => $field['type'],
            'width' => $field['width'] ?? '12',
        ];
    }

    $form = Form::where('act', $act)->first() ?? new Form();
    $form->act = $act;
    $form->form_data = $formData;
    $form->save();

    echo "KYC form saved: {$act} (" . count($fields) . " fields)\n";

    return $form;
}

upsertKycForm('kyc', [
    ['name' => 'Full Legal Name', 'type' => 'text', 'required' => true, 'width' => '12', 'instruction' => 'Must match your government-issued ID'],
    ['name' => 'Date of Birth', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'ID Document Type', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['Passport', 'National ID', 'Driving Licence']],
    ['name' => 'ID Document Number', 'type' => 'text', 'required' => true, 'width' => '12'],
    ['name' => 'Government ID Upload', 'type' => 'file', 'required' => true, 'width' => '6', 'extensions' => 'pdf,jpg,jpeg,png', 'instruction' => 'Clear photo or scan of your ID'],
    ['name' => 'Proof of Address', 'type' => 'file', 'required' => true, 'width' => '6', 'extensions' => 'pdf,jpg,jpeg,png', 'instruction' => 'Utility bill or bank statement dated within 3 months'],
    ['name' => 'Business Registration', 'type' => 'file', 'required' => false, 'width' => '12', 'extensions' => 'pdf,jpg,jpeg,png', 'instruction' => 'Optional — upload if you operate as a registered company'],
]);

upsertKycForm('kyc_buyer', [
    ['name' => 'Full Legal Name', 'type' => 'text', 'required' => true, 'width' => '12'],
    ['name' => 'Date of Birth', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'ID Document Type', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['Passport', 'National ID', 'Driving Licence']],
    ['name' => 'Government ID Upload', 'type' => 'file', 'required' => true, 'width' => '6', 'extensions' => 'pdf,jpg,jpeg,png'],
    ['name' => 'Proof of Address', 'type' => 'file', 'required' => true, 'width' => '6', 'extensions' => 'pdf,jpg,jpeg,png'],
]);

Illuminate\Support\Facades\Cache::flush();
echo "Default KYC forms applied.\n";
