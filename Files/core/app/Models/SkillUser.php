<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class SkillUser extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
