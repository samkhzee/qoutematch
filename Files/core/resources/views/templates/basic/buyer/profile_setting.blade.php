@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container-fluid px-0">
        <div class="row justify-content-center">
            <div class="col-xxl-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <form class="disableSubmission" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center gy-4">
                                <div class="col-xl-4 col-lg-12">
                                    <div class="user-profile m-auto">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilepath('buyerProfile') . '/' . $user->image, getFileSize('buyerProfile')) }}"
                                                alt="user">
                                        </div>
                                        <div class="content">
                                            <small>@lang('Accept'): @lang('.png, .jpg, .jpeg') /
                                                {{ getFileSize('userProfile') }}@lang('px')</small>
                                            <h5 class="title mb-0 mt-1">{{ $user->fullname }}</h5>
                                        </div>

                                        <label class="show-image btn btn--base w-100 text-center"
                                            for="profile-image">@lang('Change Profile Photo')</label>
                                        <input class="form-control form--control" id="profile-image" name="image"
                                            type="file" accept="image/*" hidden>

                                        <div class="remove-image btn btn--sm btn--danger w-100 text-center">
                                            <i class="las la-times"></i> @lang('Remove')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-12">
                                    <div class="user-profile-form row mb--20 gy-3">
                                        <div class="form-group form-group col-md-6">
                                            <label class="form--label">@lang('First Name')</label>
                                            <input class="form-control form--control" name="firstname" type="text"
                                                value="{{ $user->firstname }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('Last Name')</label>
                                            <input class="form-control form--control" name="lastname" type="text"
                                                value="{{ $user->lastname }}" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form--label">@lang('Email Address')</label>
                                            <input class="form-control form--control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('Mobile Number')</label>
                                            <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('Address')</label>
                                            <input class="form-control form--control" name="address" type="text"
                                                value="{{ @$user->address }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('State')</label>
                                            <input class="form-control form--control" name="state" type="text"
                                                value="{{ @$user->state }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('Zip Code')</label>
                                            <input class="form-control form--control" name="zip" type="text"
                                                value="{{ @$user->zip }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('City')</label>
                                            <input class="form-control form--control" name="city" type="text"
                                                value="{{ @$user->city }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form--label">@lang('Country')</label>
                                            <input class="form-control form--control" value="{{ @$user->country_name }}"
                                                disabled>
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
                                                <small class="mt-2">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma'))
                                                    @lang('or') <code>@lang('enter')</code>
                                                    @lang('key').</small>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
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




@push('script')
    <script>
        (function($) {
            "use strict";

            var prevImg = $('.user-profile .thumb').html();

            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $('.user-profile').find('.thumb');
                        preview.html(`<img src="${e.target.result}" alt="user">`);
                        preview.addClass('has-image');
                        preview.hide();
                        preview.fadeIn(650);
                        $(".remove-image").show();
                        $(".show-image").hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#profile-image").on('change', function() {
                proPicURL(this);
            });
            $(".remove-image").on('click', function() {
                $(".user-profile .thumb").html(prevImg);
                $(".user-profile .thumb").removeClass('has-image');
                $(this).hide();
                $(".show-image").show();
            })

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

@push('style')
    <style>
        .btn {
            padding: 10px 24px;
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

        .bg--primary {
            color: #fff !important;
        }
    </style>
@endpush
