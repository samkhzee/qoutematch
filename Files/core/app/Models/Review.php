<?php

namespace App\Models;

use App\Constants\ReviewDimension;
use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $casts = [
        'scores' => 'array',
        'moderated_at' => 'datetime',
    ];

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

    public function scopeApproved($query)
    {
        return $query->where('status', Status::REVIEW_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::REVIEW_PENDING);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', Status::REVIEW_HIDDEN);
    }

    public function statusLabel(): string
    {
        return match ((int) $this->status) {
            Status::REVIEW_APPROVED => 'Approved',
            Status::REVIEW_HIDDEN => 'Hidden',
            default => 'Pending',
        };
    }

    public function dimensionScores(): array
    {
        $scores = is_array($this->scores) ? $this->scores : [];

        return collect(ReviewDimension::all())->mapWithKeys(function ($label, $key) use ($scores) {
            return [$key => [
                'label' => $label,
                'score' => (int) ($scores[$key] ?? 0),
            ]];
        })->all();
    }
}
