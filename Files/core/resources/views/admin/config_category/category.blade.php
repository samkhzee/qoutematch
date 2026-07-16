@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.config_category.top_bar')
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Request Form')</th>
                                    <th>@lang('Quote Form')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Subcategories')</th>
                                    <th>@lang('Jobs')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="text-start">
                                            <div class="d-flex flex-wrap justify-content-end justify-content-lg-start">
                                                <span class="avatar avatar--xs me-2">
                                                    <img
                                                        src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}">
                                                </span>
                                                <span class="mt-1">
                                                    {{ __($category->name) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($category->requestForm)
                                                <code class="small">{{ $category->requestForm->act }}</code>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->quoteForm)
                                                <code class="small">{{ $category->quoteForm->act }}</code>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->is_featured == Status::YES)
                                                <span class="badge badge--info"> @lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning"> @lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.category.subcategories', $category->id) }}"
                                                type="button"
                                                class="btn btn-outline--primary btn-sm">{{ $category->subcategories_count }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.jobs.index', $category->id) }}" type="button"
                                                class="btn btn-outline--info btn-sm">{{ $category->jobs_count }}</a>
                                        </td>
                                        <td>
                                            @php echo $category->statusBadge; @endphp
                                        </td>
                                        @php
                                            $category->image_with_path = getImage(
                                                getFilePath('category') . '/' . $category->image,
                                                getFileSize('category'),
                                            );
                                        @endphp
                                        <td>
                                            <div class="btn--group">
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    <button class="btn btn-outline--primary cuModalBtn btn-sm"
                                                        data-modal_title="@lang('Update Category')"
                                                        data-resource="{{ $category }}">
                                                        <i class="las la-pen"></i>@lang('Edit')
                                                    </button>
                                                    <button class="btn btn-outline--info btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="las la-ellipsis-v"></i> @lang('More')
                                                    </button>
                                                    <ul class="dropdown-menu px-2">
                                                        <li>
                                                            @if ($category->status == Status::ENABLE)
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to disable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}">
                                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to enable this category?')"
                                                                    data-action="{{ route('admin.category.status', $category->id) }}">
                                                                    <i class="la la-eye"></i> @lang('Enable')
                                                                </a>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            @if ($category->is_featured == Status::YES)
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to unmark feature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}">
                                                                    <i class="las la-star-half-alt"></i>
                                                                    @lang('Unmark Feature')</a>
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)"
                                                                    class="dropdown-item cursor-pointer confirmationBtn"
                                                                    data-question="@lang('Are you sure to mark feature this category?')"
                                                                    data-action="{{ route('admin.category.feature', $category->id) }}">
                                                                    <i class="las la-star"></i> @lang('Mark Feature')
                                                                </a>
                                                            @endif
                                                        </li>
                                                    </ul>
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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <x-image-uploader :imagePath="getImage(null, getFileSize('category'))" :size="getFileSize('category')" class="w-100" id="imageEdit"
                                :required="false" />
                        </div>

                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Request Form')</label>
                            <select class="form-control select2" name="request_form_id" data-minimum-results-for-search="0">
                                <option value="">@lang('None')</option>
                                @foreach ($requestForms as $requestForm)
                                    <option value="{{ $requestForm->id }}">{{ $requestForm->displayLabel() }} ({{ $requestForm->act }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">@lang('Shown when customers post a job in this category.')</small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Quote Form')</label>
                            <select class="form-control select2" name="quote_form_id" data-minimum-results-for-search="0">
                                <option value="">@lang('None')</option>
                                @foreach ($quoteForms as $quoteForm)
                                    <option value="{{ $quoteForm->id }}">{{ $quoteForm->displayLabel() }} ({{ $quoteForm->act }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">@lang('Shown when providers submit a quote for jobs in this category.')</small>
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
    <x-search-form />
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Category')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.cuModalBtn').on('click', function() {
                $('#cuModal').find('[name=image]').closest('.form-group').find('label').first().addClass(
                    'required');

                const resource = $(this).data('resource');
                if (resource) {
                    setTimeout(function() {
                        if (!resource.request_form_id) {
                            $('#cuModal [name=request_form_id]').val('').trigger('change');
                        }
                        if (!resource.quote_form_id) {
                            $('#cuModal [name=quote_form_id]').val('').trigger('change');
                        }
                    }, 100);
                }
            });
        })(jQuery);
    </script>
@endpush
