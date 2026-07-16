<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadCreditLog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }
}
