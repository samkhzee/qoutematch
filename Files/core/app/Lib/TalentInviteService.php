<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Buyer;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;

class TalentInviteService
{
    public static function send(Buyer $buyer, User $freelancer): array
    {
        $activeJobs = Job::query()
            ->where('buyer_id', $buyer->id)
            ->where('status', Status::JOB_PUBLISH)
            ->latest('id')
            ->get(['id', 'title', 'slug']);

        if ($activeJobs->isEmpty()) {
            return [
                'success' => false,
                'message' => 'You do not have any active requests. Publish a request first, then invite providers.',
            ];
        }

        $requestsUrl = route('freelance.jobs', ['buyer' => $buyer->username]);
        $primaryJob = $activeJobs->first();
        $jobLink = $primaryJob?->slug
            ? route('explore.bid.job', $primaryJob->slug)
            : $requestsUrl;

        notify($freelancer, 'FREELANCER_INVITATION', [
            'buyer' => $buyer->fullname,
            'active_post' => $activeJobs->count(),
            'post_page' => $requestsUrl,
            'job_title' => $primaryJob->title ?? 'Request',
            'job_link' => $jobLink,
        ]);

        self::sendInAppInvite($buyer, $freelancer, $activeJobs, $requestsUrl, $jobLink);

        return [
            'success' => true,
            'message' => 'Invitation sent! The provider will receive a notification and can open your active requests.',
            'requestsUrl' => $requestsUrl,
        ];
    }

    protected static function sendInAppInvite(
        Buyer $buyer,
        User $freelancer,
        $activeJobs,
        string $requestsUrl,
        string $jobLink
    ): void {
        $conversation = Conversation::query()->firstOrCreate(
            [
                'buyer_id' => $buyer->id,
                'user_id' => $freelancer->id,
            ],
            [
                'status' => Status::UNBLOCK,
            ]
        );

        if ($conversation->user_hidden_at) {
            $conversation->user_hidden_at = null;
        }
        if ($conversation->buyer_hidden_at) {
            $conversation->buyer_hidden_at = null;
        }
        $conversation->save();

        $count = $activeJobs->count();
        $summary = $count === 1
            ? "{$buyer->fullname} invited you to submit a quote on: {$activeJobs->first()->title}"
            : "{$buyer->fullname} invited you to submit quotes on {$count} active requests.";

        $message = new Message();
        $message->conversation_id = $conversation->id;
        $message->buyer_id = $buyer->id;
        $message->message = "INVITE:: {$summary}\nView all requests: {$requestsUrl}\nOpen latest request: {$jobLink}";
        $message->buyer_read_at = now();
        $message->save();
    }
}
