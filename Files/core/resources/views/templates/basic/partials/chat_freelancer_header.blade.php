@if (!empty($freelancer))
    <div class="chat-box__partner">
        <span class="chat-box__partner-avatar">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . $freelancer->image, avatar: true) }}"
                alt="{{ __($freelancer->fullname) }}">
        </span>
        <div class="chat-box__partner-meta">
            <div class="chat-box__partner-name">
                @if ($freelancer->work_profile_complete)
                    <a href="{{ route('talent.explore', $freelancer->username) }}" target="_blank" rel="noreferrer">
                        {{ __($freelancer->fullname) }}
                    </a>
                @else
                    {{ __($freelancer->fullname) }}
                @endif
            </div>
            @include('Template::partials.verification_badges', [
                'user' => $freelancer,
                'compact' => true,
                'class' => 'chat-box__partner-badges',
            ])
        </div>
    </div>
@endif
