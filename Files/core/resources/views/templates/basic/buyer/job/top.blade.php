@php
    $activeRoute = Route::currentRouteName();
    $jobId = request()->route('id') ?? optional($job ?? null)->id;

    $step = 1;
    if (Str::contains($activeRoute, 'freelancer.details')) {
        $step = 2;
    } elseif (Str::contains($activeRoute, 'budget')) {
        $step = 3;
    }
@endphp

<h6>@lang('Complete these 3 steps to post your request')</h6>
<ul class="page-list pt-3">
    <!-- Step 1: Request Details -->
    <li class="nav-item {{ $step >= 1 ? 'active' : '' }} {{ $step == 1 ? 'current' : '' }}">
        <a class="nav-link" href="{{ $jobId ? route('buyer.job.post.details', $jobId) : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Request Details')</span>
        </a>
    </li>

    <!-- Step 2: Provider Preferences -->
    <li class="nav-item {{ $step >= 2 ? 'active' : '' }} {{ $step == 2 ? 'current' : '' }}">
        <a class="nav-link {{ $step < 2 || !$jobId ? 'disabled' : '' }}"
            href="{{ $step >= 2 && $jobId ? route('buyer.job.post.freelancer.details', $jobId) : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Provider Preferences')</span>
        </a>
    </li>

    <!-- Step 3: Budget & Publish -->
    <li class="nav-item {{ $step >= 3 ? 'active' : '' }} {{ $step == 3 ? 'current' : '' }}">
        <a class="nav-link {{ $step < 3 || !$jobId ? 'disabled' : '' }}"
            href="{{ $step >= 3 && $jobId ? route('buyer.job.post.budget', $jobId) : 'javascript:void(0);' }}">
            <span class="profile-item__title">@lang('Budget & Publish')</span>
        </a>
    </li>
</ul>
