@extends('Template::layouts.master')
@section('content')
    <div class="profile-main-section">
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="profile-bio">
                        <div class="profile-bio__item">
                            @include('Template::user.profile.top')
                            <form action="{{ route('user.store.profile.skill') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form--label"> @lang('Your title') </label>
                                    <input type="text" name="tagline" class="form-control form--control"
                                        value="{{ old('tagline', @$user->tagline) }}">
                                    <small class="mt-5">@lang('Enter a single sentence description of your professional skills | experience (e.g. Expert Web Designer with Ajax experience)')</small>
                                </div>
                                <div class="form-group">
                                    <label class="form--label"> @lang('Your Skill') </label>
                                    <select class="form-select form-control form--control select2" name="skill_ids[]"
                                        multiple="multiple" required>
                                        @foreach ($skills as $skill)
                                            <option value="{{ $skill->id }}"
                                                @if ($user->skills->pluck('id')->contains($skill->id)) selected @endif>
                                                {{ __($skill->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form--label"> @lang('Your About') <span
                                            class="text--danger">*</span></label>
                                    <textarea name="about" id="" cols="30" rows="10" class="form-control form--control nicEdit">{{ old('about', @$user->about) }}</textarea>
                                    <small class="mt-5">@lang('Brief description of relevant experience')</small>
                                </div>

                                <div class="btn-wrapper">
                                    <a href="{{ route('user.home') }}" class="btn btn-outline--dark"><i
                                            class="las la-angle-double-left"></i> @lang('Cancel') </a>
                                    <button type="submit" class="btn btn--dark"> @lang('Next') <i
                                            class="las la-angle-double-right"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!--================== sidebar start here ================== -->
                    @include('Template::user.profile.info')
                    <!--================== sidebar end here ==================== -->
                </div>
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
            margin-top: 10px !important;
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

@push('script')
    <script>
        (function($) {
            "use strict";
            $.each($('.select2-auto-tokenize'), function() {
                $(this)
                    .wrap(`<div class="position-relative"></div>`)
                    .select2({
                        tags: true,
                        maximumSelectionLength: 15,
                        tokenSeparators: [','],
                        dropdownParent: $(this).parent()
                    });
            });


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

        })(jQuery);
    </script>
@endpush
