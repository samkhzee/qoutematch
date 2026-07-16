<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\NotificationLog;
use App\Models\ProviderSubscription;

class SubscriptionExpiryService
{
    public static function process(): array
    {
        return [
            'expired' => self::expireSubscriptions(),
            'expiring_soon' => self::notifyExpiringSoon(),
        ];
    }

    public static function expireSubscriptions(): int
    {
        if (!LeadCreditService::subscriptionModeEnabled()) {
            return 0;
        }

        $subscriptions = ProviderSubscription::query()
            ->with(['user', 'plan'])
            ->where('status', Status::SUBSCRIPTION_ACTIVE)
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            $subscription->status = Status::SUBSCRIPTION_EXPIRED;
            $subscription->save();

            $user = $subscription->user;
            if (!$user) {
                continue;
            }

            notify($user, 'SUBSCRIPTION_EXPIRED', [
                'plan' => $subscription->plan?->name ?? 'Subscription',
                'expired' => $subscription->expires_at?->format('d M Y') ?? now()->format('d M Y'),
                'credits_url' => route('user.lead.credits.index'),
            ]);

            $count++;
        }

        return $count;
    }

    public static function notifyExpiringSoon(int $days = 3): int
    {
        if (!LeadCreditService::subscriptionModeEnabled()) {
            return 0;
        }

        $subscriptions = ProviderSubscription::query()
            ->with(['user', 'plan'])
            ->where('status', Status::SUBSCRIPTION_ACTIVE)
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays($days))
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;
            if (!$user) {
                continue;
            }

            $alreadySent = NotificationLog::query()
                ->where('user_id', $user->id)
                ->where('notification_type', 'SUBSCRIPTION_EXPIRING')
                ->where('created_at', '>=', now()->subDays($days + 1))
                ->exists();

            if ($alreadySent) {
                continue;
            }

            notify($user, 'SUBSCRIPTION_EXPIRING', [
                'plan' => $subscription->plan?->name ?? 'Subscription',
                'expires' => $subscription->expires_at?->format('d M Y') ?? '',
                'days' => max(1, now()->diffInDays($subscription->expires_at)),
                'credits_url' => route('user.lead.credits.index'),
            ]);

            $count++;
        }

        return $count;
    }
}
