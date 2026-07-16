<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Collection;

class QuoteDeadlineService
{
    public const EXPIRED_GRACE_DAYS = 7;

    public static function quoteDeadlineExpired(?Job $job): bool
    {
        if (!$job?->deadline) {
            return false;
        }

        return $job->deadline->copy()->endOfDay()->isPast();
    }

    public static function expiredGraceEndsAt(Job $job): ?\Carbon\Carbon
    {
        if (!$job->deadline) {
            return null;
        }

        return $job->deadline->copy()->addDays(self::EXPIRED_GRACE_DAYS)->endOfDay();
    }

    public static function inExpiredGracePeriod(?Job $job): bool
    {
        if (!$job?->deadline || !self::quoteDeadlineExpired($job)) {
            return false;
        }

        $graceEnd = self::expiredGraceEndsAt($job);

        return $graceEnd && now()->lte($graceEnd);
    }

    public static function expiredGraceDaysRemaining(?Job $job): int
    {
        if (!$job?->deadline || !self::quoteDeadlineExpired($job)) {
            return 0;
        }

        $graceEnd = self::expiredGraceEndsAt($job);
        if (!$graceEnd || now()->gt($graceEnd)) {
            return 0;
        }

        return max(0, (int) today()->diffInDays($graceEnd->copy()->startOfDay(), false));
    }

    public static function expiredListingLabel(?Job $job): ?string
    {
        if (!self::inExpiredGracePeriod($job)) {
            return null;
        }

        $days = self::expiredGraceDaysRemaining($job);

        if ($days <= 0) {
            return __('Quote deadline expired — last day on Find Jobs');
        }

        $dayWord = $days === 1 ? 'day' : 'days';

        return __('Quote deadline expired — listed for :days more :dayWord', [
            'days' => $days,
            'dayWord' => $dayWord,
        ]);
    }

    public static function isOpenForListing(Job $job): bool
    {
        if ((int) $job->status !== Status::JOB_PUBLISH) {
            return false;
        }

        if ((int) $job->is_approved !== Status::JOB_APPROVED) {
            return false;
        }

        if ($job->bids()->where('status', Status::BID_ACCEPTED)->exists()) {
            return false;
        }

        if (!self::quoteDeadlineExpired($job)) {
            return true;
        }

        return self::inExpiredGracePeriod($job);
    }

    public static function isOpenForNewQuotes(Job $job): bool
    {
        if ((int) $job->status !== Status::JOB_PUBLISH) {
            return false;
        }

        if ((int) $job->is_approved !== Status::JOB_APPROVED) {
            return false;
        }

        if ($job->bids()->where('status', Status::BID_ACCEPTED)->exists()) {
            return false;
        }

        if (!self::quoteDeadlineExpired($job)) {
            return true;
        }

        return self::inExpiredGracePeriod($job);
    }

    /**
     * @return Collection<int, Job>
     */
    public static function jobsNeedingExpiryNotification(): Collection
    {
        $graceStart = today()->subDays(self::EXPIRED_GRACE_DAYS);

        return Job::query()
            ->published()
            ->approved()
            ->whereDoesntHave('bids', fn ($q) => $q->where('status', Status::BID_ACCEPTED))
            ->whereDate('deadline', '<', today())
            ->whereDate('deadline', '>=', $graceStart)
            ->whereNull('deadline_expired_notified_at')
            ->with(['buyer', 'category'])
            ->get()
            ->filter(fn (Job $job) => self::quoteDeadlineExpired($job));
    }

    public static function notifyExpired(Job $job): void
    {
        if ($job->deadline_expired_notified_at) {
            return;
        }

        $job->deadline_expired_notified_at = now();
        $job->save();

        $adminNotification = new AdminNotification();
        $adminNotification->buyer_id = $job->buyer_id;
        $adminNotification->title = 'Quote deadline expired — ' . strLimit($job->title, 55);
        $adminNotification->click_url = urlPath('admin.jobs.details', $job->id);
        $adminNotification->save();

        $payload = [
            'request'     => $job->title,
            'deadline'    => showDateTime($job->deadline, 'd M, Y'),
            'grace_days'  => (string) self::EXPIRED_GRACE_DAYS,
            'link'        => route('explore.bid.job', $job->slug),
        ];

        self::matchingProviders($job)->chunkById(100, function ($providers) use ($payload) {
            foreach ($providers as $provider) {
                notify($provider, 'QUOTE_DEADLINE_EXPIRED', $payload);
            }
        });
    }

    public static function processExpiryNotifications(): int
    {
        $count = 0;

        foreach (self::jobsNeedingExpiryNotification() as $job) {
            self::notifyExpired($job);
            $count++;
        }

        return $count;
    }

    public static function processExpiryNotificationsIfNeeded(): void
    {
        $cacheKey = 'quote_deadline_expiry_notify_' . today()->toDateString();

        if (cache()->get($cacheKey)) {
            return;
        }

        self::processExpiryNotifications();
        cache()->put($cacheKey, true, now()->addDay());
    }

    protected static function matchingProviders(Job $job)
    {
        $subId = (int) $job->subcategory_id;

        return User::query()
            ->where('status', Status::USER_ACTIVE)
            ->where('provider_approved', Status::YES)
            ->where(function ($query) use ($subId) {
                $query->whereJsonContains('subcategory_ids', $subId)
                    ->orWhereJsonContains('subcategory_ids', (string) $subId)
                    ->orWhereNull('subcategory_ids')
                    ->orWhere('subcategory_ids', '[]')
                    ->orWhere('subcategory_ids', '');
            });
    }
}
