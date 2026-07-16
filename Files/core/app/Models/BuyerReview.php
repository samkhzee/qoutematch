<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerReview extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
