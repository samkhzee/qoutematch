@extends('Template::layouts.frontend')
@section('content')

    @php
        $policyPages = getContent('policy_pages.element', false, null, true);
        $banner = getContent('banner.content', true)->data_values;
    @endphp

    <div class="job-details-section mt-60 mb-120">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="job-details">
                        <div class="details-item">
                            <div class="bid-top">
                                <div class="left">
                                    <h5 class="bid-top__title">{{ __($job->title) }}</h5>
                                    <small> {{ getJobTimeDifference($job->created_at, $job->deadline) }}</small>
                                </div>
                                <div class="right">
                                    @if ($job->custom_budget)
                                        <sup class="d-block">@lang('Flexible budget available.')</sup>
                                    @endif
                                    <h5 class="price">{{ showAmount($job->budget) }}</h5>
                                    <small class="text"> @lang('Bids'): {{ $job->bids_count }} </small>
                                    <small class="text"> @lang('Interviews'): {{ $job->interviews }} </small>
                                </div>
                            </div>
                            <div class="details-item__content">
                                @php echo $job->description @endphp
                            </div>
                            <div class="project-info">
                                <h6 class="project-info__title"> @lang('About the job') </h6>
                                <div class="project-info-wrapper">
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-clock"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Posted Job') </p>
                                            <span class="title">
                                                {{ showDateTime($job->created_at, 'd M, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="project-info__item">
                                        <span class="project-info__icon">
                                            <i class="las la-calendar"></i>
                                        </span>
                                        <div class="project-info__content">
                                            <p class="text"> @lang('Deadline') </p>
                                            <span class="title">{{ showDateTime($job->deadline, 'd M, Y') }}</span>
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
                                            <p class="text"> @lang('Project Scope')</p>
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
                                            <span class="title">@lang('100% Remote job') </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="skill-expert-wrapper">
                                <h6 class="skill-expert-wrapper__title"> @lang('Skill and expertise') </h6>
                                <div class="skill-wrapper">
                                    <ul class="skill-list">
                                        @foreach ($job->skills as $skill)
                                            <li class="skill-list__item">
                                                <span class="skill-list__link">{{ __($skill->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if (!is_null($skillMatchPercent)) 
                                    <div class="skill-match-box mt-3">
                                        <h6>@lang('Skill Match')</h6>
                                        <div class="progress">
                                            <div class="progress-bar {{ $barClass }}" role="progressbar"
                                                style="width: {{ $skillMatchPercent }}%; min-width: 30px;"
                                                aria-valuenow="{{ $skillMatchPercent }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $skillMatchPercent }}%
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($job->questions)
                                <div class="question-section">
                                    <div class="question-header">
                                        <h4>@lang('Job questions for freelancers')</h4>
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

                            <div class="job-share">
                                <div
                                    class="blog-details__share d-flex align-items-center flex-wrap justify-content-start gap-2">
                                    <h6 class="social-share__title mb-0 me-sm-3 me-1 d-inline-block">@lang('Share') :
                                    </h6>
                                    <ul class="social-list">
                                        <li class="social-list__item"><a
                                                href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                                class="social-list__link flex-center" target="__blank"><i
                                                    class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="social-list__item"><a
                                                href="https://twitter.com/share?url={{ url()->current() }}"
                                                class="social-list__link flex-center" target="__blank"> <i
                                                    class="fa-brands fa-x-twitter"></i></a></li>
                                        <li class="social-list__item"><a
                                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}"
                                                class="social-list__link flex-center" target="__blank"> <i
                                                    class="fab fa-linkedin-in"></i></a>
                                        </li>
                                        <li class="social-list__item"><a
                                                href="https://wa.me/?text={{ urlencode(url()->current()) }}"
                                                class="social-list__link flex-center" target="__blank"> <i
                                                    class="fab fa-whatsapp"></i></a>
                                        </li>
                                        <li class="social-list__item"><a
                                                href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($job->title) }}"
                                                class="social-list__link flex-center" target="__blank"> <i
                                                    class="fab fa-telegram"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="details-item">
                            <div class="bid-wrapper">
                                <div class="bid-wrapper__top">
                                    <h6 class="mb-0">{{ @$totalBiddenFreelancers }} - @lang('Freelancers are bidding on this job')</h6>
                                </div>
                                <div class="freelancers-wrapper">
                                    @include('Template::job_explore.freelancer', [
                                        'similarFreelancers' => @$biddenFreelancers,
                                    ])
                                </div>

                                @if (@$totalBiddenFreelancers > 5)
                                    <div class="bid-wrapper__bottom">
                                        <button class="btn-outline--base btn moreFreelancerBtn"> @lang('Load more')
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    @include('Template::job_explore.info')
                </div>
            </div>
        </div>
    </div>

    <div class="modal custom--modal" id="bidModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.bid.store', $job->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-2">{{ __($job->title) }}</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>

                        <p class="mb-3">
                            <i class="las la-angle-double-right"></i>
                            @lang('Are you sure you\'ve read this job post carefully?')
                        </p>

                        <h6 class="mb-3">
                            @if (@$job->custom_budget)
                                @lang('Estimated Budget')
                            @else
                                @lang('Budget')
                            @endif
                            : {{ showAmount($job->budget) }}
                        </h6>

                        @if (@$job->custom_budget)
                            <div class="form-group mb-3">
                                <label class="form-label">@lang('Your Bid Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" class="form-control form--control"
                                        name="bid_amount" placeholder="@lang('Enter your bid amount')" required>
                                    <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label class="form-label">@lang('Estimated Time')</label>
                            <input type="text" class="form-control form--control" name="estimated_time"
                                placeholder="@lang('Estimate in time, days or weeks, e.g. 10 hours, 2 days')" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">@lang('Your Bid Quote')</label>
                            <textarea class="form-control form--control" name="bid_quote" rows="5" placeholder="@lang('Write why you are the best fit for this job.')"
                                required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn--base">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
                "use strict";
                const bidButton = $('.bidModalBtn');

                @auth
                let bidModal = $("#bidModal");
                bidButton.on('click', function() {
                    bidModal.modal("show");
                });
            @endauth


            // Start-load-more-area
            let offset = 5;
            let limit = 5;
            const jobId = `{{ $job->id }}`;
            const moreBtnFreelancers = $('.moreFreelancerBtn');
            const moreBtnJobs = $('.moreJobBtn');

            function loadMoreFreelancers() {
                $.ajax({
                    url: `{{ route('explore.get-similar-freelancers') }}`,
                    method: 'GET',
                    data: {
                        job_id: jobId,
                        offset: offset,
                        limit: limit,
                    },
                    beforeSend: function() {
                        moreBtnFreelancers.html(
                            `<div class="text-center"><div class="spinner-border" role="status"></div></div>`
                        );
                    },
                    success: function(response) {
                        $('.freelancers-wrapper').append(response.data.html);
                        const nextOffset = response.data.next_offset;

                        if (nextOffset !== null) {
                            offset = nextOffset;
                        } else {
                            $('.bid-wrapper__bottom').addClass('d-none');
                            moreBtnFreelancers.hide();
                        }

                        moreBtnFreelancers.html('Load More Freelancers');
                    },
                    error: function() {
                        moreBtnFreelancers.html('@lang('Flexible budget available.')');
                    },
                });
            }

            function loadMoreJobs() {
                $.ajax({
                    url: `{{ route('explore.get-similar-jobs') }}`,
                    method: 'GET',
                    data: {
                        job_skill_ids: jobSkillIds,
                        offset: offset,
                        limit: limit,
                    },
                    beforeSend: function() {
                        moreBtnJobs.html(
                            `<div class="text-center"><div class="spinner-border" role="status"></div></div>`
                        );
                    },
                    success: function(response) {
                        $('.similar-jobs-wrapper').append(response.data.html);
                        const nextOffset = response.data.next_offset;

                        if (nextOffset !== null) {
                            offset = nextOffset;
                        } else {
                            $('.sidebar-item__btn').addClass('d-none');
                            moreBtnJobs.hide();
                        }

                        moreBtnJobs.html('@lang('Bids')');
                    },
                    error: function() {
                        moreBtnJobs.html('@lang('Interviews')');
                    },
                });
            }

            moreBtnFreelancers.on('click', loadMoreFreelancers); moreBtnJobs.on('click', loadMoreJobs);
            // End-load-more-area
            if (bidButton.length > 0) {
                bidButton.addClass('has-indicator');
                setTimeout(() => {
                    bidButton.removeClass('has-indicator');
                }, 10000);
            }
        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .question-section {
            padding: 20px;
        }

        .question-header {
            padding-bottom: 12px;
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


        .project-info-wrapper {
            max-width: unset !important;
        }

        .job-list__item:last-child {
            border-bottom: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .job-share {
            padding: 20px;
        }

        .job-share .blog-item__text {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 5px;
            font-size: 18px;
        }

        @media screen and (max-width: 424px) {
            .job-share .blog-item__text {
                font-size: 15px;
            }
        }

        .job-share .blog-item__text-icon {
            color: hsl(var(--base));
        }

        .job-share .social-list__link {
            border: 1px solid hsl(var(--border-color));
            color: hsl(var(--text-color));
            width: 35px;
            height: 35px;
            background-color: hsl(var(--white)/.1);
            font-size: 16px;
        }

        .blog-details__share-title {
            color: hsl(var(--base));
            margin-bottom: 16px;
            font-weight: 500;
        }

        .blog-details__share .social-list {
            gap: 8px;
        }

        .blog-details__share .social-list__link:hover {
            color: hsl(var(--white)) !important;
            background-color: hsl(var(--base));
        }




        /* indicator */
        .bidModalBtn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }


        .bidModalBtn.disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        @keyframes highlightButton {
            0% {
                transform: scale(1);
                background-color: #007bff;
            }

            50% {
                transform: scale(1.1);
                background-color: #ffcc00;
            }

            100% {
                transform: scale(1);
                background-color: #007bff;
            }
        }

        .bidModalBtn.highlight {
            animation: highlightButton 2s ease-in-out infinite;
        }


        @keyframes spreadStars {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(3);
                opacity: 0;
            }
        }


        .bidModalBtn.has-indicator::after {
            content: '⭐';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            font-size: 20px;
            color: #ffcc00;
            animation: spreadStars 1.5s ease-out infinite;
            opacity: 0;
        }


        .bidModalBtn.has-indicator::before {
            content: '⭐';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            font-size: 20px;
            color: #ff6666;
            animation: spreadStars 1.5s ease-out 0.5s infinite;
            opacity: 0;
        }


        .bidModalBtn.has-indicator span {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            font-size: 20px;
            color: #66ff66;
            animation: spreadStars 1.5s ease-out 1s infinite;
            opacity: 0;
        }

        /* Pulse animation for the indicator */
        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 0.7;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(0.8);
                opacity: 0.7;
            }
        }
    </style>
@endpush
