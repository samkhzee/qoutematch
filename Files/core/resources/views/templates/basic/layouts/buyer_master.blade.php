@extends('Template::layouts.app')
@section('panel')
    <div class="dashboard position-relative">
        <div class="dashboard__inner flex-wrap">
            <div class="dashboard__left">
                <!-- ====================== Sidebar menu Start ========================= -->
                @include('Template::partials.buyer_sidebar')
            </div>
            <div class="dashboard__right">
                <div class="dashboard-header">
                    <div class="dashboard-header__inner flex-between">
                        <div class="dashboard-header__left">
                            <div class="dashboard-body__bar d-lg-none d-inline-block">
                                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
                            </div>
                            <h6 class="title"> {{ __(@$pageTitle) }} </h6>
                        </div>
                        @include('Template::partials.dashboard_user_menu', ['guard' => 'buyer', 'unreadCount' => $unreadCount ?? 0])
                    </div>
                </div>

                <div class="dashboard-body dashboard-body--buyer">

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @auth('buyer')
        @include('partials.dashboard_message_notify', ['pollUrl' => route('buyer.conversation.unread.summary')])
    @endauth
@endsection
