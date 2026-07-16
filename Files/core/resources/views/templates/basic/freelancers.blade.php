@extends('Template::layouts.frontend')
@section("content")
    <div class="talent-main-section mb-120 mt-60">
        <div class="talent-section my-60">
            <div class="container">
                <!--====================== filter section start here ====================== -->
                <div class="filter-wrapper">
                    <div class="filter-wrapper__content">
                        <span class="filter-wrapper__content-title">@lang("Filter")</span>
                        <form class="filter-form" method="GET">
                            <select class="form-select form--control select2" id="rating" name="rating" data-minimum-results-for-search="-1" data-placeholder="@lang("Select Rating")">
                                <option value="0" disabled @if (request()->rating == 0) selected @endif>@lang("All Star")</option>
                                <option value="1" @if (request()->rating == 1) selected @endif>@lang("1 Star")</option>
                                <option value="2" @if (request()->rating == 2) selected @endif>@lang("2 Star")</option>
                                <option value="3" @if (request()->rating == 3) selected @endif>@lang("3 Star")</option>
                                <option value="4" @if (request()->rating == 4) selected @endif>@lang("4 Star")</option>
                                <option value="5" @if (request()->rating == 5) selected @endif>@lang("5 Star")</option>
                            </select>
                            <select class="form-select form--control select2" id="skill" name="skill" data-placeholder="@lang("Select Skill")">
                                <option value="" @if (request()->skill == "") selected @endif>
                                    @lang("Skills")</option>
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}" @if (request()->skill == $skill->id) selected @endif>
                                        {{ __($skill->name) }}</option>
                                @endforeach
                            </select>
                            <input class="form-control form--control" id="search" name="search" type="search" value="{{ request()->search }}" placeholder="@lang("Search Talent")">
                            <button class="btn btn--base">
                                <i class="las la-filter"></i>
                            </button>
                        </form>
                    </div>
                    <div class="filter-wrapper__right d-none d-lg-block">
                        <p class="filter-wrapper__right-text">
                            {{ $totalFreelancer }} @lang("result")
                        </p>
                    </div>
                </div>
                <!--====================== filter section end here ======================== -->

                <div class="row gy-4 justify-content-center">
                    @forelse ($freelancers ?? [] as $freelancer)
                        <div class="col-xl-3 col-sm-6">
                            @include('Template::partials.freelancer')
                        </div>
                    @empty
                        <div class="col-12">
                            @include("Template::partials.empty", ["message" => "Talents not found!"])
                        </div>
                    @endforelse
                    @if ($freelancers->hasPages())
                        {{ paginateLinks($freelancers) }}
                    @endif
                </div>
            </div>
        </div>

        @if (@$sections->secs != null)
            @foreach (json_decode($sections->secs) as $sec)
                @include('Template::sections.' . $sec)
            @endforeach
        @endif

    </div>
@endsection

@push("script-lib")
    <script src="{{ asset("assets/global/js/select2.min.js") }}"></script>
@endpush
@push("style-lib")
    <link href="{{ asset("assets/global/css/select2.min.css") }}" rel="stylesheet">
@endpush

@push("style")
    <style>
        .fa-lg {
            font-size: unset !important;
            vertical-align: unset !important;
        }

        .position-relative {
            width: 100% !important;
        }

        .filter-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            background-color: hsl(var(--white));
            padding: 30px;
            border-radius: 12px;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            margin-bottom: 30px;
        }

        .filter-wrapper__content {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 20px;
            flex-grow: 1;
        }

        .filter-wrapper__right-text {
            color: hsl(var(--heading-color));
            font-weight: 600;
            font-size: 20px;
        }

        @media (min-width: 992px) and (max-width: 1399px) {
            .filter-wrapper__right-text {
                margin-top: 30px;
            }
        }

        .filter-wrapper .filter-form {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .filter-wrapper__content-title {
            color: hsl(var(--heading-color));
            font-weight: 600;
            font-size: 14px;
        }

        .filter-wrapper .filter-form .form--control {
            font-size: 14px;
            color: hsl(var(--text-color));
        }

        @media screen and (max-width: 1399px) {
            .filter-wrapper__content {
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start;
                -webkit-box-align: start;
                -ms-flex-align: start;
                align-items: flex-start;
            }
        }

        @media screen and (max-width: 991px) {
            .filter-wrapper {
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                gap: 20px;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start;
                -webkit-box-align: start;
                -ms-flex-align: start;
                align-items: flex-start;
            }
        }

        @media screen and (max-width: 991px) {
            .filter-wrapper .filter-form {
                min-width: unset;
                max-width: unset;
                width: 100%;
            }
        }

        @media screen and (max-width: 991px) {
            .filter-wrapper .filter-form .form--control {
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
            }
        }
    </style>
@endpush
