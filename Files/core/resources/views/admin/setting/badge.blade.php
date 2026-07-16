@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="mb-3">@lang('Note: Users earning the minimum amounts will automatically receive a badge.')</div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Min Amount')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($badges as $badge)
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex flex-wrap justify-content-end justify-content-lg-start">
                                                <span class="avatar avatar--xs me-2">
                                                    <img
                                                        src="{{ getImage(getFilePath('badge') . '/' . $badge->image, getFileSize('badge')) }}">
                                                </span>
                                                <span class="mt-1">
                                                    {{ __($badge->badge_name) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ showAmount($badge->min_amount) }}</td>
                                        @php
                                            $badge->image_with_path = getImage(
                                                getFilePath('badge') . '/' . $badge->image,
                                                getFileSize('badge'),
                                            );
                                        @endphp
                                        <td>
                                            <div class="btn--group">
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    <button class="btn btn-outline--primary editBtn cuModalBtn btn-sm"
                                                        data-modal_title="@lang('Update Category')"
                                                        data-resource="{{ $badge }}">
                                                        <i class="las la-pen"></i>@lang('Edit')
                                                    </button>
                                                    <button
                                                        class="btn btn-outline--danger btn-sm confirmationBtn"
                                                        data-question="@lang('Are you sure to permanently delete this badge?')"
                                                        data-action="{{ route('admin.badge.delete', $badge->id) }}">
                                                        <i class="la la-trash"></i> @lang('Delete')
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($badges->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($badges) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.badge.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader :imagePath="getImage(null, getFileSize('badge'))" :size="getFileSize('badge')" class="w-100" id="imageEdit"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" name="badge_name" type="text" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Min Earning')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="min_amount" type="text" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Badge')"
        data-placeholder="{{ getImage(getFilePath('badge') . '/' . '', getFileSize('badge')) }}" type="button">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('style')
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }

        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }

        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
    </style>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.cuModalBtn').on('click', function() {
                $('#cuModal').find('[name=image]').closest('.form-group').find('label').first().addClass(
                    'required');
            });
        })(jQuery);
    </script>
@endpush
