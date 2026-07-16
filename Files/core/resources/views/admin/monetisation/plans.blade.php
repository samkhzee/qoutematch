@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h5 class="card-title mb-0">@lang('Add Subscription Plan')</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.monetisation.plans.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Slug')</label>
                                    <input type="text" class="form-control" name="slug" required placeholder="pro-monthly">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <input type="number" step="any" class="form-control" name="price" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Duration (days)')</label>
                                    <input type="number" class="form-control" name="duration_days" min="1" value="30" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Bonus credits')</label>
                                    <input type="number" class="form-control" name="monthly_credits" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">
                                <input type="checkbox" name="unlimited_quotes" value="1"> @lang('Unlimited quote submissions')
                            </label>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Create Plan')</button>
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
                            <th>@lang('Plan')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Duration')</th>
                            <th>@lang('Credits')</th>
                            <th>@lang('Unlimited')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ __($plan->name) }}</span><br>
                                    <code class="small">{{ $plan->slug }}</code>
                                </td>
                                <td>{{ showAmount($plan->price) }}</td>
                                <td>{{ $plan->duration_days }} @lang('days')</td>
                                <td>{{ $plan->monthly_credits ?: '—' }}</td>
                                <td>{{ $plan->unlimited_quotes ? __('Yes') : __('No') }}</td>
                                <td>@php echo $plan->statusBadge; @endphp</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                            data-resource="{{ $plan }}">
                                            <i class="las la-pen"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                            data-question="@lang('Delete this plan?')"
                                            data-action="{{ route('admin.monetisation.plans.delete', $plan->id) }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="100%" class="text-center text-muted">@lang('No plans yet')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($plans->hasPages())
            <div class="card-footer">{{ paginateLinks($plans) }}</div>
        @endif
    </div>

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">@lang('Edit Plan')</h5></div>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Slug')</label>
                                    <input type="text" class="form-control" name="slug" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <input type="number" step="any" class="form-control" name="price" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Duration (days)')</label>
                                    <input type="number" class="form-control" name="duration_days" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Bonus credits')</label>
                                    <input type="number" class="form-control" name="monthly_credits" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">
                                <input type="checkbox" name="unlimited_quotes" value="1"> @lang('Unlimited quote submissions')
                            </label>
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
            modal.find('form').attr('action', `{{ url('admin/monetisation/plans') }}/${resource.id}`);
            modal.find('[name=name]').val(resource.name);
            modal.find('[name=slug]').val(resource.slug);
            modal.find('[name=price]').val(resource.price);
            modal.find('[name=duration_days]').val(resource.duration_days);
            modal.find('[name=monthly_credits]').val(resource.monthly_credits);
            modal.find('[name=description]').val(resource.description);
            modal.find('[name=sort_order]').val(resource.sort_order);
            modal.find('[name=unlimited_quotes]').prop('checked', resource.unlimited_quotes == 1);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
