<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;

class Category extends Model
{
    use GlobalStatus;

    public function requestForm()
    {
        return $this->belongsTo(Form::class, 'request_form_id');
    }

    public function quoteForm()
    {
        return $this->belongsTo(Form::class, 'quote_form_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
