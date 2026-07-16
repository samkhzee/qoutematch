<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class ProviderSubscription extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'price_paid' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::SUBSCRIPTION_ACTIVE)
            ->where('expires_at', '>', now());
    }

    public function isActive(): bool
    {
        return (int) $this->status === Status::SUBSCRIPTION_ACTIVE
            && $this->expires_at
            && $this->expires_at->isFuture();
    }
}
