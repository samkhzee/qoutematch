<?php

namespace App\Lib;

use App\Models\Buyer;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class NotificationInboxService
{
    public static function unreadCountForBuyer(Buyer $buyer): int
    {
        return self::unreadQuery('buyer_id', $buyer->id)->distinct('subject')->count('subject');
    }

    public static function unreadCountForProvider(User $user): int
    {
        return self::unreadQuery('user_id', $user->id)->distinct('subject')->count('subject');
    }

    public static function unreadSummaryForBuyer(Buyer $buyer): array
    {
        return self::summary('buyer_id', $buyer->id);
    }

    public static function unreadSummaryForProvider(User $user): array
    {
        return self::summary('user_id', $user->id);
    }

    public static function markAllReadForBuyer(Buyer $buyer): void
    {
        self::unreadQuery('buyer_id', $buyer->id)->update(['user_read' => 1]);
    }

    public static function markAllReadForProvider(User $user): void
    {
        self::unreadQuery('user_id', $user->id)->update(['user_read' => 1]);
    }

    private static function unreadQuery(string $column, int $id): Builder
    {
        return NotificationLog::query()
            ->where($column, $id)
            ->where('user_read', 0);
    }

    private static function summary(string $column, int $id): array
    {
        $latest = self::unreadQuery($column, $id)->orderByDesc('id')->first();

        return [
            'count' => self::unreadQuery($column, $id)->distinct('subject')->count('subject'),
            'subject' => $latest?->subject,
            'preview' => $latest ? strLimit(strip_tags((string) $latest->message), 80) : null,
        ];
    }
}
