<?php

/**
 * Module 4 — Dynamic request forms (Blueprint §5 fields, §11, §24)
 * Seeds builder + freight category request forms and links them to parent categories.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Form;

function upsertRequestForm(string $act, array $fields): Form
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

    echo "Form saved: {$act} (" . count($fields) . " fields)\n";

    return $form;
}

$builderForm = upsertRequestForm('request_builders', [
    ['name' => 'Postcode', 'type' => 'text', 'required' => true, 'width' => '6', 'instruction' => 'Where is the project located?'],
    ['name' => 'Property Type', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['House', 'Flat', 'Bungalow', 'Commercial', 'Other']],
    ['name' => 'Property Age', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['New build', '0-10 years', '10-50 years', '50+ years']],
    ['name' => 'Project Timeline', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['ASAP', 'Within 1 month', '1-3 months', '3-6 months', 'Flexible']],
    ['name' => 'Site Visit Required', 'type' => 'radio', 'required' => true, 'width' => '6', 'options' => ['Yes', 'No']],
    ['name' => 'Plans / Drawings Upload', 'type' => 'file', 'required' => false, 'width' => '6', 'extensions' => 'pdf,jpg,jpeg,png'],
    ['name' => 'Additional Requirements', 'type' => 'textarea', 'required' => false, 'width' => '12', 'instruction' => 'Access restrictions, materials preferences, or other notes'],
]);

$freightForm = upsertRequestForm('request_freight', [
    ['name' => 'Origin City', 'type' => 'text', 'required' => true, 'width' => '6'],
    ['name' => 'Origin Country', 'type' => 'text', 'required' => true, 'width' => '6'],
    ['name' => 'Destination City', 'type' => 'text', 'required' => true, 'width' => '6'],
    ['name' => 'Destination Country', 'type' => 'text', 'required' => true, 'width' => '6'],
    ['name' => 'Incoterms', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['EXW', 'FOB', 'CIF', 'DAP', 'DDP', 'Other']],
    ['name' => 'Shipment Type', 'type' => 'select', 'required' => true, 'width' => '6', 'options' => ['Sea freight', 'Air freight', 'Road freight', 'Multimodal']],
    ['name' => 'Gross Weight (kg)', 'type' => 'number', 'required' => true, 'width' => '6', 'instruction' => 'Approximate total weight in kilograms'],
    ['name' => 'Dimensions / CBM', 'type' => 'text', 'required' => false, 'width' => '6', 'instruction' => 'e.g. 120 x 80 x 60 cm or 2.5 CBM'],
    ['name' => 'Commodity Description', 'type' => 'textarea', 'required' => true, 'width' => '12'],
    ['name' => 'Customs Clearance Needed', 'type' => 'radio', 'required' => true, 'width' => '6', 'options' => ['Yes', 'No', 'Unsure']],
    ['name' => 'Target Delivery Date', 'type' => 'date', 'required' => false, 'width' => '6'],
    ['name' => 'Commercial Invoice / Packing List', 'type' => 'file', 'required' => false, 'width' => '12', 'extensions' => 'pdf,jpg,jpeg,png,doc,docx'],
]);

$links = [
    'builders-home-improvement' => $builderForm->id,
    'freight-forwarding-logistics' => $freightForm->id,
];

foreach ($links as $slug => $formId) {
    $category = Category::where('slug', $slug)->first();
    if (!$category) {
        echo "Category not found: {$slug}\n";
        continue;
    }

    $category->request_form_id = $formId;
    $category->save();
    echo "Linked form to category: {$category->name}\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Module 4 request forms applied.\n";
