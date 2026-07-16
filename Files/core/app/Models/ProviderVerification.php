<?php

namespace App\Models;

use App\Constants\ProviderVerificationType;
use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderVerification extends Model
{
    protected $casts = [
        'expires_at' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::VERIFICATION_APPROVED)
            ->where(function ($inner) {
                $inner->whereNull('expires_at')->orWhereDate('expires_at', '>=', now());
            });
    }

    public function isApproved(): bool
    {
        return (int) $this->status === Status::VERIFICATION_APPROVED
            && ($this->expires_at === null || $this->expires_at->gte(now()->startOfDay()));
    }

    public function statusLabel(): string
    {
        return match ((int) $this->status) {
            Status::VERIFICATION_APPROVED => __('Approved'),
            Status::VERIFICATION_REJECTED => __('Rejected'),
            default => __('Pending review'),
        };
    }

    public function typeLabel(): string
    {
        return ProviderVerificationType::label((string) $this->type);
    }

    public function documentUrl(string $routeName = 'user.download.attachment'): ?string
    {
        if (!$this->document) {
            return null;
        }

        return route($routeName, encrypt(getFilePath('verify') . '/' . $this->document));
    }
}
