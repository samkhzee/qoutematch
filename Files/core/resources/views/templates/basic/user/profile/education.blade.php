@extends('Template::layouts.master')
@section('content')
    <div class="profile-main-section">
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="profile-bio">

                        <div class="profile-bio__item">
                            @include('Template::user.profile.top')
                            <form action="{{ route('user.store.profile.education') }}" method="POST">
                                @csrf

                                <button type="button" class="btn btn--base mb-2 ms-auto d-block" id="addNewEducation">
                                    <i class="las la-plus-circle"></i> @lang('Add Education')
                                </button>

                                <div id="education-wrapper">
                                    @forelse ($educations as $index => $education)
                                        <div class="education-item border p-3 mt-3">
                                            <input type="hidden" name="education[{{ $index }}][id]"
                                                value="{{ $education->id }}">

                                            <div class="form-group col-sm-12">
                                                <label class="form-label">@lang('School')</label>
                                                <input class="form-control form--control"
                                                    name="education[{{ $index }}][school]"
                                                    value="{{ $education->school }}" required
                                                    placeholder="@lang('Ex: XYZ University')">
                                            </div>
                                            <div class="row">
                                                <label class="form-label">@lang('Dates Attended (Optional)')</label>
                                                <div class="form-group col-sm-6">
                                                    <input class="form-control form--control"
                                                        name="education[{{ $index }}][year_from]" type="text"
                                                        placeholder="@lang('Year From')" value="{{ $education->year_from }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <input class="form-control form--control" type="text"
                                                        name="education[{{ $index }}][year_to]"
                                                        placeholder="@lang('Year To')" value="{{ $education->year_to }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label">@lang('Degree (Optional)')</label>
                                                    <input type="text" class="form-control form--control"
                                                        name="education[{{ $index }}][degree]"
                                                        value="{{ $education->degree }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label">@lang('Area of Study (Optional)')</label>
                                                    <input type="text" class="form-control form--control"
                                                        name="education[{{ $index }}][area_of_study]"
                                                        placeholder="@lang('Ex: Computer Science | CA')"
                                                        value="{{ $education->area_of_study }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label class="form-label">@lang('Description (Optional)')</label>
                                                <textarea class="form-control form--control" name="education[{{ $index }}][description]">{{ $education->description }}</textarea>
                                            </div>
                                            <span class="remove-education">
                                                <i class="las la-trash"></i>
                                            </span>
                                        </div>
                                    @empty
                                        @include('Template::partials.empty', [
                                            'message' => 'Education not found!',
                                        ])
                                    @endforelse
                                </div>

                                <div class="btn-wrapper">
                                    <a href="{{ route('user.profile.setting') }}" class="btn btn-outline--dark">
                                        <i class="las la-angle-double-left"></i> @lang('Previous') </a>
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
    <x-confirmation-modal />
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {


                function updateEducationIndexes() {
                    $('#education-wrapper .education-item').each(function(index) {
                        $(this).find('input, textarea').each(function() {
                            let name = $(this).attr('name');
                            if (name) {
                                let newName = name.replace(/\[.*?\]/, '[' + index + ']');
                                $(this).attr('name', newName);
                            }
                        });
                    });
                }

                $('#addNewEducation').on('click', function() {
                    $('.empty-message').addClass('d-none');

                    let educationIndex = $('#education-wrapper .education-item').length;

                    const newEducationItem = `
                <div class="education-item mt-4 border p-3">
                    <div class="form-group col-sm-12">
                        <label class="form-label">@lang('School')<span class="text--danger">*</span></label>
                        <input class="form-control form--control" name="education[${educationIndex}][school]" required placeholder="@lang('Ex: XYZ University')">
                    </div>
                    <div class="row">
                        <label class="form-label">@lang('Dates Attended (Optional)')</label>
                        <div class="form-group col-sm-6">
                            <input class="form-control form--control" type="text" name="education[${educationIndex}][year_from]" placeholder="@lang('Year From')">
                        </div>
                        <div class="form-group col-sm-6">
                            <input class="form-control form--control" type="text" name="education[${educationIndex}][year_to]" placeholder="@lang('Year To')">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Degree (Optional)')</label>
                            <input type="text" class="form-control form--control" name="education[${educationIndex}][degree]">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">@lang('Area of Study (Optional)')</label>
                            <input type="text" class="form-control form--control" name="education[${educationIndex}][area_of_study]" placeholder="@lang('Ex: Computer Science | CA')">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="form-label">@lang('Description (Optional)')</label>
                        <textarea class="form-control form--control" name="education[${educationIndex}][description]"></textarea>
                    </div>
                    <span class="remove-education"><i class="las la-trash"></i></span>
                </div>
                `;

                    $('#education-wrapper').append(newEducationItem);



                    updateEducationIndexes();

                    $("html, body").animate({
                        scrollTop: $(document).height()
                    }, "slow");
                });

                $(document).on('click', '.remove-education', function() {
                    $(this).closest('.education-item').remove();
                    updateEducationIndexes();
                });

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
    </style>
@endpush
