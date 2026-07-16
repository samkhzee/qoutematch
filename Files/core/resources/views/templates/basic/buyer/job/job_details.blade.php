@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container-fluid px-0">
        <div class="row gy-4">
            <div class="col-xxl-8 col-xl-7">
                <div class="job-post-content">
                    <div class="inner-content">
                        @include('Template::buyer.job.top')
                    </div>
                    <form action="{{ route('buyer.job.post.details.store', @$job->id) }}" method="POST"
                        class="disableSubmission" enctype="multipart/form-data">
                        @csrf
                        <div class="inner-content border-top">
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                        <label class="form--label"> @lang('Write a title for your job post') </label><a href="javascript:void(0)"
                                            class="buildSlug">
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

                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label for="message" class="form--label"> @lang('Describe Your Requirement') <small
                                            class="text--danger">*</small></label>
                                    <textarea class="form--control form-control nicEdit" name="description" id="message"
                                        placeholder="@lang('Describe what you need quotes for').. ">{{ old('description', @$job->description) }}</textarea>
                                </div>
                            </div>

                            <div id="dynamic-request-form-wrapper" class="inner-content border-top pt-4 mt-2 {{ $requestForm ? '' : 'd-none' }}">
                                <h6 class="mb-3">@lang('Service Requirements')</h6>
                                <div id="dynamic-request-form-fields">
                                    @if ($requestForm)
                                        @include('Template::buyer.job.partials.request_form_fields', [
                                            'formData' => $requestForm->form_data,
                                            'savedValues' => $savedValues,
                                        ])
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="inner-content border-0">
                            <div class="btn-wrapper">
                                <a href="#" class="btn btn-outline--base disabled">
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

            var jobSubcategoryId = `{{ @$job->subcategory_id }}`
            var jobId = `{{ @$job->id ?? 0 }}`
            var categoryFormMap = @json($categoryFormMap);

            function loadRequestForm(categoryId) {
                if (!categoryFormMap[categoryId]) {
                    $('#dynamic-request-form-wrapper').addClass('d-none');
                    $('#dynamic-request-form-fields').html('');
                    return;
                }

                $('#dynamic-request-form-wrapper').removeClass('d-none');
                $('#dynamic-request-form-fields').html(`
                    <div class="text-center py-3"><i class="las la-spinner la-spin"></i> @lang('Loading form')...</div>
                `);

                $.get("{{ url('buyer/job/post/request-form') }}/" + categoryId, { job_id: jobId }, function(html) {
                    $('#dynamic-request-form-fields').html(html);
                    $('.select2-dynamic').select2();
                    const tooltipTriggerList = document.querySelectorAll('#dynamic-request-form-fields [data-bs-toggle="tooltip"]');
                    [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
                }).fail(function(xhr) {
                    if (xhr.status === 204) {
                        $('#dynamic-request-form-wrapper').addClass('d-none');
                        $('#dynamic-request-form-fields').html('');
                    }
                });
            }

            $('select[name="category_id"]').on('change', function() {
                let subcategories = $(this).find(`option:selected`).data(`subcategories`);
                let html = `<option value="" disabled>@lang('Specility depend\'s on category')</option>`;
                $.each(subcategories, function(i, subcategory) {
                    let isSelected = jobSubcategoryId == subcategory.id ? 'selected' : '';
                    html +=
                        `<option value="${subcategory.id}" ${isSelected}>${subcategory.name}</option>`;
                });
                $(`select[name=subcategory_id]`).html(html);

                loadRequestForm($(this).val());
            }).change();


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
