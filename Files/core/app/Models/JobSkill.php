<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
  
    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }

}
