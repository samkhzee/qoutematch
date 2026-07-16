<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\TrialTask;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskResource
{
    public static function buyerTasks(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => collect($paginator->items())->map(fn (TrialTask $task) => self::buyerTaskRow($task))->values()->all(),
            'links' => $paginator->linkCollection()->toArray(),
            'meta' => self::paginationMeta($paginator),
        ];
    }

    public static function providerTasks(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => collect($paginator->items())->map(fn (TrialTask $task) => self::providerTaskRow($task))->values()->all(),
            'links' => $paginator->linkCollection()->toArray(),
            'meta' => self::paginationMeta($paginator),
        ];
    }

    public static function buyerTaskRow(TrialTask $task): array
    {
        return [
            'id' => (int) $task->id,
            'title' => __($task->title),
            'amount' => showAmount($task->amount),
            'deadline' => showDateTime($task->deadline, 'd M, Y'),
            'status' => self::taskStatus((int) $task->status),
            'provider' => $task->user?->fullname,
            'jobTitle' => __($task->job?->title ?? '—'),
            'detailUrl' => route('buyer.trial.task.file.detail', $task->id),
            'editUrl' => route('buyer.trial.task.form', [$task->bid_id, $task->id]),
        ];
    }

    public static function providerTaskRow(TrialTask $task): array
    {
        return [
            'id' => (int) $task->id,
            'title' => __($task->title),
            'amount' => showAmount($task->amount),
            'deadline' => showDateTime($task->deadline, 'd M, Y'),
            'status' => self::taskStatus((int) $task->status),
            'buyer' => $task->buyer?->fullname,
            'jobTitle' => __($task->job?->title ?? '—'),
            'acceptUrl' => route('user.trial.task.accept', $task->id),
            'uploadUrl' => route('user.trial.task.form', $task->id),
            'canUpload' => in_array((int) $task->status, [Status::TASK_ACCEPTED, Status::TASK_COMPLETED, Status::TASK_REPORTED], true),
        ];
    }

    public static function buyerForm(TrialTask $task = null, $bid = null): array
    {
        return [
            'bidId' => (int) ($bid?->id ?? $task?->bid_id),
            'taskId' => $task?->id,
            'title' => $task?->title,
            'amount' => $task?->amount,
            'description' => $task?->description,
            'deadline' => $task?->deadline ? $task->deadline->format('Y-m-d') : null,
            'submitUrl' => $task
                ? route('buyer.trial.task.store', [$task->bid_id, $task->id])
                : route('buyer.trial.task.store', $bid->id),
            'indexUrl' => route('buyer.trial.task.index'),
        ];
    }

    public static function buyerDetail(TrialTask $task): array
    {
        $file = $task->task_file;

        return [
            'id' => (int) $task->id,
            'title' => __($task->title),
            'description' => $task->description,
            'amount' => showAmount($task->amount),
            'deadline' => showDateTime($task->deadline),
            'assignedAt' => showDateTime($task->created_at, 'd F Y'),
            'uploadedAt' => $task->uploaded_at ? showDateTime($task->uploaded_at, 'd F Y') : null,
            'status' => self::taskStatus((int) $task->status),
            'provider' => [
                'fullname' => $task->user?->fullname,
                'image' => getImage(getFilePath('userProfile') . '/' . ($task->user?->image ?? ''), avatar: true),
            ],
            'file' => $file ? [
                'name' => basename($file),
                'downloadUrl' => route('buyer.trial.task.file.download', [$task->id, encrypt($file)]),
            ] : null,
            'indexUrl' => route('buyer.trial.task.index'),
        ];
    }

    public static function providerUpload(TrialTask $task, $buyer, array $buyerStats): array
    {
        return [
            'id' => (int) $task->id,
            'title' => __($task->title),
            'description' => $task->description,
            'deadline' => showDateTime($task->deadline, 'd M, Y'),
            'amount' => showAmount($task->amount),
            'uploadUrl' => route('user.trial.task.upload', $task->id),
            'indexUrl' => route('user.trial.task.index'),
            'buyer' => [
                'fullname' => $buyer->fullname,
                'image' => getImage(getFilePath('buyerProfile') . '/' . $buyer->image, avatar: true),
                'country' => $buyer->country_name,
                'address' => $buyer->address,
            ],
            'buyerStats' => $buyerStats,
        ];
    }

    public static function taskStatus(int $status): array
    {
        return match ($status) {
            Status::TASK_ACCEPTED => ['label' => __('Processing'), 'class' => 'badge--warning'],
            Status::TASK_COMPLETED => ['label' => __('Submitted'), 'class' => 'badge--primary'],
            Status::TASK_FINISHED => ['label' => __('Finished'), 'class' => 'badge--success'],
            Status::TASK_REPORTED => ['label' => __('Reported'), 'class' => 'badge--danger'],
            Status::TASK_CANCELED => ['label' => __('Canceled'), 'class' => 'badge--danger'],
            Status::TASK_DRAFT => ['label' => __('Draft'), 'class' => 'badge--dark'],
            default => ['label' => __('Pending'), 'class' => 'badge--dark'],
        };
    }

    private static function paginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
