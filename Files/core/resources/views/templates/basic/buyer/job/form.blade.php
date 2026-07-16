@extends('Template::layouts.buyer_master')
@section('content')
    
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <div class="col-xxl-8 col-xl-7">
                    <div class="job-post-content">
                        <div class="job-post-content__top">
                            <h6 class="job-post-content__title">@lang('Create a job post! We\'ll match you with the best candidates.') </h6>
                        </div>
                        <form action="{{ route('buyer.job.post.store', @$job->id) }}" method="POST" class="disableSubmission">
                            @csrf
                            <div class="inner-content border-top">
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <div class="d-flex justify-content-between flex-wrap mb-2">
                                            <label class="form--label"> @lang('Write a title for your job post') </label><a
                                                href="javascript:void(0)" class="buildSlug">
                                                <small><i class="las la-link"></i> @lang('Make Slug')</small>
                                            </a>
                                        </div>
                                        <input type="text" class="form--control form-control" name="title"
                                            value="{{ old('title', @$job->title) }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <div class="d-flex justify-content-between">
                                            <label class="form--label"> @lang('Make Slug for SEO Friendly')</label>
                                            <div class="slug-verification d-none"></div>
                                        </div>
                                        <input type="text" class="form--control form-control" name="slug"
                                            value="{{ old('slug', @$job->slug) }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label class="form--label"> @lang('Category')</label>
                                        <select class="form-select form--control form-control select2" name="category_id"
                                            required>
                                            <option value="">@lang('Select Categories')</option>
                                            @foreach ($categories as $category)
                                                <option data-subcategories='@json($category->subcategories)'
                                                    value="{{ $category->id }}"
                                                    @if (old('category_id', @$job->category_id) == $category->id) selected @endif>
                                                    {{ __($category->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label class="form--label"> @lang('Speciality')</label>
                                        <select class="form-select form--control form-control select2" name="subcategory_id"
                                            required>
                                            <option value="">@lang('Select Speciality')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="inner-content__bottom">
                                    <label class="form--label"> @lang('Tell us your budget') <small class="text--danger fs-12">*</small></label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="number" name="budget"
                                                value="{{ old('budget', getAmount(@$job->budget)) }}"
                                                class="form--control form-control" required>
                                            <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                        </div>
                                    </div>
                                    <label class="form--label"> @lang('Make Custom Proposal ?') <small class="text--danger fs-12">*</small></label>
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
                                    <div class="col-sm-12 form-group">
                                        <label for="message" class="form--label"> @lang('Description About Project') <small
                                                class="text--danger">*</small></label>
                                        <textarea class="form--control form-control nicEdit" name="description" id="message"
                                            placeholder="@lang('write Description').. ">{{ old('description', @$job->description) }}</textarea>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Project Skill') </label>
                                            <select class="form-select form--control form-control select2"
                                                name="skill_ids[]" multiple="multiple" required>
                                                @foreach ($skills as $skill)
                                                    <option value="{{ $skill->id }}"
                                                        {{ isset($job) && $job->skills->pluck('id')->contains($skill->id) ? 'selected' : '' }}>
                                                        {{ __($skill->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="radio-btn-wrapper">
                                        <div class="col-sm-12 ">
                                            <label class="form--label"> @lang('Scope of your project work')<small
                                                    class="text--danger">*</small> </label>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="large">
                                                            <span class="text"> @lang('Large') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="project_scope" id="large"
                                                            value="{{ Status::SCOPE_LARGE }}"
                                                            {{ old('project_scope', @$job->project_scope) == Status::SCOPE_LARGE ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="medium">
                                                            <span class="text"> @lang('Medium') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="project_scope" id="medium"
                                                            value="{{ Status::SCOPE_MEDIUM }}"
                                                            {{ old('project_scope', @$job->project_scope) == Status::SCOPE_MEDIUM ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="small">
                                                            <span class="text"> @lang('Small') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="project_scope" id="small"
                                                            value="{{ Status::SCOPE_SMALL }}"
                                                            {{ old('project_scope', @$job->project_scope) == Status::SCOPE_SMALL ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="radio-btn-wrapper">
                                        <div class="col-sm-12">
                                            <label class="form--label"> @lang('How long will your work take?')<small
                                                    class="text--danger">*</small> </label>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="3to6">
                                                            <span class="text"> @lang('3 to 6 months') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="job_longevity" id="3to6" value="4"
                                                            {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_MORE_MONTH ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="1to3">
                                                            <span class="text"> @lang('1 to 3 months') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="job_longevity" id="1to3" value="3"
                                                            {{ old('job_longevity', @$job->job_longevity) == status::JOB_LONGEVITY_ABOVE_MONTH ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="less1m">
                                                            <span class="text"> @lang('Less than 1 month') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="job_longevity" id="less1m" value="2"
                                                            {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_MONTH ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="less1week">
                                                            <span class="text"> @lang('Less than 1 Week') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio"
                                                            name="job_longevity" id="less1week" value="1"
                                                            {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_WEEK ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="radio-btn-wrapper">
                                        <div class="col-sm-12">
                                            <label class="form--label"> @lang('Job skill level?')<small
                                                    class="text--danger">*</small> </label>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="proSkill">
                                                            <span class="text"> @lang('Pro Level') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="skill_level"
                                                            id="proSkill" value="1"
                                                            {{ old('skill_level', @$job->skill_level) == Status::SKILL_PRO ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="expertSkill">
                                                            <span class="text"> @lang('Expert') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="skill_level"
                                                            id="expertSkill" value="2"
                                                            {{ old('skill_level', @$job->skill_level) == Status::SKILL_EXPERT ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="intermediateSkill">
                                                            <span class="text"> @lang('Intermediate') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="skill_level"
                                                            id="intermediateSkill" value="3"
                                                            {{ old('skill_level', @$job->skill_level) == Status::SKILL_INTERMEDIATE ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="form--radio">
                                                        <label class="form-check-label" for="entryLevelSkill">
                                                            <span class="text"> @lang('Entry') </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="skill_level"
                                                            id="entryLevelSkill" value="4"
                                                            {{ old('skill_level', @$job->skill_level) == Status::SKILL_ENTRY ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inner-content__bottom mt-3">
                                        <h6 class="title">@lang('This job post will be end this date') <small class="text--danger fs-12">*</small>
                                        </h6>
                                        <div class="form-group">
                                            <input class="form--control form-control datepicker-here" name="deadline"
                                                placeholder="@lang('Date: YY-MM-DD')"
                                                value="{{ old('deadline', @$job->deadline) }}" autocomplete="off">
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
                                                        <input type="text"
                                                            class="form--control form-control question-input"
                                                            name="questions[]" value="{{ $question }}"
                                                            placeholder="Write your question" maxlength="500" required>
                                                        <span class="icon text--danger remove-question ms-3"
                                                            title="Remove this question" style="cursor: pointer;">
                                                            <i class="las la-trash-alt"></i>
                                                        </span>
                                                    </div>
                                                @empty
                                                    <div class="form-group d-flex align-items-center">
                                                        <input type="text"
                                                            class="form--control form-control question-input"
                                                            name="questions[]" placeholder="Write your question"
                                                            maxlength="255" required>
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
                                            <span class="text"> @lang('Publish')</span>
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
                                <div class="btn-wrapper justify-content-end">
                                    <button class="btn btn--base">@lang('Submit') </button>
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


            var jobSubcategoryId = `{{ @$job->subcategory_id }}`

            $('select[name="category_id"]').on('change', function() {
                let subcategories = $(this).find(`option:selected`).data(`subcategories`);
                let html = `<option value="" disabled>@lang('Specility depend\'s on category')</option>`;
                $.each(subcategories, function(i, subcategory) {
                    let isSelected = jobSubcategoryId == subcategory.id ? 'selected' : '';
                    html +=
                        `<option value="${subcategory.id}" ${isSelected}>${subcategory.name}</option>`;
                });
                $(`select[name=subcategory_id]`).html(html);
            }).change();


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


            $('.buildSlug').on('click', function() {
                let closestForm = $(this).closest('form');
                let name = closestForm.find('[name=title]').val();
                closestForm.find('[name=slug]').val(name);
                closestForm.find('[name=slug]').trigger('input');
            });

            $('[name=slug]').on('input', function() {
                let closestForm = $(this).closest('form');
                closestForm.find('[type=submit]').addClass('disabled')
                let slug = $(this).val();
                slug = slug.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                $(this).val(slug)
                if (slug) {
                    $('.slug-verification').removeClass('d-none');
                    $('.slug-verification').html(`
                        <small class="text--info"><i class="las la-spinner la-spin"></i> @lang('Verifying')</small>
                    `);
                    $.get("{{ route('buyer.job.post.check.slug', @$job->id) }}", {
                        slug: slug
                    }, function(response) {
                        if (!response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--success"><i class="las la-check"></i> @lang('Verified')</small>
                            `);
                            closestForm.find('[type=submit]').removeClass('disabled')
                        }
                        if (response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--danger"><i class="las la-times"></i> @lang('Slug already exists')</small>
                            `);
                        }
                    });
                } else {
                    $('.slug-verification').addClass('d-none');
                }
            })

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
