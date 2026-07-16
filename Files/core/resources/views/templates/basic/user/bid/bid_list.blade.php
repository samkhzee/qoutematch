<div class="dashboard-table">
    <table class="table table--responsive--md">
        <thead>
            <tr>
                <th>@lang('Job')</th>
                <th> @lang('Buyer') </th>
                <th> @lang('Estimate Time') </th>
                <th> @lang('Budget') </th>
                <th> @lang('Status') </th>
                @if (!request()->routeIs('user.home'))
                    <th> @lang('Action') </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($bids as $bid)
                <tr>
                    <td>
                        <a class="clamping"
                            href="@if ($bid->job->status == Status::JOB_PUBLISH && $bid->job->is_approved == Status::JOB_APPROVED) {{ route('explore.bid.job', $bid->job->slug) }} @else javascript:void(0) @endif"
                            target="__blank">{{ __($bid->job->title) }}</a>
                    </td>
                    <td>
                        <div>
                            {{ $bid->buyer->fullname }}
                            <span class="small d-block">
                                <a href="{{ route('freelance.jobs', ['buyer' => $bid->buyer->username]) }}"
                                    target="__blank"><span>@</span>{{ $bid->buyer->firstname }}</a>
                            </span>
                        </div>
                    </td>
                    <td><span class="clamping">{{ __($bid->estimated_time) }}</span></td>
                    <td>
                        <div>
                            {{ showAmount($bid->bid_amount) }}<br>
                            <sup class="text--primary">
                                [
                                @if ($bid->job->custom_budget)
                                    @lang('Customized')
                                @else
                                    @lang('Fixed')
                                @endif
                                ]
                            </sup>
                            @if ($bid->job)
                                <sup class="text-muted d-block">
                                    @lang('Request budget'): {{ showAmount($bid->job->budget) }}
                                </sup>
                            @endif
                        </div>
                    </td>
                    <td>
                        @php echo $bid->statusBadge @endphp
                        @if (
                            $bid->status == Status::BID_PENDING
                            && $bid->job
                            && $bid->job->updated_at > ($bid->updated_at ?? $bid->created_at)
                        )
                            <br><span class="badge badge--info mt-1">@lang('Request updated')</span>
                        @endif
                    </td>
                    @if (!request()->routeIs('user.home'))
                        <td>
                            @if ($bid->status == Status::BID_PENDING && $bid->job->status == Status::JOB_PUBLISH && $bid->job->is_approved == Status::JOB_APPROVED)
                                <a href="{{ route('user.bid.edit.page', $bid->job_id) }}"
                                    class="btn btn--base btn-sm me-2">
                                    <i class="las la-edit"></i> @lang('Edit Bid')
                                </a>
                            @endif
                            <div class="action-btn d-inline-block">
                                <button class="action-btn__icon">
                                    <i class="fa-solid fa-caret-down"></i>
                                </button>
                                <ul class="action-dropdown">
                                    @if ($bid->status == Status::BID_PENDING)
                                        <li class="action-dropdown__item withdrawModalBtn"
                                            data-action="{{ route('user.bid.withdraw', $bid->id) }}"
                                            data-question="@lang('Are you sure to withdraw this job proposal / bid?')">
                                            <a class="action-dropdown__link" href="javascript:void(0)">
                                                <span class="text"><i class="las la-undo"></i>
                                                    @lang('Withdraw')</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="action-dropdown__item moreModalBtn"
                                        data-title="{{ __($bid->job->title) }}"
                                        data-freelancer="{{ $bid->user->fullname }}"
                                        data-quote="{{ __(@$bid->bid_quote) }}"><a class="action-dropdown__link"
                                            href="javascript:void(0)">
                                            <span class="text"><i class="las la-quote-left"></i>
                                                @lang('My Quote')</span>
                                        </a>
                                    </li>

                                    @if (@$bid->project && @$bid->project?->status != Status::PROJECT_COMPLETED)
                                        <li class="action-dropdown__item">
                                            <a class="action-dropdown__link"
                                                href="{{ route('user.project.detail', @$bid->project->id) }}">
                                                <span class="text"><i class="las la-desktop"></i>
                                                    @lang('Project Details')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center msg-center py-4">
                        @include('Template::partials.empty', ['message' => 'No bids yet.'])
                        <a href="{{ route('freelance.jobs') }}" class="btn btn--base btn-sm mt-3">
                            <i class="las la-search"></i> @lang('Browse Requests & Submit a Bid')
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
