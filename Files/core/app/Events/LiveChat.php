<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $remark;
    public $message;
    public $buyerId;
    public $userId;
    public $adminId;
    public $conversationId;
    public $chanelName;
    public $userImage;
    public $files;
    public $action;
    public $profileImage;

    public function __construct($message)
    {
        $this->remark = 'live-chat';
        $this->message = $message->message;
        $this->action = $message->action;
        $this->files = $message->files ?? [];
        $this->buyerId = @$message->buyer_id ?? 0;
        $this->userId = @$message->user_id ?? 0;
        $this->adminId = @$message->admin_id ?? 0;

        // Determine profile image
        if ($message->admin_id) {
            $this->userImage = getImage(getFilePath('adminProfile') . '/' . @$message->admin->image, getFilePath('adminProfile'), avatar: true);
        } elseif ($message->user_id) {
            $this->userImage = getImage(getFilePath('userProfile') . '/' . @$message->user->image, getFilePath('userProfile'), avatar: true);
        } elseif ($message->buyer_id) {
            $this->userImage = getImage(getFilePath('buyerProfile') . '/' . @$message->buyer->image, getFilePath('buyerProfile'), avatar: true);
        }

        $this->conversationId = $message->conversation_id ?? null;
        $this->chanelName = 'conversation_' . $message->conversation_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->chanelName);
    }

    public function broadcastAs()
    {
        return $this->chanelName;
    }
}
