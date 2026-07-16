<?php

namespace App\Lib;

use App\Constants\ProviderVerificationType;
use App\Constants\Status;
use App\Models\ProviderVerification;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class VerificationBadgeService
{
    public static function documentTypes(): array
    {
        return ProviderVerificationType::all();
    }

    public static function isIdentityVerified(User $user): bool
    {
        return (int) $user->kv === Status::KYC_VERIFIED;
    }

    public static function isProviderApproved(User $user): bool
    {
        return (bool) $user->provider_approved;
    }

    public static function approvedRecord(User $user, string $type): ?ProviderVerification
    {
        return $user->relationLoaded('providerVerifications')
            ? $user->providerVerifications->first(fn (ProviderVerification $row) => $row->type === $type && $row->isApproved())
            : ProviderVerification::query()->approved()->where('user_id', $user->id)->where('type', $type)->first();
    }

    public static function hasApprovedInsurance(User $user): bool
    {
        return (bool) self::approvedRecord($user, ProviderVerificationType::INSURANCE);
    }

    public static function hasApprovedCompany(User $user): bool
    {
        return (bool) self::approvedRecord($user, ProviderVerificationType::COMPANY);
    }

    public static function hasApprovedLicence(User $user): bool
    {
        return (bool) self::approvedRecord($user, ProviderVerificationType::LICENCE);
    }

    public static function scopeHasApprovedInsurance(Builder $query): Builder
    {
        return $query->whereHas('providerVerifications', function (Builder $inner) {
            $inner->approved()->where('type', ProviderVerificationType::INSURANCE);
        });
    }

    public static function scopeHasApprovedCompany(Builder $query): Builder
    {
        return $query->whereHas('providerVerifications', function (Builder $inner) {
            $inner->approved()->where('type', ProviderVerificationType::COMPANY);
        });
    }

    public static function scopeHasApprovedLicence(Builder $query): Builder
    {
        return $query->whereHas('providerVerifications', function (Builder $inner) {
            $inner->approved()->where('type', ProviderVerificationType::LICENCE);
        });
    }

    public static function scopeFullyVerified(Builder $query): Builder
    {
        return $query->where('provider_approved', true)
            ->where('kv', Status::KYC_VERIFIED)
            ->whereHas('providerVerifications', function (Builder $inner) {
                $inner->approved()->where('type', ProviderVerificationType::INSURANCE);
            });
    }

    public static function badgesForUser(User $user): array
    {
        $badges = [];

        if (self::isProviderApproved($user)) {
            $badges[] = [
                'key' => 'approved',
                'label' => __('Approved provider'),
                'icon' => 'las la-user-check',
                'tone' => 'primary',
            ];
        }

        if (self::isIdentityVerified($user)) {
            $badges[] = [
                'key' => 'identity',
                'label' => __('ID verified'),
                'icon' => 'las la-id-card',
                'tone' => 'success',
            ];
        }

        foreach (ProviderVerificationType::all() as $type) {
            if (!self::approvedRecord($user, $type)) {
                continue;
            }

            $badges[] = [
                'key' => $type,
                'label' => ProviderVerificationType::label($type),
                'icon' => ProviderVerificationType::icon($type),
                'tone' => $type === ProviderVerificationType::INSURANCE ? 'success' : 'info',
            ];
        }

        return $badges;
    }

    public static function providerFormRows(User $user): array
    {
        $records = $user->providerVerifications->keyBy('type');

        return collect(ProviderVerificationType::all())->map(function (string $type) use ($records) {
            /** @var ProviderVerification|null $record */
            $record = $records->get($type);

            return [
                'type' => $type,
                'label' => ProviderVerificationType::label($type),
                'description' => ProviderVerificationType::description($type),
                'icon' => ProviderVerificationType::icon($type),
                'status' => $record?->status ?? null,
                'statusLabel' => $record?->statusLabel() ?? __('Not submitted'),
                'referenceNumber' => $record?->reference_number,
                'expiresAt' => $record?->expires_at?->format('Y-m-d'),
                'adminNote' => $record?->admin_note,
                'documentUrl' => $record?->documentUrl(),
                'canSubmit' => !$record || (int) $record->status === Status::VERIFICATION_REJECTED,
            ];
        })->values()->all();
    }

    public static function profileVerificationSummary(User $user): array
    {
        $records = $user->relationLoaded('providerVerifications')
            ? $user->providerVerifications->keyBy('type')
            : ProviderVerification::query()->where('user_id', $user->id)->get()->keyBy('type');

        $items = [
            [
                'key' => 'provider_approved',
                'title' => __('Approved provider'),
                'text' => self::isProviderApproved($user) ? __('Verified by admin') : __('Pending admin approval'),
                'verified' => self::isProviderApproved($user),
                'icon' => 'las la-user-check',
            ],
            [
                'key' => 'identity',
                'title' => __('ID verified'),
                'text' => match ((int) $user->kv) {
                    Status::KYC_VERIFIED => __('Identity documents verified'),
                    Status::KYC_PENDING => __('Pending review'),
                    default => __('Not submitted'),
                },
                'verified' => self::isIdentityVerified($user),
                'icon' => 'las la-id-card',
            ],
        ];

        foreach (ProviderVerificationType::all() as $type) {
            /** @var ProviderVerification|null $record */
            $record = $records->get($type);

            $items[] = [
                'key' => $type,
                'title' => ProviderVerificationType::label($type),
                'text' => $record?->isApproved()
                    ? __('Approved')
                    : ($record ? $record->statusLabel() : __('Not submitted')),
                'verified' => (bool) $record?->isApproved(),
                'icon' => ProviderVerificationType::icon($type),
            ];
        }

        $items[] = [
            'key' => 'email',
            'title' => __('Verified email'),
            'text' => (int) $user->ev === Status::VERIFIED ? __('Email verified') : __('Not verified'),
            'verified' => (int) $user->ev === Status::VERIFIED,
            'icon' => 'las la-envelope',
        ];

        $items[] = [
            'key' => 'mobile',
            'title' => __('Verified mobile'),
            'text' => (int) $user->sv === Status::VERIFIED ? __('Mobile verified') : __('Not verified'),
            'verified' => (int) $user->sv === Status::VERIFIED,
            'icon' => 'las la-phone',
        ];

        $items[] = [
            'key' => 'profile',
            'title' => (int) $user->work_profile_complete === Status::YES ? __('Profile verified') : __('Profile incomplete'),
            'text' => (int) $user->work_profile_complete === Status::YES
                ? __('Work profile completed')
                : __('Profile not yet complete'),
            'verified' => (int) $user->work_profile_complete === Status::YES,
            'icon' => 'las la-user-circle',
        ];

        return $items;
    }
}
