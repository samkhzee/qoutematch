@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Category | Speciality ')</th>
                                    <th>@lang('Budget')</th>
                                    <th>@lang('Scope') | @lang('Deadline')</th>
                                    <th>@lang('Approved')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $job)
                                    <tr>
                                        <td>
                                            @if ($job->status == Status::JOB_PUBLISH)
                                                <a class="fw-bold" target="__blank"
                                                    href="{{ route('explore.bid.job', $job->slug) }}">{{ strLimit(__($job->title), 25) }}</a>
                                            @else
                                                <span class="fw-bold"> {{ strLimit(__($job->title), 25) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-bold">{{ $job->buyer->fullname }}</span>
                                                <br>
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.buyers.detail', $job->buyer_id) }}"><span>@</span>{{ $job->buyer->username }}</a>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="text--base">{{ __($job->category->name) }}</span>
                                                <br>
                                                <span class="text--info">{{ __($job->subcategory->name) }}</span>
                                            </div>
                                        </td>
                                        <td> {{ showAmount($job->budget) }} </td>
                                        <td>
                                            @if ($job->skill_level == 1)
                                                @lang('Large Project')
                                            @elseif($job->skill_level == 2)
                                                @lang('Medium Project')
                                            @else
                                                @lang('Small Project')
                                            @endif
                                            <br>
                                            <span
                                                class="@if ($job->deadline > now()) text--info @else text--warning @endif">
                                                {{ showDateTime($job->deadline, 'd M, Y') }}</span>
                                        </td>
                                        <td>
                                            @if ($job->is_approved == Status::NO)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif ($job->is_approved == Status::JOB_APPROVED)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
                                        </td>
                                        <td> @php echo $job->statusBadge @endphp
                                            @if (\App\Lib\QuoteDeadlineService::inExpiredGracePeriod($job))
                                                <span class="badge badge--danger d-block mt-1">@lang('Quote deadline expired')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.jobs.details', $job->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                <a href="{{ route('admin.bids.index', $job->id) }}"
                                                    class="btn btn-sm btn-outline--info @if (!$job->bids->count()) disabled @endif">
                                                    <i class="las la-gavel"></i> @lang('All Bids')
                                                </a>
                                                @if (!$job->bids->where('status', Status::BID_ACCEPTED)->count())
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.jobs.delete', $job->id) }}"
                                                        data-question="@lang('Are you sure to permanently delete this request and all related quotes?')">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($jobs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($jobs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Job title" />
@endpush


@push('style')
    <style>
        .badge--finish {
            border-radius: 999px;
            padding: 2px 15px;
            position: relative;
            border-radius: 999px;
            -webkit-border-radius: 999px;
            -moz-border-radius: 999px;
            -ms-border-radius: 999px;
            -o-border-radius: 999px;
        }

        .badge--finish {
            background-color: rgba(44, 43, 43, 0.5) !important;
            color: #fff !important;
            border: 1px rgba(0, 0, 0, 0.5) solid;
        }
    </style>
@endpush
