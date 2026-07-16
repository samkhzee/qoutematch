@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.config_category.top_bar')
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Jobs')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subcategories as $subcategory)
                                    <tr>
                                        <td>
                                            {{ __($subcategory->name) }}
                                        </td>
                                        <td>
                                            {{ __($subcategory->category->name) }}
                                        </td>
                                        <td>
                                            <span> {{ $subcategory->jobs_count }}</span>
                                        </td>
                                        <td>
                                            @php echo $subcategory->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <div class="btn--group">
                                                <div class="d-flex justify-content-end flex-wrap gap-1">
                                                    <button type="button"
                                                    class="btn btn-sm btn-outline--primary ms-1 mb-2 cuModalBtn"
                                                    data-modal_title="@lang('Update Subcategory')"
                                                    data-resource="{{ $subcategory }}">
                                                    <i class="las la-pen"></i>@lang('Edit')
                                                </button>
                                                    @if ($subcategory->status == Status::DISABLE)
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline--success ms-1 mb-2 confirmationBtn"
                                                            data-action="{{ route('admin.category.subcategory.status', $subcategory->id) }}"
                                                            data-question="@lang('Are you sure to enable this subcategory?')">
                                                            <i class="la la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline--danger mb-2 confirmationBtn"
                                                            data-action="{{ route('admin.category.subcategory.status', $subcategory->id) }}"
                                                            data-question="@lang('Are you sure to disable this subcategory?')">
                                                            <i class="la la-eye-slash"></i> @lang('Disable')
                                                        </button>
                                                    @endif

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
                @if ($subcategories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($subcategories) }}
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
                <form action="{{ route('admin.category.subcategory.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Category')</label>
                            <select class="form-control select2" name="category_id">
                                <option value="" disabled selected>@lang('Select One')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ __($category->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form-control" name="name" type="text" required>
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
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add Subcategory')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
@endpush
