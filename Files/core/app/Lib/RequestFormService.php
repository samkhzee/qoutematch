<?php

namespace App\Lib;

use Illuminate\Http\Request;

class RequestFormService
{
    /**
     * Form model casts form_data as object (label-keyed stdClass). Normalize to a list of fields.
     */
    public static function normalizeFormFields(mixed $formData): array
    {
        if ($formData === null) {
            return [];
        }

        if (is_array($formData)) {
            return array_values($formData);
        }

        if ($formData instanceof \stdClass) {
            return array_values((array) $formData);
        }

        if ($formData instanceof \Traversable) {
            return iterator_to_array($formData);
        }

        return [];
    }

    public static function fieldsForFrontend(mixed $formData, ?array $saved = null): array
    {
        $savedByLabel = collect($saved ?? [])->keyBy('label');

        return collect(self::normalizeFormFields($formData))->map(function ($field) use ($savedByLabel) {
            $savedValue = $savedByLabel->get($field->label)['value'] ?? null;
            $existingFileUrl = null;

            if ($field->type === 'file' && $savedValue && ! is_array($savedValue)) {
                $existingFileUrl = route('buyer.download.attachment', encrypt(getFilePath('requestDocuments') . '/' . $savedValue));
            }

            return [
                'name' => $field->name,
                'label' => $field->label,
                'type' => $field->type,
                'isRequired' => $field->is_required === 'required',
                'instruction' => $field->instruction ?? null,
                'options' => $field->options ?? [],
                'extensions' => $field->extensions ?? '',
                'width' => $field->width ?? '12',
                'value' => $savedValue,
                'existingFileUrl' => $existingFileUrl,
            ];
        })->values()->all();
    }

    public static function processSubmission(Request $request, mixed $formData, ?array $existing = null): array
    {
        $existingByLabel = collect($existing ?? [])->keyBy('label');
        $requestForm = [];

        foreach (self::normalizeFormFields($formData) as $data) {
            $label = $data->label;

            if ($data->type === 'file') {
                if ($request->hasFile($label)) {
                    $directory = date('Y/m/d');
                    $path = getFilePath('requestDocuments') . '/' . $directory;
                    $value = $directory . '/' . fileUploader($request->file($label), $path);
                } else {
                    $value = $existingByLabel->get($label)['value'] ?? null;
                }
            } elseif ($data->type === 'checkbox') {
                $value = $request->input($label, []);
            } else {
                $value = $request->input($label);
            }

            $requestForm[] = [
                'name' => $data->name,
                'label' => $label,
                'type' => $data->type,
                'value' => $value,
            ];
        }

        return $requestForm;
    }

    public static function displayValues(?array $requestData, string $downloadRoute = 'buyer.download.attachment'): array
    {
        return collect($requestData ?? [])->map(function ($item) use ($downloadRoute) {
            $value = $item['value'] ?? null;

            if (($item['type'] ?? '') === 'file' && $value) {
                $value = route($downloadRoute, encrypt(getFilePath('requestDocuments') . '/' . $value));
            }

            if (($item['type'] ?? '') === 'checkbox' && is_array($value)) {
                $value = implode(', ', $value);
            }

            return [
                'name' => $item['name'] ?? '',
                'type' => $item['type'] ?? 'text',
                'value' => $value,
                'isFile' => ($item['type'] ?? '') === 'file' && !empty($item['value']),
            ];
        })->filter(fn ($item) => filled($item['value']))->values()->all();
    }
}
