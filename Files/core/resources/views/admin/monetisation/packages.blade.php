@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">@lang('Add Credit Package')</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.monetisation.packages.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Credits')</label>
                                    <input type="number" class="form-control" name="credits" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Bonus credits')</label>
                                    <input type="number" class="form-control" name="bonus_credits" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <input type="number" step="any" class="form-control" name="price" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Sort order')</label>
                                    <input type="number" class="form-control" name="sort_order" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Create Package')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive--md table-responsive">
                <table class="table table--light style--two">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Credits')</th>
                            <th>@lang('Bonus')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td>{{ __($package->name) }}</td>
                                <td>{{ $package->credits }}</td>
                                <td>{{ $package->bonus_credits }}</td>
                                <td>{{ showAmount($package->price) }}</td>
                                <td>@php echo $package->statusBadge; @endphp</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                            data-resource="{{ $package }}">
                                            <i class="las la-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-question="@lang('Delete this package?')"
                                            data-action="{{ route('admin.monetisation.packages.delete', $package->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="100%" class="text-center text-muted">@lang('No packages yet')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($packages->hasPages())
            <div class="card-footer">{{ paginateLinks($packages) }}</div>
        @endif
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">@lang('Edit Package')</h5></div>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Credits')</label>
                            <input type="number" class="form-control" name="credits" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Bonus credits')</label>
                            <input type="number" class="form-control" name="bonus_credits" min="0">
                        </div>
                        <div class="form-group">
                            <label>@lang('Price')</label>
                            <input type="number" step="any" class="form-control" name="price" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Sort order')</label>
                            <input type="number" class="form-control" name="sort_order" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.monetisation.settings') }}" class="btn btn-sm btn-outline--dark">
        <i class="las la-cog"></i> @lang('Settings')
    </a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.editBtn').on('click', function () {
            const resource = $(this).data('resource');
            const modal = $('#editModal');
            modal.find('form').attr('action', `{{ url('admin/monetisation/packages') }}/${resource.id}`);
            modal.find('[name=name]').val(resource.name);
            modal.find('[name=credits]').val(resource.credits);
            modal.find('[name=bonus_credits]').val(resource.bonus_credits);
            modal.find('[name=price]').val(resource.price);
            modal.find('[name=sort_order]').val(resource.sort_order);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
