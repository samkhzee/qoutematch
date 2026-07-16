<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class SeoLocation extends Model
{
    use GlobalStatus;

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }
}
