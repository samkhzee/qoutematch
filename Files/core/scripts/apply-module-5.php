<?php

/**
 * Module 5 — Dynamic quote forms (Blueprint §5 quote fields, §11, §42 #7)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Form;

function upsertQuoteForm(string $act, array $fields): Form
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

    echo "Quote form saved: {$act} (" . count($fields) . " fields)\n";

    return $form;
}

$builderQuote = upsertQuoteForm('quote_builders', [
    ['name' => 'Total Price', 'type' => 'number', 'required' => true, 'width' => '6'],
    ['name' => 'Labour Cost', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'Material Cost', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'VAT Status', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['VAT included', 'VAT excluded', 'Not applicable']],
    ['name' => 'Estimated Start Date', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'Estimated Completion Time', 'type' => 'text', 'required' => true, 'width' => '6', 'instruction' => 'e.g. 2 weeks, 6-8 weeks'],
    ['name' => 'Site Visit Required', 'type' => 'radio', 'required' => true, 'width' => '6', 'options' => ['Yes', 'No']],
    ['name' => 'Payment Schedule', 'type' => 'textarea', 'required' => false, 'width' => '12'],
    ['name' => 'Warranty or Guarantee', 'type' => 'text', 'required' => false, 'width' => '6'],
    ['name' => 'Inclusions', 'type' => 'textarea', 'required' => true, 'width' => '12'],
    ['name' => 'Exclusions', 'type' => 'textarea', 'required' => true, 'width' => '12'],
    ['name' => 'Quote Expiry Date', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'Notes and Assumptions', 'type' => 'textarea', 'required' => false, 'width' => '12'],
]);

$freightQuote = upsertQuoteForm('quote_freight', [
    ['name' => 'Freight Cost', 'type' => 'number', 'required' => true, 'width' => '6'],
    ['name' => 'Customs Clearance Cost', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'Delivery / Haulage Cost', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'Documentation Charges', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'Tail Lift Charge', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'Insurance Cost', 'type' => 'number', 'required' => false, 'width' => '6'],
    ['name' => 'VAT or Duty Handling Notes', 'type' => 'textarea', 'required' => false, 'width' => '12'],
    ['name' => 'Transit Time', 'type' => 'text', 'required' => true, 'width' => '6'],
    ['name' => 'Collection Date', 'type' => 'date', 'required' => false, 'width' => '6'],
    ['name' => 'Estimated Delivery Date', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'Pallet Exchange Included', 'type' => 'radio', 'required' => true, 'width' => '6', 'options' => ['Yes', 'No']],
    ['name' => 'Quote Valid Until', 'type' => 'date', 'required' => true, 'width' => '6'],
    ['name' => 'Inclusions', 'type' => 'textarea', 'required' => true, 'width' => '12'],
    ['name' => 'Exclusions', 'type' => 'textarea', 'required' => true, 'width' => '12'],
    ['name' => 'Notes and Assumptions', 'type' => 'textarea', 'required' => false, 'width' => '12'],
]);

$links = [
    'builders-home-improvement' => $builderQuote->id,
    'freight-forwarding-logistics' => $freightQuote->id,
];

foreach ($links as $slug => $formId) {
    $category = Category::where('slug', $slug)->first();
    if (!$category) {
        echo "Category not found: {$slug}\n";
        continue;
    }

    $category->quote_form_id = $formId;
    $category->save();
    echo "Linked quote form to category: {$slug}\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Module 5 applied.\n";
