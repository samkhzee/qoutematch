<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'float',
        'unlimited_quotes' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE)->orderBy('sort_order')->orderBy('price');
    }
}
