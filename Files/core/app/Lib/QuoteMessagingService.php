<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Bid;
use App\Models\Buyer;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class QuoteMessagingService
{
    /**
     * Messaging is allowed only after a provider has submitted a quote on the request.
     */
    public static function bidAllowsMessaging(Bid $bid): bool
    {
        return !in_array((int) $bid->status, [Status::BID_WITHDRAW, Status::BID_REJECTED], true);
    }

    public static function buyerCanMessageBid(Buyer $buyer, Bid $bid): bool
    {
        return (int) $bid->buyer_id === (int) $buyer->id
            && self::bidAllowsMessaging($bid);
    }

    public static function providerCanMessageBid(User $provider, Bid $bid): bool
    {
        return (int) $bid->user_id === (int) $provider->id
            && self::bidAllowsMessaging($bid);
    }

    public static function findOrCreateForBid(Bid $bid): Conversation
    {
        $conversation = Conversation::query()
            ->where('buyer_id', $bid->buyer_id)
            ->where('user_id', $bid->user_id)
            ->when($bid->job_id, fn ($query) => $query->where('job_id', $bid->job_id))
            ->first();

        if ($conversation) {
            if (!$conversation->bid_id) {
                $conversation->bid_id = $bid->id;
                $conversation->job_id = $bid->job_id;
                $conversation->save();
            }

            return $conversation;
        }

        $conversation = new Conversation();
        $conversation->buyer_id = $bid->buyer_id;
        $conversation->user_id = $bid->user_id;
        $conversation->job_id = $bid->job_id;
        $conversation->bid_id = $bid->id;
        $conversation->save();

        return $conversation;
    }

    public static function conversationBid(Conversation $conversation): ?Bid
    {
        if ($conversation->bid_id) {
            return Bid::find($conversation->bid_id);
        }

        if ($conversation->job_id) {
            return Bid::query()
                ->where('job_id', $conversation->job_id)
                ->where('buyer_id', $conversation->buyer_id)
                ->where('user_id', $conversation->user_id)
                ->where('status', '!=', Status::BID_WITHDRAW)
                ->latest('id')
                ->first();
        }

        return null;
    }

    public static function unreadIncomingQueryForBuyer(Buyer $buyer)
    {
        return Message::query()
            ->whereHas('conversation', fn ($query) => $query->where('buyer_id', $buyer->id)->visibleToBuyer())
            ->where(function ($query) {
                $query->whereNotNull('user_id')->orWhereNotNull('admin_id');
            })
            ->whereNull('buyer_read_at');
    }

    public static function unreadIncomingQueryForProvider(User $provider)
    {
        return Message::query()
            ->whereHas('conversation', fn ($query) => $query->where('user_id', $provider->id)->visibleToUser())
            ->where(function ($query) {
                $query->whereNotNull('buyer_id')->orWhereNotNull('admin_id');
            })
            ->whereNull('read_at');
    }

    public static function unreadCountForBuyer(Buyer $buyer): int
    {
        return (int) self::unreadIncomingQueryForBuyer($buyer)->count();
    }

    public static function unreadCountForProvider(User $provider): int
    {
        return (int) self::unreadIncomingQueryForProvider($provider)->count();
    }

    public static function unreadSummaryForBuyer(Buyer $buyer): array
    {
        $query = self::unreadIncomingQueryForBuyer($buyer);
        $count = (int) (clone $query)->count();
        $latest = (clone $query)
            ->with(['user', 'buyer', 'admin'])
            ->latest('id')
            ->first();

        return self::formatUnreadSummary($latest, $count, 'buyer');
    }

    public static function unreadSummaryForProvider(User $provider): array
    {
        $query = self::unreadIncomingQueryForProvider($provider);
        $count = (int) (clone $query)->count();
        $latest = (clone $query)
            ->with(['user', 'buyer', 'admin'])
            ->latest('id')
            ->first();

        return self::formatUnreadSummary($latest, $count, 'provider');
    }

    protected static function formatUnreadSummary(?Message $latest, int $count, string $role): array
    {
        $sender = __('Someone');
        $preview = '';
        $conversationId = null;
        $conversationUrl = null;

        if ($latest) {
            $conversationId = (int) $latest->conversation_id;

            if ($latest->user) {
                $sender = $latest->user->fullname;
            } elseif ($latest->buyer) {
                $sender = $latest->buyer->fullname;
            } elseif ($latest->admin) {
                $sender = __('Support');
            }

            $preview = strLimit(strip_tags((string) $latest->message), 120);
            $conversationUrl = $role === 'buyer'
                ? route('buyer.conversation.start', $conversationId)
                : route('user.conversation.index', $conversationId);
        }

        return [
            'count' => $count,
            'sender' => $sender,
            'preview' => $preview,
            'conversation_id' => $conversationId,
            'conversation_url' => $conversationUrl,
        ];
    }

    public static function formatMessageForChat(Message $message, string $viewer = 'buyer'): array
    {
        $fromBuyer = (int) ($message->buyer_id ?? 0) > 0 && ! (int) ($message->user_id ?? 0);
        $fromFreelancer = (int) ($message->user_id ?? 0) > 0;
        $fromAdmin = (int) ($message->admin_id ?? 0) > 0;

        if ($viewer === 'buyer') {
            if ($fromBuyer) {
                $side = 'right';
                $senderName = __('You');
                $senderImage = getImage(getFilePath('buyerProfile') . '/' . ($message->buyer->image ?? ''), avatar: true);
            } elseif ($fromAdmin) {
                $side = 'left';
                $senderName = __('Support');
                $senderImage = getImage(getFilePath('adminProfile') . '/' . ($message->admin->image ?? ''), avatar: true);
            } else {
                $side = 'left';
                $senderName = $message->user?->fullname ?? __('Freelancer');
                $senderImage = getImage(getFilePath('userProfile') . '/' . ($message->user->image ?? ''), avatar: true);
            }
        } else {
            if ($fromFreelancer) {
                $side = 'right';
                $senderName = __('You');
                $senderImage = getImage(getFilePath('userProfile') . '/' . ($message->user->image ?? ''), avatar: true);
            } elseif ($fromAdmin) {
                $side = 'left';
                $senderName = __('Support');
                $senderImage = getImage(getFilePath('adminProfile') . '/' . ($message->admin->image ?? ''), avatar: true);
            } else {
                $side = 'left';
                $senderName = $message->buyer?->fullname ?? __('Buyer');
                $senderImage = getImage(getFilePath('buyerProfile') . '/' . ($message->buyer->image ?? ''), avatar: true);
            }
        }

        $files = $message->files ?? [];
        if (is_object($files)) {
            $files = json_decode(json_encode($files), true) ?? [];
        }

        return [
            'id' => (int) $message->id,
            'message' => (string) $message->message,
            'buyerId' => (int) ($message->buyer_id ?? 0),
            'userId' => (int) ($message->user_id ?? 0),
            'adminId' => (int) ($message->admin_id ?? 0),
            'side' => $side,
            'senderName' => $senderName,
            'senderImage' => $senderImage,
            'files' => array_values((array) $files),
            'action' => (int) ($message->action ?? 0),
            'time' => diffForHumans($message->updated_at),
        ];
    }
}
