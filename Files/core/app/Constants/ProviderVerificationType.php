<?php

namespace App\Constants;

class ProviderVerificationType
{
    public const INSURANCE = 'insurance';
    public const COMPANY = 'company';
    public const LICENCE = 'licence';

    public static function all(): array
    {
        return [
            self::INSURANCE,
            self::COMPANY,
            self::LICENCE,
        ];
    }

    public static function label(string $type): string
    {
        return match ($type) {
            self::INSURANCE => 'Insured',
            self::COMPANY => 'Company verified',
            self::LICENCE => 'Trade licence',
            default => ucfirst($type),
        };
    }

    public static function description(string $type): string
    {
        return match ($type) {
            self::INSURANCE => 'Upload your public liability insurance certificate.',
            self::COMPANY => 'Upload company registration or incorporation proof.',
            self::LICENCE => 'Upload your trade licence or professional certificate.',
            default => '',
        };
    }

    public static function icon(string $type): string
    {
        return match ($type) {
            self::INSURANCE => 'las la-shield-alt',
            self::COMPANY => 'las la-building',
            self::LICENCE => 'las la-certificate',
            default => 'las la-check-circle',
        };
    }
}
