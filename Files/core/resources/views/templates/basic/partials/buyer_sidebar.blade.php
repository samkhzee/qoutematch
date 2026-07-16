@php
    $user = auth()->guard('buyer')->user();
    $hasPendingReviews = App\Models\Project::where('buyer_id', $user->id)
        ->where('status', Status::PROJECT_BUYER_REVIEW)
        ->count();
    $activeDisputes = App\Models\Dispute::where('buyer_id', $user->id)->active()->count();
@endphp

<div class="sidebar-menu flex-between">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
        <!-- Sidebar Logo Start -->
        <div class="sidebar-logo">
            <a href="{{ route('home') }}" class="sidebar-logo__link"><img src="{{ siteLogo('dark') }}" alt=""></a>
        </div>
        <!-- Sidebar Logo End -->
        <div class="sidebar-menu__top">
            <div class="shape">
                <img src="{{ asset(activeTemplate(true) . 'shape/d-shape.png') }}" alt="">
            </div>
            <span class="icon">
                <i class="las la-wallet"></i>
            </span>
            <div class="content">
                <span class="title">@lang('Balance')</span>
                <h6 class="number">{{ showAmount(auth()->guard('buyer')->user()->balance) }}</h6>
            </div>
        </div>

        <!-- ========= Sidebar Menu Start ================ -->
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item {{ menuActive('buyer.home') }}">
                <a href="{{ route('buyer.home') }}" class="sidebar-menu-list__link">
                    <span class="icon"> <i class="las la-home"></i> </span>
                    <span class="text">@lang('Dashboard') </span>
                </a>
            </li>

            <li class="sidebar-menu-list__item has-dropdown {{ menuActive(['buyer.job.post.*']) }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon">
                        <i class="las la-rocket"></i>
                    </span>
                    <span class="text"> @lang('Jobs') </span>
                </a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.job.post.index') }}">
                            <a href="{{ route('buyer.job.post.index') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Job List') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.job.post.details') }}">
                            <a href="{{ route('buyer.job.post.details') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Post Job') </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item {{ menuActive('buyer.job.post.index') }}">
                <a href="{{ route('buyer.job.post.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-columns"></i></span>
                    <span class="text">@lang('Compare Quotes')</span>
                </a>
            </li>
            @if(gs('trial_task'))
            <li class="sidebar-menu-list__item {{ menuActive('buyer.trial.task.index') }}">
                <a href="{{ route('buyer.trial.task.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-tasks"></i></span>
                    <span class="text">@lang('Trial Tasks') </span>
                </a>
            </li>
            @endif
            <li class="sidebar-menu-list__item {{ menuActive(['buyer.project.index', 'buyer.project.detail']) }}">
                <a href="{{ route('buyer.project.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-briefcase"></i></span>
                    <span class="text">@lang('My Projects')
                        @if ($hasPendingReviews)
                            <span class="shake text--warning"> <i class="las la-bell"></i></span>
                        @endif
                    </span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive(['buyer.disputes.index', 'buyer.disputes.detail']) }}">
                <a href="{{ route('buyer.disputes.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-exclamation-triangle"></i></span>
                    <span class="text">@lang('Disputes')
                        @if ($activeDisputes > 0)
                            <span class="shake text--warning"><i class="las la-bell"></i></span>
                        @endif
                    </span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('buyer.notifications.index') }}">
                <a href="{{ route('buyer.notifications.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-bell"></i></span>
                    <span class="text">@lang('Notifications')</span>
                </a>
            </li>

            <li
                class="sidebar-menu-list__item {{ menuActive(['buyer.deposit.index', 'buyer.deposit.history']) }} has-dropdown">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"> <i class="las la-wallet"></i> </span>
                    <span class="text">@lang('Deposit')</span>
                </a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.deposit.index') }}">
                            <a href="{{ route('buyer.deposit.index') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Deposit Money') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.deposit.history') }}">
                            <a href="{{ route('buyer.deposit.history') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Deposit History') </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li
                class="sidebar-menu-list__item {{ menuActive(['buyer.withdraw', 'buyer.withdraw.history']) }} has-dropdown">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-money-check-alt"></i></span>
                    <span class="text"> @lang('Withdraw') </span>
                </a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.withdraw') }}">
                            <a href="{{ route('buyer.withdraw') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Withdraw Money') </span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.withdraw.history') }}">
                            <a href="{{ route('buyer.withdraw.history') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Withdraw History') </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item {{ menuActive('buyer.transactions') }}">
                <a href="{{ route('buyer.transactions') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-exchange-alt"></i> </span>
                    <span class="text">@lang('Transactions') </span>
                </a>
            </li>

            <li
                class="sidebar-menu-list__item has-dropdown {{ menuActive(['buyer.ticket.open', 'buyer.ticket.index', 'buyer.ticket.view']) }}">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-ticket-alt"></i></span>
                    <span class="text"> @lang('Support Ticket') </span>
                </a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.ticket.open') }}">
                            <a href="{{ route('buyer.ticket.open') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Create New')</span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.ticket.index') }}">
                            <a href="{{ route('buyer.ticket.index') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Ticket History') </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidebar-menu-list__item {{ menuActive(['buyer.conversation.*']) }}">
                <a href="{{ route('buyer.conversation.index') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="lab la-rocketchat"></i></span>
                    <span class="text">@lang('Chat')
                        <span class="sidebar-chat-notify @if(empty($unreadCount)) d-none @else shake text--warning @endif" data-sidebar-chat-notify>
                            @if(!empty($unreadCount))
                                <i class="las la-bell"></i>
                                <span class="sidebar-chat-notify__count">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </span>
                    </span>
                </a>
            </li>

            <li
                class="sidebar-menu-list__item {{ menuActive(['buyer.profile.setting', 'buyer.change.password', 'buyer.twofactor']) }} has-dropdown">
                <a href="javascript:void(0)" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-cog"></i></span>
                    <span class="text"> @lang('Settings') </span>
                </a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.profile.setting') }}">
                            <a href="{{ route('buyer.profile.setting') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Profile Setting')</span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.change.password') }}">
                            <a href="{{ route('buyer.change.password') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('Change Password')</span>
                            </a>
                        </li>
                        <li class="sidebar-submenu-list__item {{ menuActive('buyer.twofactor') }}">
                            <a href="{{ route('buyer.twofactor') }}" class="sidebar-submenu-list__link">
                                <span class="text">@lang('2FA Security') </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <li class="sidebar-menu-list__item">
                <a href="{{ route('buyer.logout') }}" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-sign-out-alt"></i></span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
        <!-- ========= Sidebar Menu End ================ -->
    </div>
</div>
