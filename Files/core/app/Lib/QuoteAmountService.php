<?php

namespace App\Lib;

use App\Models\Job;
use Illuminate\Http\Request;

class QuoteAmountService
{
    public const EXPLICIT_TOTAL_FIELD_NAMES = ['Total Price'];

    /**
     * Quote forms with an explicit "Total Price" field use that value directly.
     * Freight-style forms total every numeric line item (freight, customs, haulage, etc.).
     */
    public static function resolveBidAmount(Request $request, Job $job, ?array &$quoteData): float
    {
        $explicitTotal = collect($quoteData ?? [])->first(
            fn ($item) => in_array($item['name'] ?? '', self::EXPLICIT_TOTAL_FIELD_NAMES, true)
        );

        if ($explicitTotal) {
            $bidAmount = $request->filled('bid_amount') ? (float) $request->bid_amount : null;

            if ($bidAmount !== null && $quoteData) {
                $quoteData = collect($quoteData)->map(function ($item) use ($bidAmount) {
                    if (in_array($item['name'] ?? '', self::EXPLICIT_TOTAL_FIELD_NAMES, true)) {
                        $item['value'] = $bidAmount;
                    }

                    return $item;
                })->all();
            } elseif ($bidAmount === null && is_numeric($explicitTotal['value'] ?? null)) {
                $bidAmount = (float) $explicitTotal['value'];
            }

            return max(0.01, $bidAmount ?? (float) $job->budget);
        }

        $costFields = self::costLineItems($quoteData ?? []);
        if ($costFields->isNotEmpty()) {
            return max(0.01, (float) $costFields->sum(fn ($item) => (float) $item['value']));
        }

        $bidAmount = $request->filled('bid_amount') ? (float) $request->bid_amount : (float) $job->budget;

        return max(0.01, $bidAmount);
    }

    public static function costLineItems(?array $quoteData): \Illuminate\Support\Collection
    {
        return collect($quoteData ?? [])->filter(
            fn ($item) => ($item['type'] ?? null) === 'number' && is_numeric($item['value'] ?? null) && (float) $item['value'] > 0
        )->map(fn ($item) => [
            'name' => $item['name'] ?? '',
            'value' => (float) $item['value'],
            'valueFormatted' => showAmount((float) $item['value']),
        ])->values();
    }

    public static function breakdown(?array $quoteData): array
    {
        $explicitTotal = collect($quoteData ?? [])->first(
            fn ($item) => in_array($item['name'] ?? '', self::EXPLICIT_TOTAL_FIELD_NAMES, true)
        );

        $costLines = self::costLineItems($quoteData)->all();
        $summedTotal = array_sum(array_column($costLines, 'value'));

        return [
            'hasExplicitTotal' => (bool) $explicitTotal,
            'isSummedTotal' => !$explicitTotal && count($costLines) > 0,
            'costLines' => $costLines,
            'computedTotal' => $explicitTotal
                ? (is_numeric($explicitTotal['value'] ?? null) ? (float) $explicitTotal['value'] : 0)
                : $summedTotal,
            'computedTotalFormatted' => showAmount(
                $explicitTotal && is_numeric($explicitTotal['value'] ?? null)
                    ? (float) $explicitTotal['value']
                    : $summedTotal
            ),
        ];
    }

    public static function formUsesSummedTotal(array $quoteFields): bool
    {
        $hasExplicit = collect($quoteFields)->contains(
            fn ($field) => in_array($field['name'] ?? '', self::EXPLICIT_TOTAL_FIELD_NAMES, true)
        );
        $hasCostFields = collect($quoteFields)->contains(fn ($field) => ($field['type'] ?? null) === 'number');

        return !$hasExplicit && $hasCostFields;
    }
}
