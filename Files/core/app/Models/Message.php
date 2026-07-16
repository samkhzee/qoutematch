<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $casts = [
        'files' => 'object',
    ];
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function scopeUnreadForUser($query, $userId)
    {
        return $query->whereNull('read_at')->where('user_id', '!=', $userId);
    }
   
}
