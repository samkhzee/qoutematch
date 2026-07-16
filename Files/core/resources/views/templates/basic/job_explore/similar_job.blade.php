@forelse (@$similarJobs as $job)
    <li class="job-list__item">
        <a href="{{ route('explore.bid.job', $job->slug) }}" class="job-list__link">
            {{ strLimit(__($job->title), 30) }}</a>
        <div class="d-flex align-items-center gap-3">
            <span class="text">
                {{ getJobTimeDifference($job->created_at, $job->deadline) }}
            </span>
            <span class="text"> @lang('Deadline') {{ showDateTime($job->deadline, 'd m, Y') }} </span>
        </div>
    </li>
@empty
    <div class="empty-message text-center py-5">
        <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
        <p class="text-muted mt-3">@lang('No job found!')</p>
    </div>
@endforelse
