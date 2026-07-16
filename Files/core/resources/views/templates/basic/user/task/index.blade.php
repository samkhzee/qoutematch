@extends('Template::layouts.master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header gap-3">
            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn--base showFilterBtn btn--sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="responsive-filter-card my-4">
                <form>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <input type="search" name="search" value="{{ request()->search }}"
                                placeholder="@lang('Search by Task')" autocomplete="off" class="form-control form--control">
                        </div>
                        <div class="flex-grow-1">
                            <select name="status" class="form-control form--control select2 "
                                data-minimum-results-for-search="-1">
                                <option value="">@lang('All Status')</option>
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
                            <input name="date" type="search"
                                class="datepicker-here form-control form--control bg--white pe-2 date-range"
                                placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                        </div>

                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--base w-100"><i class="las la-filter"></i>
                                @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="dashboard-table">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th> @lang('Title') </th>
                        <th> @lang('Freelancer') </th>
                        <th> @lang('Amount') </th>
                        <th> @lang('Deadline') </th>
                        <th> @lang('Job') </th>
                        <th> @lang('Status') </th>
                        <th> @lang('Action') </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>
                                {{ __($task->title) }}
                            </td>
                            <td>
                                <div>
                                    {{ __($task->user->username) }}
                                </div>
                            </td>

                            <td> {{ showAmount($task->amount) }} </td>
                            <td>
                                {{ showDateTime($task->deadline, 'd M, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('explore.bid.job', $task->job->slug) }}" target="blank"><span
                                        class="clamping">
                                        {{ __($task->job->title) }} </span></a>
                            </td>
                            <td> @php echo $task->statusBadge @endphp</td>
                            <td>
                                <div class="action-btn">

                                    <button class="action-btn__icon">
                                        <i class="fa-solid fa-caret-down"></i>
                                    </button>
                                    <ul class="action-dropdown">
                                        <li class="action-dropdown__item">
                                            <button class="action-dropdown__link detailBtn"
                                                data-task_title="{{ $task->title }}"
                                                data-deadline="{{ showDateTime($task->deadline, 'd M, Y') }}"
                                                data-description='@php echo $task->description @endphp'>
                                                <span class="text"><i class="las la-desktop"></i>
                                                    @lang('Details')</span>
                                            </button>
                                        </li>
                                        @if ($task->status == Status::TASK_PENDING)
                                            <li class="action-dropdown__item">
                                                <a class="action-dropdown__link"
                                                    href="{{ route('user.trial.task.accept', $task->id) }}">
                                                    <span class="text"><i class="las la-check-circle"></i>
                                                        @lang('Accept')</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if (in_array($task->status, [Status::TASK_ACCEPTED, Status::TASK_REPORTED, Status::TASK_COMPLETED]))
                                            <li class="action-dropdown__item">
                                                <a class="action-dropdown__link" data-bs-toggle="tooltip"
                                                    data-placement="top"
                                                    href="{{ route('user.trial.task.form', @$task->id) }}"
                                                    title="@lang('Upload your completed project file for reviewing')">
                                                    <span class="text"><i class="las la-upload"></i>
                                                        @lang('Upload FIle')</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center msg-center">
                                <div class="empty-message text-center py-5">
                                    <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
                                    <h6 class="text-muted mt-3">@lang('Job not found!')</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($tasks->hasPages())
                <div class="dashboard-table__bottom">
                    {{ paginateLinks($tasks) }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal custom--modal" id="detailModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush taskDetailsContent">
                    </ul>

                    <div class="mt-3">
                        <h6 class="fw-bold">@lang('Task Description')</h6>
                        <div class="taskDescription content-panel" style="max-height:300px; overflow:auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(".detailBtn").on("click", function() {
                let modal = $('#detailModal');
                let detailsList = $(".taskDetailsContent");
                let descriptionBox = $(".taskDescription");

                detailsList.html('');
                descriptionBox.html('');

                $.each(this.dataset, function(key, value) {
                    if (key === 'description') {
                        descriptionBox.html(value);
                        return;
                    }

                    let formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, char => char
                        .toUpperCase());

                    detailsList.append(
                        `<li class="list-group-item d-flex flex-wrap justify-content-between align-items-center px-0 gap-1">
                            <span> <strong>${formattedKey}</strong></span>
                            <span>${value}</span>
                        </li>`
                    );
                });
                modal.modal('show');
            });

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
        })(jQuery);
    </script>
@endpush
