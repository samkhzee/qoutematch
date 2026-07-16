<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class LeadCreditPackage extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'float',
    ];

    public function totalCredits(): int
    {
        return (int) $this->credits + (int) $this->bonus_credits;
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE)->orderBy('sort_order')->orderBy('price');
    }
}
