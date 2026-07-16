@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.config_category.top_bar')
    @endpush

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                <a href="{{ route('admin.marketplace.forms.index') }}"
                    class="btn btn-sm {{ empty($type) ? 'btn--primary' : 'btn-outline--primary' }}">@lang('All')</a>
                <a href="{{ route('admin.marketplace.forms.index', ['type' => 'request']) }}"
                    class="btn btn-sm {{ $type === 'request' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Request Forms')</a>
                <a href="{{ route('admin.marketplace.forms.index', ['type' => 'quote']) }}"
                    class="btn btn-sm {{ $type === 'quote' ? 'btn--primary' : 'btn-outline--primary' }}">@lang('Quote Forms')</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Form Key')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Fields')</th>
                                    <th>@lang('Linked Categories')</th>
                                    <th>@lang('Updated')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forms as $form)
                                    @php
                                        $linked = $categoryMap[$form->id] ?? [];
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $form->displayLabel() }}</span><br>
                                            <code class="small">{{ $form->act }}</code>
                                        </td>
                                        <td>
                                            @if ($form->isRequestForm())
                                                <span class="badge badge--primary">@lang('Request')</span>
                                            @else
                                                <span class="badge badge--info">@lang('Quote')</span>
                                            @endif
                                        </td>
                                        <td>{{ $form->fieldCount() }}</td>
                                        <td>
                                            @if (count($linked))
                                                {{ implode(', ', $linked) }}
                                            @else
                                                <span class="text-muted">@lang('None')</span>
                                            @endif
                                        </td>
                                        <td>{{ showDateTime($form->updated_at) }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('admin.marketplace.forms.edit', $form->id) }}"
                                                    class="btn btn-outline--primary btn-sm">
                                                    <i class="las la-pen"></i> @lang('Edit Fields')
                                                </a>
                                                @if (! count($linked))
                                                    <button type="button"
                                                        class="btn btn-outline--danger btn-sm confirmationBtn"
                                                        data-question="@lang('Delete this form permanently?')"
                                                        data-action="{{ route('admin.marketplace.forms.delete', $form->id) }}">
                                                        <i class="las la-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No forms found.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($forms->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($forms) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="createFormModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Create Form')</h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.marketplace.forms.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Form Type')</label>
                            <select class="form-control" name="type" id="formTypeSelect" required>
                                <option value="">@lang('Select One')</option>
                                <option value="request">@lang('Request form (customer job post)')</option>
                                <option value="quote">@lang('Quote form (provider bid)')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Key suffix')</label>
                            <div class="input-group">
                                <span class="input-group-text" id="formKeyPrefix">request_</span>
                                <input class="form-control" name="slug" type="text" required
                                    pattern="[a-z0-9_]+" maxlength="30"
                                    placeholder="e.g. builders"
                                    aria-describedby="formKeyPrefix">
                            </div>
                            <small class="text-muted">@lang('Lowercase letters, numbers, and underscores only. Full key preview:') <code id="formKeyPreview">request_</code></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Create & Edit Fields')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Search by form key" />
    <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#createFormModal">
        <i class="las la-plus"></i>@lang('Add New Form')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function updateFormKeyPreview() {
                const type = $('#formTypeSelect').val() || 'request';
                const slug = ($('[name=slug]').val() || '').toLowerCase().replace(/[^a-z0-9_]/g, '');
                const prefix = type + '_';
                $('#formKeyPrefix').text(prefix);
                $('#formKeyPreview').text(prefix + slug);
            }

            $('#formTypeSelect, [name=slug]').on('input change', updateFormKeyPreview);
            updateFormKeyPreview();
        })(jQuery);
    </script>
@endpush
