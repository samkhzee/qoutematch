<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TrialTask extends Model
{
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class, 'bid_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::TASK_PENDING) {
                $html = '<span class="badge badge--primary">' . trans("Pending") . '</span>';
            } else if ($this->status == Status::TASK_ACCEPTED) {
                $html = '<span class="badge badge--warning">' . trans("Processing") . '</span>';
            } else if ($this->status == Status::TASK_COMPLETED) {
                $html = '<span class="badge badge--secondary">' . trans("Submitted") . '</span>';
            } else if ($this->status == Status::TASK_FINISHED) {
                $html = '<span class="badge badge--success">' . trans("Finished") . '</span>';
            } else if ($this->status == Status::TASK_REPORTED) {
                $html = '<span class="badge badge--danger">' . trans("Reported") . '</span>';
            } else if ($this->status == Status::TASK_CANCELED) {
                $html = '<span class="badge badge--danger">' . trans("Canceled") . '</span>';
            } else {
                $html = '<span class="badge badge--dark">' . trans("Drafted") . '</span>';
            }
            return $html;
        });
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', Status::TASK_COMPLETED);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::TASK_PENDING);
    }

    public function scopeUploadable($query)
    {
        return $query->whereIn('status', [
            Status::TASK_ACCEPTED,
            Status::TASK_REPORTED,
            Status::TASK_COMPLETED,
        ]);
    }
}
