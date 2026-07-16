<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalStatus;
use Illuminate\Support\Facades\Schema;

class Conversation extends Model
{
    use GlobalStatus;

    protected $fillable = [
        'job_id',
        'bid_id',
        'buyer_id',
        'user_id',
    ];

    protected $casts = [
        'buyer_hidden_at' => 'datetime',
        'user_hidden_at' => 'datetime',
    ];
    public function job()
    {
        return $this->belongsTo(Job::class);
    }


    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeUnblock($query)
    {
        return $query->where('status', Status::UNBLOCK);
    }

    public function scopeVisibleToBuyer($query)
    {
        if (self::supportsHiddenColumns()) {
            return $query->whereNull('buyer_hidden_at');
        }

        return $query;
    }

    public function scopeVisibleToUser($query)
    {
        if (self::supportsHiddenColumns()) {
            return $query->whereNull('user_hidden_at');
        }

        return $query;
    }

    public function hideForBuyer(): void
    {
        if (!self::supportsHiddenColumns()) {
            return;
        }

        $this->buyer_hidden_at = now();
        $this->save();
    }

    public function hideForUser(): void
    {
        if (!self::supportsHiddenColumns()) {
            return;
        }

        $this->user_hidden_at = now();
        $this->save();
    }

    public function revealForBuyer(): void
    {
        if (!self::supportsHiddenColumns() || !$this->buyer_hidden_at) {
            return;
        }

        $this->buyer_hidden_at = null;
        $this->save();
    }

    public function revealForUser(): void
    {
        if (!self::supportsHiddenColumns() || !$this->user_hidden_at) {
            return;
        }

        $this->user_hidden_at = null;
        $this->save();
    }

    protected static function supportsHiddenColumns(): bool
    {
        static $supported = null;

        if ($supported === null) {
            $supported = Schema::hasColumn((new static())->getTable(), 'buyer_hidden_at')
                && Schema::hasColumn((new static())->getTable(), 'user_hidden_at');
        }

        return $supported;
    }
}
