@extends('Template::layouts.frontend')
@section('content')
    <div class="job-bid-section mt-60 mb-120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="bid-job-item">
                        <div class="bid-job-item__top">
                            <div class="bid-top">
                                <div class="left">
                                    <h5 class="bid-top__title"> {{ __($job->title) }}</h5>
                                    <span class="skill-list__link"> @lang('100% Remote Work') </span>
                                    <small class="text">
                                        @php use Carbon\Carbon; @endphp
                                        {{ getJobTimeDifference($job->created_at, $job->deadline) }}
                                    </small>
                                </div>
                                @if (!$job->is_approved)
                                    <div class="right">
                                        <a href="{{ route('buyer.job.post.details', $job->id) }}"
                                            class="btn btn--base btn--sm">@lang('Edit')</a>
                                    </div>
                                @endif
                            </div>

                            <p class="bid-job-item__desc">
                                @php echo $job->description @endphp
                            </p>
                            @include('Template::buyer.job.partials.request_data_summary', ['requestFields' => $requestFields ?? []])
                            <div class="project-info-wrapper mt-4">
                                <div class="project-info__item">
                                    <span class="project-info__icon">
                                        <i class="las la-layer-group"></i>
                                    </span>
                                    <div class="project-info__content">
                                        <p class="text"> @lang('Category') </p>
                                        <span class="title">
                                            {{ __($job->category->name) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="project-info__item">
                                    <span class="project-info__icon">
                                        <i class="las la-object-ungroup"></i>
                                    </span>
                                    <div class="project-info__content">
                                        <p class="text"> @lang('Specility') </p>
                                        <span class="title"> {{ __($job->subcategory->name) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bid-job-item">
                        <div class="bid-job-item__top">
                            <h6 class="bid-job-item__title"> @lang('Price | Budget') </h6>
                            <small> @lang('What is the price or budget a freelancer did like to bid for this job?')</small>
                            <p class="bid-job-item__text d-flex align-items-center gap-4 flex-wrap mt-1">
                                <span> {{ showAmount($job->budget) }} </span>
                            </p>
                        </div>
                    </div>
                    <div class="bid-job-item">
                        <div class="bid-job-item__top">
                            <div class="project-info">
                                <h6 class="project-info__title">@lang('About the job') </h6>
                                <div class="project-info-wrapper">
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-calendar"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Deadline')</p>
                                            <span class="title"> {{ showDateTime($job->deadline, 'd M, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-brain"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Experience level') </p>
                                            <span class="title">
                                                @if ($job->skill_level == Status::SKILL_PRO)
                                                    @lang('Pro Level')
                                                @elseif($job->skill_level == Status::SKILL_EXPERT)
                                                    @lang('Expert')
                                                @elseif($job->skill_level == Status::SKILL_INTERMEDIATE)
                                                    @lang('Intermediate')
                                                @else
                                                    @lang('Entry')
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-briefcase"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Project Scope') </p>
                                            <span class="title">
                                                @if ($job->project_scope == Status::SCOPE_LARGE)
                                                    @lang('Large')
                                                @elseif($job->project_scope == Status::SCOPE_MEDIUM)
                                                    @lang('Medium')
                                                @else
                                                    @lang('Small')
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-map-marker"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Location') </p>
                                            <span class="title"> @lang('Remote Job') </span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-history"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Job Longevity') </p>
                                            <span class="title">
                                                @if ($job->job_longevity == Status::JOB_LONGEVITY_WEEK)
                                                    @lang('Less than 1 Week')
                                                @elseif($job->job_longevity == Status::JOB_LONGEVITY_MONTH)
                                                    @lang('Less than 1 month')
                                                @elseif($job->job_longevity == Status::JOB_LONGEVITY_ABOVE_MONTH)
                                                    @lang('1 to 3 months')
                                                @else
                                                    @lang('3 to 6 months')
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-map-marker"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Location') </p>
                                            <span class="title"> @lang('Remote Job') </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="skill-expert-wrapper">
                            <h6 class="skill-expert-wrapper__title"> @lang('Skill and Expertise') </h6>
                            <div class="skill-wrapper">
                                <div>
                                    <p class="skill-wrapper__title"> </p>
                                    <ul class="skill-list">
                                        @foreach ($job->skills as $skill)
                                            <li class="skill-list__item">
                                                <span class="skill-list__link">
                                                    {{ __($skill->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @if ($job->questions)
                            <div class="question-section">
                                <div class="question-header">
                                    <h4>@lang('Job Questions')</h4>
                                </div>
                                <ul class="question-list">
                                    @foreach ($job->questions as $question)
                                        <li class="question-item">
                                            <i class="las la-question-circle question-icon"></i>
                                            <span>{{ $question }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="col-lg-4">
                    <!--================== sidebar start here ================== -->
                    @include('Template::buyer.job.info')
                    <!--================== sidebar end here ==================== -->
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection



@push('style')
    <style>
        .question-section {
            padding: 20px;
        }

        .question-header {
            margin-bottom: 15px;
            border-bottom: 2px solid hsl(var(--base));
            padding-bottom: 8px;
        }

        .question-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .question-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .question-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            margin-bottom: 10px;
            padding: 10px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .question-item:hover {
            background-color: #e9f5ff;
            border-color: hsl(var(--base));
            transform: translateY(-2px);
        }

        .question-icon {
            font-size: 1.2rem;
            color: hsl(var(--base));
        }

        .question-item span {
            font-size: 1rem;
            color: #333;
        }

        @media (max-width: 768px) {
            .question-header h4 {
                font-size: 1.1rem;
            }

            .question-item {
                font-size: 0.95rem;
                padding: 8px 12px;
            }

            .question-icon {
                font-size: 1rem;
            }
        }
    </style>
@endpush
