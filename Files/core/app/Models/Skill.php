<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use GlobalStatus;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skills', 'skill_id', 'job_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'skill_user', 'skill_id', 'user_id');
    }

    public function scopeForCategory($query, $categoryId)
    {
        if (! $categoryId) {
            return $query;
        }

        return $query->where(function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId)->orWhereNull('category_id');
        });
    }
}
