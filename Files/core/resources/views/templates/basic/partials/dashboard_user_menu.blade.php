@php
    $dashboardUser = ($guard ?? 'user') === 'buyer' ? auth()->guard('buyer')->user() : auth()->user();
    $roleLabel = ($guard ?? 'user') === 'buyer' ? __('Buyer') : __('Provider');
    $conversationRoute = ($guard ?? 'user') === 'buyer'
        ? route('buyer.conversation.index')
        : route('user.conversation.index');
    $profileRoute = ($guard ?? 'user') === 'buyer'
        ? route('buyer.profile.setting')
        : route('user.profile.setting');
    $passwordRoute = ($guard ?? 'user') === 'buyer'
        ? route('buyer.change.password')
        : route('user.change.password');
    $twofactorRoute = ($guard ?? 'user') === 'buyer'
        ? route('buyer.twofactor')
        : route('user.twofactor');
    $logoutRoute = ($guard ?? 'user') === 'buyer'
        ? route('buyer.logout')
        : route('user.logout');
    $publicProfileRoute = ($guard ?? 'user') === 'buyer'
        ? null
        : route('talent.explore', $dashboardUser->username);
    $profilePath = ($guard ?? 'user') === 'buyer' ? getFilePath('buyerProfile') : getFilePath('userProfile');
    $unreadCount = $unreadCount ?? 0;
@endphp

<div class="user-info dashboard-user-menu" data-dashboard-user-menu>
    <div class="user-info__right">
        <div class="notification">
            <a class="notification-link dashboard-user-menu__notify" href="{{ $conversationRoute }}" aria-label="@lang('Messages')" data-message-notify-link>
                <i class="las la-envelope"></i>
                @if ($unreadCount > 0)
                    <span class="notification-number">{{ $unreadCount }}</span>
                @endif
            </a>
        </div>
        <button type="button" class="user-info__button user-info__trigger border-0 bg-transparent p-0" aria-haspopup="true" aria-expanded="false" data-dashboard-user-menu-trigger>
            <div class="user-info__thumb">
                <img src="{{ getImage($profilePath . '/' . $dashboardUser->image, avatar: true) }}"
                    alt="{{ $dashboardUser->fullname }}"
                    data-dashboard-user-avatar
                    onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');">
                <i class="las la-user-circle fs-2 text--base d-none" data-dashboard-user-fallback></i>
            </div>
            <span class="user-info__chevron" aria-hidden="true">
                <i class="las la-angle-down" data-dashboard-user-chevron></i>
            </span>
        </button>
    </div>

    <div class="user-info-dropdown dashboard-user-menu__dropdown" data-dashboard-user-menu-panel>
        <div class="dashboard-user-menu__header">
            <div class="dashboard-user-menu__avatar">
                <img src="{{ getImage($profilePath . '/' . $dashboardUser->image, avatar: true) }}"
                    alt="{{ $dashboardUser->fullname }}"
                    onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');">
                <i class="las la-user-circle d-none"></i>
            </div>
            <div class="dashboard-user-menu__meta">
                <div class="dashboard-user-menu__name">{{ strLimit($dashboardUser->fullname) }}</div>
                <span class="dashboard-user-menu__role">{{ $roleLabel }}</span>
            </div>
        </div>

        <ul class="dashboard-user-menu__list">
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ $profileRoute }}">
                    <span class="icon"><i class="fas fa-user-circle"></i></span>
                    <span class="text">@lang('My Profile')</span>
                </a>
            </li>
            @if ($publicProfileRoute)
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ $publicProfileRoute }}" target="_blank" rel="noreferrer">
                        <span class="icon"><i class="las la-external-link-alt"></i></span>
                        <span class="text">@lang('Public Profile')</span>
                    </a>
                </li>
            @endif
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ $passwordRoute }}">
                    <span class="icon"><i class="fas fa-lock"></i></span>
                    <span class="text">@lang('Password')</span>
                </a>
            </li>
            <li class="user-info-dropdown__item">
                <a class="user-info-dropdown__link" href="{{ $twofactorRoute }}">
                    <span class="icon"><i class="fas fa-key"></i></span>
                    <span class="text">@lang('2FA Security')</span>
                </a>
            </li>
            <li class="user-info-dropdown__item is-danger">
                <a class="user-info-dropdown__link" href="{{ $logoutRoute }}">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
