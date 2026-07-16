@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container-fluid px-0">
        <div class="row gy-4">
            <div class="col-xxl-8 col-xl-7">
                <div class="job-post-content">
                    <div class="inner-content">
                        @include('Template::buyer.job.top')
                    </div>
                    <form action="{{ route('buyer.job.post.budget.store', @$job->id) }}" method="POST"
                        class="disableSubmission">
                        @csrf
                        <div class="inner-content border-top">

                            <div class="inner-content__bottom">
                                <label class="form--label"> @lang('Tell us your budget') <small
                                        class="text--danger fs-12">*</small></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="budget"
                                            value="{{ old('budget', getAmount(@$job->budget)) }}"
                                            class="form--control form-control" required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                                <label class="form--label"> @lang('Make Custom Proposal ?') <small
                                        class="text--danger fs-12">*</small></label>
                                <div class="proposal-wrapper">
                                    <div class="form-check form--radio">
                                        <label class="form-check-label" for="yes_custom_proposal">
                                            <input class="form-check-input" type="radio" name="custom_budget"
                                                id="yes_custom_proposal" value="1"
                                                {{ old('custom_budget', @$job->custom_budget == Status::YES ? 'checked' : '') }}>
                                            <span class="icon">
                                                <i class="las la-check-square"></i>
                                            </span>
                                            <span class="text"> @lang('Yes') </span>
                                        </label>
                                    </div>
                                    <div class="form-check form--radio">
                                        <label class="form-check-label" for="no_custom_proposal">
                                            <input class="form-check-input" type="radio" name="custom_budget"
                                                id="no_custom_proposal" value="0"
                                                {{ old('custom_budget', @$job->custom_budget) == Status::NO ? 'checked' : '' }}>
                                            <span class="icon">
                                                <i class="las la-exclamation-triangle"></i>
                                            </span>
                                            <span class="text">@lang('No')</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-content border-0">
                            <div class="row">

                                <div class="inner-content__bottom mt-3">
                                    <h6 class="title">@lang('This job post will be end this date') <small class="text--danger fs-12">*</small>
                                    </h6>
                                    <div class="form-group">
                                        <input class="form--control form-control datepicker-here" name="deadline"
                                            placeholder="@lang('Date: YY-MM-DD')" value="{{ old('deadline', @$job->deadline) }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="about-question py-3">
                                <div class="form-group">
                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                        <label class="form--label"> @lang('Screening questions') <small
                                                class="text--danger">*</small></label>
                                        <button type="button" id="add-question"
                                            class="btn-outline--base btn d-flex align-items-center gap-2">
                                            <span class="icon"><i class="las la-plus"></i></span>
                                            @lang('Write your own question')
                                        </button>
                                    </div>
                                </div>

                                <div id="question-container">
                                    <div class="about-question__content question-item row">
                                        <div class="content">
                                            @forelse ($job->questions ?? [] as $key => $question)
                                                <div class="form-group d-flex align-items-center">
                                                    <input type="text" class="form--control form-control question-input"
                                                        name="questions[]" value="{{ $question }}"
                                                        placeholder="Write your question" maxlength="500" required>
                                                    <span class="icon text--danger remove-question ms-3"
                                                        title="Remove this question" style="cursor: pointer;">
                                                        <i class="las la-trash-alt"></i>
                                                    </span>
                                                </div>
                                            @empty
                                                <div class="form-group d-flex align-items-center">
                                                    <input type="text" class="form--control form-control question-input"
                                                        name="questions[]" placeholder="Write your question" maxlength="255"
                                                        required>
                                                    <span class="icon text--danger remove-question ms-3"
                                                        title="Remove this question" style="cursor: pointer;">
                                                        <i class="las la-trash-alt"></i>
                                                    </span>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="proposal-wrapper">
                                <div class="form-check form--radio">
                                    <label class="form-check-label" for="publish">
                                        <input class="form-check-input" type="radio" name="status" id="publish"
                                            value="1"
                                            {{ old('status', @$job->status) == Status::YES ? 'checked' : '' }}>
                                        <span class="icon">
                                            <i class="las la-bullseye"></i>
                                        </span>
                                        <span class="text"> @lang('Go Live')</span>
                                    </label>
                                </div>
                                <div class="form-check form--radio">
                                    <label class="form-check-label" for="draft">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="0"
                                            {{ old('status', @$job->status) == Status::NO ? 'checked' : '' }}>
                                        <span class="icon">
                                            <i class="las la-crosshairs"></i>
                                        </span>
                                        <span class="text"> @lang('Draft') </span>
                                    </label>
                                </div>
                            </div>
                            <div class="btn-wrapper">
                                <a href="{{ route('buyer.job.post.freelancer.details', $job->id) }}"
                                    class="btn btn-outline--base">
                                    <i class="las la-angle-double-left"></i> @lang('Previous') </a>
                                <button type="submit" class="btn btn--base"> @lang('Save') <i
                                        class="las la-save"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5">
                <!--================== sidebar start here ================== -->
                @include('Template::buyer.job.info')
                <!--================== sidebar end here ==================== -->
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
            "use strict";

            $('.datepicker-here').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().add(0, 'days'),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('.datepicker-here').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
            });

            $('.datepicker-here').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            const maxQuestions = 5;
            let questionCount = $("#question-container .question-input").length;

            if (questionCount >= maxQuestions) {
                $("#add-question").prop("disabled", true);
            }

            $("#add-question").on("click", function() {
                if (questionCount < maxQuestions) {
                    const newQuestion = `
                        <div class="content">
                            <div class="form-group d-flex align-items-center">
                                <input
                                    type="text"
                                    class="form--control form-control question-input"
                                    name="questions[]"
                                    placeholder="Write your question"
                                    maxlength="500"
                                    required>
                                <span class="icon text--danger remove-question ms-3" title="Remove this question" style="cursor: pointer;">
                                    <i class="las la-trash-alt"></i>
                                </span>
                            </div>
                        </div>`;
                    $("#question-container .about-question__content").append(newQuestion);
                    questionCount++;

                    if (questionCount >= maxQuestions) {
                        $("#add-question").prop("disabled", true);
                    }
                }
            });

            $(document).on("click", ".remove-question", function() {
                $(this).closest(".content").remove();
                questionCount--;
                if (questionCount < maxQuestions) {
                    $("#add-question").prop("disabled", false);
                }
            });

        })(jQuery);
    </script>
@endpush
