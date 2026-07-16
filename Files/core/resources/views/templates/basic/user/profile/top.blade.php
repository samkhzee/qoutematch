@php
    $currentStep = auth()->user()->step;
    $activeRoute = Route::currentRouteName();
@endphp

<h6>@lang('Please complete these 4 steps and publish profile, before bidding on jobs.')</h6>
<ul class="page-list pt-3">
    <!-- Step 1: Skills -->
    <li class="nav-item {{ ($currentStep >= 1 || $activeRoute == 'user.profile.skill') ? 'active' : '' }} {{ $activeRoute == 'user.profile.skill' ? 'current' : '' }}">
        <a class="nav-link {{ menuActive('user.profile.skill') }}" href="{{ route('user.profile.skill') }}">
            <span class="profile-item__title">@lang('About & Skill')</span>
        </a>
    </li>

    <!-- Step 2: Profile -->
    <li class="nav-item {{ ($currentStep >= 2 || $activeRoute == 'user.profile.setting') ? 'active' : '' }} {{ $activeRoute == 'user.profile.setting' ? 'current' : '' }}">
        <a class="nav-link {{ menuActive('user.profile.setting') }} {{ $currentStep < 1 ? 'disabled' : '' }}"
            href="{{ $currentStep >= 1 ? route('user.profile.setting') : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Basic')</span>
        </a>
    </li>

    <!-- Step 3: Education -->
    <li class="nav-item {{ ($currentStep >= 3 || $activeRoute == 'user.profile.education') ? 'active' : '' }} {{ $activeRoute == 'user.profile.education' ? 'current' : '' }}">
        <a class="nav-link {{ menuActive('user.profile.education') }} {{ $currentStep < 2 ? 'disabled' : '' }}"
            href="{{ $currentStep >= 2 ? route('user.profile.education') : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Education')</span>
        </a>
    </li>

    <!-- Step 4: Portfolio -->
    <li class="nav-item {{ ($currentStep >= 4 || $activeRoute == 'user.profile.portfolio') ? 'active' : '' }} {{ $activeRoute == 'user.profile.portfolio' ? 'current' : '' }}">
        <a class="nav-link {{ menuActive('user.profile.portfolio') }} {{ $currentStep < 3 ? 'disabled' : '' }}"
            href="{{ $currentStep >= 3 ? route('user.profile.portfolio') : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Portfolio')</span>
        </a>
    </li>
</ul>
