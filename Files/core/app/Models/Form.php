<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Form extends Model
{
    public $casts = [
        'form_data' => 'object',
    ];

    public function scopeMarketplace($query)
    {
        return $query->where(function ($builder) {
            $builder->where('act', 'like', 'request_%')
                ->orWhere('act', 'like', 'quote_%');
        });
    }

    public function scopeRequestForms($query)
    {
        return $query->where('act', 'like', 'request_%');
    }

    public function scopeQuoteForms($query)
    {
        return $query->where('act', 'like', 'quote_%');
    }

    public function isRequestForm(): bool
    {
        return str_starts_with((string) $this->act, 'request_');
    }

    public function isQuoteForm(): bool
    {
        return str_starts_with((string) $this->act, 'quote_');
    }

    public function fieldCount(): int
    {
        if ($this->form_data === null) {
            return 0;
        }

        return count((array) $this->form_data);
    }

    public function displayLabel(): string
    {
        $act = (string) $this->act;
        $suffix = preg_replace('/^(request|quote)_/', '', $act);

        return ucwords(str_replace('_', ' ', $suffix ?: $act));
    }

    public function jsonData(): Attribute
    {
        return new Attribute(
            get: fn () => [
                'type' => $this->type,
                'is_required' => $this->is_required,
                'label' => $this->name,
                'extensions' => $this->extensions ?? 'null',
                'options' => json_encode($this->options),
                'old_id' => '',
            ],
        );
    }
}
