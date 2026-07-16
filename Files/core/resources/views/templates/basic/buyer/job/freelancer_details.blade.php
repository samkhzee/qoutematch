@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container-fluid px-0">
        <div class="row gy-4">
            <div class="col-xxl-8 col-xl-7">
                <div class="job-post-content">
                    <div class="inner-content">
                        @include('Template::buyer.job.top')
                    </div>
                    <form action="{{ route('buyer.job.post.freelancer.details.store', @$job->id) }}" method="POST"
                        class="disableSubmission">
                        @csrf
                        <div class="inner-content border-top">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form--label">@lang('Project Skill') </label>
                                    <select class="form-select form--control form-control select2" name="skill_ids[]"
                                        multiple="multiple" required>
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
                                <div class="col-sm-12">
                                    <label class="form--label"> @lang('Job skill level?')<small class="text--danger">*</small>
                                    </label>
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

                            <div class="radio-btn-wrapper">
                                <div class="col-sm-12 ">
                                    <label class="form--label"> @lang('Scope of your project work')<small class="text--danger">*</small>
                                    </label>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="large">
                                                    <span class="text"> @lang('Large') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="project_scope"
                                                    id="large" value="{{ Status::SCOPE_LARGE }}"
                                                    {{ old('project_scope', @$job->project_scope) == Status::SCOPE_LARGE ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="medium">
                                                    <span class="text"> @lang('Medium') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="project_scope"
                                                    id="medium" value="{{ Status::SCOPE_MEDIUM }}"
                                                    {{ old('project_scope', @$job->project_scope) == Status::SCOPE_MEDIUM ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="small">
                                                    <span class="text"> @lang('Small') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="project_scope"
                                                    id="small" value="{{ Status::SCOPE_SMALL }}"
                                                    {{ old('project_scope', @$job->project_scope) == Status::SCOPE_SMALL ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="radio-btn-wrapper">
                                <div class="col-sm-12">
                                    <label class="form--label"> @lang('How long will your work take?')<small class="text--danger">*</small>
                                    </label>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="3to6">
                                                    <span class="text"> @lang('3 to 6 months') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="job_longevity"
                                                    id="3to6" value="4"
                                                    {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_MORE_MONTH ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="1to3">
                                                    <span class="text"> @lang('1 to 3 months') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="job_longevity"
                                                    id="1to3" value="3"
                                                    {{ old('job_longevity', @$job->job_longevity) == status::JOB_LONGEVITY_ABOVE_MONTH ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="less1m">
                                                    <span class="text"> @lang('Less than 1 month') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="job_longevity"
                                                    id="less1m" value="2"
                                                    {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_MONTH ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="form--radio">
                                                <label class="form-check-label" for="less1week">
                                                    <span class="text"> @lang('Less than 1 Week') </span>
                                                </label>
                                                <input class="form-check-input" type="radio" name="job_longevity"
                                                    id="less1week" value="1"
                                                    {{ old('job_longevity', @$job->job_longevity) == Status::JOB_LONGEVITY_WEEK ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-content border-0">
                            <div class="btn-wrapper">
                                <a href="{{ route('buyer.job.post.details', $job->id) }}" class="btn btn-outline--base">
                                    <i class="las la-angle-double-left"></i> @lang('Previous') </a>
                                <button type="submit" class="btn btn--base"> @lang('Next') <i
                                        class="las la-angle-double-right"></i></button>
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
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
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
