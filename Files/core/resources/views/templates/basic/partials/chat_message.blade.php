@php
    $payload = \App\Lib\QuoteMessagingService::formatMessageForChat($message, $viewerRole ?? 'buyer');
    $downloadRoute = ($viewerRole ?? 'buyer') === 'buyer' ? 'buyer.download.attachment' : 'user.download.attachment';
@endphp
<div class="single-message message--{{ $payload['side'] }}" data-message-id="{{ $payload['id'] }}">
    <div class="message-content-outer">
        <span class="message-sender">{{ $payload['senderName'] }}</span>
        <div class="message-content">
            <p class="message-text">{!! nl2br(e($payload['message'])) !!}</p>
            @if (!empty($payload['files']))
                <small class="message-box__text">
                    @foreach ($payload['files'] as $file)
                        <a href="{{ route($downloadRoute, encrypt(getFilePath('message') . '/' . $file)) }}" target="_blank" rel="noreferrer">
                            <i class="las la-file"></i> {{ basename($file) }}
                        </a>
                    @endforeach
                </small>
            @endif
        </div>
        <span class="message-time d-block mt-1">{{ $payload['time'] }}</span>
    </div>
</div>
