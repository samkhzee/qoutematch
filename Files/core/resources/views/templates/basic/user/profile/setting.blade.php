@extends('Template::layouts.master')
@section('content')
    <div class="profile-main-section">
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <div class=" col-xl-8 col-lg-12">
                    <div class="profile-bio">
                        <div class="profile-bio__item">
                            @include('Template::user.profile.top')
                            <form class="register" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row gy-4 justify-content-center">
                                    <div class="form-group col-md-4">
                                        <label class="form-label">@lang('Profile Photo')</label>
                                        <x-image-uploader image="{{ $user->image }}" class="w-100" type="userProfile"
                                            :required=false />
                                    </div>
                                    <div class="form-group col-md-8">
                                        <div class="row">
                                            <div class="col-sm-6 form-group">
                                                <label class="form-label">@lang('First Name')</label>
                                                <input type="text" class="form-control form--control" name="firstname"
                                                    value="{{ $user->firstname }}" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">@lang('Last Name')</label>
                                                <input type="text" class="form-control form--control" name="lastname"
                                                    value="{{ $user->lastname }}" required>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group ">
                                                    <label class="form--label">@lang('Language') </label>
                                                    <select class="form-select form--control select2-auto-tokenize"
                                                        name="language[]" multiple="multiple" required>
                                                        @if (@$user->language)
                                                            @foreach (@$user->language as $option)
                                                                <option value="{{ $option }}" selected>
                                                                    {{ __($option) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="mt-2">@lang('Separate multiple keywords by')
                                                        <code>,</code>(@lang('comma'))
                                                        @lang('or') <code>@lang('enter')</code>
                                                        @lang('key').</small>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">@lang('E-mail Address')</label>
                                                <input class="form-control form--control" value="{{ $user->email }}"
                                                    readonly>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">@lang('Mobile Number')</label>
                                                <input class="form-control form--control" value="{{ $user->mobile }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Address')</label>
                                        <input type="text" class="form-control form--control" name="address"
                                            value="{{ @$user->address }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('State')</label>
                                        <input type="text" class="form-control form--control" name="state"
                                            value="{{ @$user->state }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="form-label">@lang('Zip Code')</label>
                                        <input type="text" class="form-control form--control" name="zip"
                                            value="{{ @$user->zip }}">
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label class="form-label">@lang('City')</label>
                                        <input type="text" class="form-control form--control" name="city"
                                            value="{{ @$user->city }}">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="form-label">@lang('Country')</label>
                                        <input class="form-control form--control" value="{{ @$user->country_name }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="btn-wrapper">
                                    <a href="{{ route('user.profile.skill') }}" class="btn btn-outline--dark">
                                        <i class="las la-angle-double-left"></i> @lang('Previous') </a>
                                    <button type="submit" class="btn btn--dark"> @lang('Next') <i
                                            class="las la-angle-double-right"></i></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class=" col-xl-4 col-lg-12">
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
@endpush
@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
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

        .bg--primary {
            color: hsl(var(--white)) !important;
            background-color: hsl(var(--base)) !important;
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
                        maximumSelectionLength: 10,
                        tokenSeparators: [','],
                        dropdownParent: $(this).parent()
                    });
            });

        })(jQuery);
    </script>
@endpush
