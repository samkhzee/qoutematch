@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container-fluid px-0">
        <div class="row gy-4">
            <div class="col-xxl-12 col-xl-12">
                <div class="job-post-content">
                    <div class="job-post-content__top">
                        <h6 class="job-post-content__title">@lang('Create a job post! We\'ll match you with the best candidates.') </h6>
                    </div>
                    <form action="{{ route('buyer.trial.task.store', [$bid->id, @$task->id]) }}" method="POST" class="disableSubmission">
                        @csrf
                        <div class="inner-content border-top">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                        <label class="form--label"> @lang('Write a title for your trial task') </label><a href="javascript:void(0)">
                                        </a>
                                    </div>
                                    <input type="text" class="form--control form-control" name="title"
                                        value="{{ old('title', @$task->title) }}" required>
                                </div>
                            </div>
                            
                            <div class="inner-content__bottom">
                                <label class="form--label"> @lang('Amount') <small
                                        class="text--danger fs-12">*</small></label>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="number" name="amount"
                                            value="{{ old('amount', getAmount(@$task->amount)) }}"
                                            class="form--control form-control" @if(@$task->amount) readonly @endif required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-content border-0">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label for="message" class="form--label"> @lang('Description About Task') <small
                                            class="text--danger">*</small></label>
                                    <textarea class="form--control form-control nicEdit" name="description" id="message"
                                        placeholder="@lang('write Description').. ">{{ old('description', @$task->description) }}</textarea>
                                </div>
                            
                                <div class="radio-btn-wrapper">
                                    <div class="col-sm-12">
                                        <label class="form--label"> @lang('Deadline')<small
                                                class="text--danger">*</small> </label>
                                        <div class="form-group">
                                        <input class="form--control form-control datepicker-here" name="deadline"
                                            placeholder="@lang('Date: YY-MM-DD')"
                                            value="{{ old('deadline', @$task->deadline) }}" autocomplete="off">
                                    </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="btn-wrapper justify-content-end">
                                <button class="btn btn--base">@lang('Submit') </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset(activeTemplate(true) . 'js/nicEdit.js') }}"></script>
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

            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);

                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                    $('.nicEdit-main').parent('div').addClass('nicEdit-custom-main')
                });
            });


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

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
        }

        .nicEdit-panelContain {
            border-color: #cacaca73 !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #fff !important
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 8px !important;
        }

        .select2-container .selection {
            width: 100%;
            display: inline-block;

        }

        .select2-container--default .select2-selection--single {
            border: 1px solid hsl(var(--black) / 0.1);
        }
    </style>
@endpush
