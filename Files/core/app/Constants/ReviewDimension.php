<?php

namespace App\Constants;

class ReviewDimension
{
    public const QUALITY = 'quality';

    public const COMMUNICATION = 'communication';

    public const TIMELINESS = 'timeliness';

    public const VALUE = 'value';

    public static function all(): array
    {
        return [
            self::QUALITY => 'Quality of work',
            self::COMMUNICATION => 'Communication',
            self::TIMELINESS => 'Timeliness',
            self::VALUE => 'Value for money',
        ];
    }

    public static function keys(): array
    {
        return array_keys(self::all());
    }
}
