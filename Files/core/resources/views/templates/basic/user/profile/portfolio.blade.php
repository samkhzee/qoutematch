@extends('Template::layouts.master')
@section('content')
    <div class="profile-main-section">
        <div class="container-fluid px-0">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="profile-bio">
                        <div class="profile-bio__item">
                            @include('Template::user.profile.top')

                            <button type="button" class="btn btn--base mb-2 ms-auto d-block portfolioModalBtn">
                                <i class="las la-plus-circle"></i> @lang('Add Portfolio')
                            </button>

                            <div class="dashboard-table">
                                <table class="table table--responsive--md mt-4">
                                    <thead>
                                        <tr>
                                            <th> @lang('Image') </th>
                                            <th> @lang('Title') </th>
                                            <th> @lang('Role') </th>
                                            <th> @lang('Status') </th>
                                            <th> @lang('Action') </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($portfolios as $portfolio)
                                            @php
                                                $portfolio->image_with_path = getImage(
                                                    getFilePath('portfolio') . '/' . $portfolio->image,
                                                    getFileSize('portfolio'),
                                                );
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="avatar avatar--sm">
                                                        <img src="{{ getImage(getFilePath('portfolio') . '/' . $portfolio->image, avatar: true) }}"
                                                            alt="Image">
                                                    </div>
                                                </td>
                                                <td><span class="clamping"> {{ __($portfolio->title) }} </span></td>
                                                <td><span class="clamping"> {{ __(@$portfolio->role) }} </span></td>
                                                <td>
                                                    @php echo $portfolio->statusBadge @endphp
                                                </td>
                                                <td>
                                                    <div class="action-btn">
                                                        <button class="action-btn__icon">
                                                            <i class="fa-solid fa-caret-down"></i>
                                                        </button>
                                                        <ul class="action-dropdown">
                                                            <li class="action-dropdown__item portfolioModalBtn"
                                                                data-modal_title="@lang('Update Portfolio')"
                                                                data-resource="{{ $portfolio }}"><a
                                                                    class="action-dropdown__link" href="javascript:void(0)">
                                                                    <span class="text">@lang('Edit')</span>
                                                                </a></li>
                                                            <li class="action-dropdown__item">
                                                                @if ($portfolio->status)
                                                                    <a class="action-dropdown__link  portfolioEDBtn"
                                                                        href="javascript:void(0)"
                                                                        data-question="@lang('Are you sure to disable this portfolio?')"
                                                                        data-action="{{ route('user.status.profile.portfolio', $portfolio->id) }}">
                                                                        <span class="text">@lang('Disable') </span>
                                                                    </a>
                                                                @else
                                                                    <a class="action-dropdown__link  portfolioEDBtn"
                                                                        href="javascript:void(0)"
                                                                        data-question="@lang('Are you sure to Enable this portfolio?')"
                                                                        data-action="{{ route('user.status.profile.portfolio', $portfolio->id) }}">
                                                                        <span class="text">@lang('Enable') </span>
                                                                    </a>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center msg-center">
                                                    @include('Template::partials.empty', [
                                                        'message' => 'Portfolio not found!',
                                                    ])
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($portfolios->hasPages())
                                <div class="mt-2">
                                    {{ paginateLinks($portfolios) }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="btn-wrapper">
                        <a href="{{ route('user.profile.education') }}" class="btn btn-outline--dark">
                            <i class="las la-angle-double-left"></i>@lang('Previous') </a>
                        @if ($user->work_profile_complete)
                            <button type="submit" class="btn btn--danger confirmationBtn"
                                data-question="@lang('Are you sure to draft your profile?')" data-action="{{ route('user.profile.complete') }}">
                                @lang('Draft') <i class="las la-pencil-ruler"></i></button>
                        @else
                            <button type="submit" class="btn btn--base confirmationBtn" data-question="@lang('Are you sure to publish your profile?')"
                                data-action="{{ route('user.profile.complete') }}"> @lang('Publish') <i
                                    class="las la-check-circle"></i></button>
                        @endif

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


    <div class="modal custom--modal" id="portfolioModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('user.store.profile.portfolio') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="portfolio_id" value="0">
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-2 modal-title"></h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Project Title')</label>
                            <input class="form-control form--control" name="title" type="text" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Your Role (optional)')</label>
                            <input class="form-control form--control" name="role" type="text">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Project Description')</label>
                            <textarea class="form-control form--control" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Skills and Deliverables')</label>
                            <select class="form-select form--control select2-auto-tokenize" name="skill_ids[]"
                                multiple="multiple" required>
                                @foreach ($skills as $skill)
                                    <option value="{{ $skill->id }}" @if (in_array($skill->id, $user->skill_ids ?? [])) selected @endif>
                                        {{ __($skill->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="mt-2">@lang('Separate multiple keywords by') <code>,</code>(@lang('comma')) @lang('or')
                                <code>@lang('enter')</code> @lang('key').</small>
                        </div>
                        <div class="form-group">
                            <label class="form--label"> @lang('Project Cover Image') </label>
                            <x-image-uploader :imagePath="getImage(null, getFileSize('portfolio'))" :size="getFileSize('portfolio')" class="w-100" id="imageEdit"
                                :required="false" />
                        </div>
                        <div class="text-end">
                            <button class="btn btn--base" type="submit">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="portfolioEDModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 10px !important;
        }

        .select2-container {
            width: 100% !important;
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
                        maximumSelectionLength: 15,
                        tokenSeparators: [','],
                        dropdownParent: $(this).parent()
                    });
            });


            // Open Portfolio Modal and Populate Data
            let portfolioModal = $("#portfolioModal");
            let form = portfolioModal.find("form");
            const action = form[0] ? form[0].action : null;

            $(".portfolioModalBtn").on('click', function() {

                let data = $(this).data();
                let resource = data.resource ?? null;

                if (!resource) {
                    form[0].reset();
                    form[0].action = `${action}`;
                    portfolioModal.find(".modal-title").text(`@lang('Add New Project')`);
                }
                if (resource) {
                    portfolioModal.find(".modal-title").text(`${data.modal_title}`);
                    form[0].action = `${action}/${resource.id}`;
                    // If form has image


                    if (resource.image_with_path) {
                        let preview = portfolioModal.find('.image-upload-wrapper .image-upload-preview');
                        $(preview).css('background-image', `url(${resource.image_with_path})`);
                        $(preview).addClass('has-image');
                    }

                    portfolioModal.find("[name='title']").val(resource.title);
                    portfolioModal.find("[name='role']").val(resource.role);
                    portfolioModal.find("[name='description']").val(resource.description);
                    // Handle skill selection
                    if (resource.skill_ids) {
                        let skillSelect = portfolioModal.find("[name='skill_ids[]']");
                        skillSelect.val(resource.skill_ids);
                        skillSelect.trigger("change");
                    }
                }
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

                portfolioModal.modal("show");
            });

            // Delete Modal
            $('.portfolioEDBtn').on('click', function() {
                var modal = $('#portfolioEDModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .profile-bio .pagination {
            border: 1px solid hsl(var(--black)/.1);
        }

        .profile-bio .table tbody tr:last-child td:first-child {
            border-radius: 0;
        }

        .profile-bio .table tbody tr:last-child td:last-child {
            border-radius: 0;
        }
    </style>
@endpush
