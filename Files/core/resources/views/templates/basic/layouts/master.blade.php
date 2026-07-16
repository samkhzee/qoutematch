@extends('Template::layouts.app')
@section('panel')
    <div class="dashboard position-relative">
        <div class="dashboard__inner flex-wrap">
            <!-- ====================== Sidebar menu Start ========================= -->
            @include('Template::partials.sidebar')
            <!-- ====================== Sidebar menu End ========================= -->
            @php
                $user = App\Models\User::with('badge')->find(auth()->id());
            @endphp

            <div class="dashboard__right">
                <!-- Dashboard Header Start -->
                <div class="dashboard-header">
                    <div class="dashboard-header__inner flex-between">
                        <div class="dashboard-header__left">
                            <div class="dashboard-body__bar d-lg-none d-inline-block">
                                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
                            </div>
                            <h6 class="title"> {{ __(@$pageTitle) }} </h6>
                        </div>
                        @include('Template::partials.dashboard_user_menu', ['guard' => 'user', 'unreadCount' => $unreadCount ?? 0])
                    </div>
                </div>
                <!-- Dashboard Header End -->

                <!-- Dashboard Body End -->
                <div class="dashboard-body">


                    @yield('content')

                </div>
                <!-- Dashboard Body End -->
            </div>
        </div>
    </div>
    @auth
        @include('partials.dashboard_message_notify', ['pollUrl' => route('user.conversation.unread.summary')])
    @endauth
@endsection
