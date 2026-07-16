@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">

            {{-- Filter Button --}}
            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm">
                    <i class="las la-filter"></i> @lang('Filter')
                </button>
            </div>

            {{-- Filter Card --}}
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('Task Title')</label>
                                <input type="search" name="search" value="{{ request()->search }}" class="form-control">
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control form--control select2"
                                    data-minimum-results-for-search="-1">
                                    <option value="" selected>@lang('All')</option>
                                    <option value="{{ Status::TASK_PENDING }}" @selected(request()->status !== null && request()->status == Status::TASK_PENDING)>
                                        @lang('Pending')
                                    </option>
                                    <option value="{{ Status::TASK_ACCEPTED }}" @selected(request()->status == Status::TASK_ACCEPTED)>
                                        @lang('Processing')
                                    </option>
                                    <option value="{{ Status::TASK_COMPLETED }}" @selected(request()->status == Status::TASK_COMPLETED)>
                                        @lang('Submitted')
                                    </option>
                                    <option value="{{ Status::TASK_FINISHED }}" @selected(request()->status == Status::TASK_FINISHED)>
                                        @lang('Finished')
                                    </option>
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="search"
                                    class="datepicker-here form-control bg--white pe-2 date-range"
                                    placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>

                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45">
                                    <i class="fas fa-filter"></i> @lang('Filter')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--md">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Freelancer')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Deadline')</th>
                                    <th>@lang('Job')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Created At')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tasks as $task)
                                    <tr>
                                        <td>{{ __($task->title) }}</td>

                                        <td>
                                            {{ $task->user->fullname }}
                                            <span class="small d-block">
                                                <a href="{{ route('admin.users.detail', $task->user_id) }}" target="_blank">
                                                    {{ $task->user->username }}
                                                </a>
                                            </span>
                                        </td>

                                        <td>
                                            {{ $task->buyer->fullname }}
                                            <span class="small d-block">
                                                <a href="{{ route('admin.buyers.detail', $task->buyer_id) }}"
                                                    target="_blank">
                                                    {{ $task->buyer->username }}
                                                </a>
                                            </span>
                                        </td>

                                        <td>{{ showAmount($task->amount) }}</td>

                                        <td>{{ showDateTime($task->deadline, 'd M, Y') }}</td>

                                        <td>
                                            <a href="{{ route('admin.jobs.details', $task->job_id) }}" target="_blank">
                                                <span class="text--primary">
                                                    {{ strLimit($task->job->title, 40) }}
                                                </span>
                                            </a>
                                        </td>

                                        <td>@php echo $task->statusBadge @endphp</td>

                                        <td>
                                            <a href="{{ route('admin.trial.task.details', $task->id) }}"
                                                class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">
                                            {{ __($emptyMessage ?? 'No trial task found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($tasks->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($tasks) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }


            $('.date-range').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event, picker
                .startDate, picker.endDate));

            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

        })(jQuery)
    </script>
@endpush
