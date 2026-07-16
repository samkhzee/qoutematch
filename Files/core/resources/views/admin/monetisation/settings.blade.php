@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.monetisation.settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Enable monetisation')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Enabled')" data-off="@lang('Disabled')"
                                name="monetisation_enabled" value="1" @checked($general->monetisation_enabled ?? false)>
                            <small class="text-muted d-block mt-1">@lang('When disabled, providers submit quotes for free (MVP default).')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Monetisation mode')</label>
                            <select class="form-control" name="monetisation_mode" required>
                                @foreach (['credits' => 'Lead credits only', 'subscription' => 'Subscriptions only', 'both' => 'Credits + subscriptions'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($general->monetisation_mode ?? 'credits') === $value)>{{ __($label) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Credits per quote submission')</label>
                            <input type="number" class="form-control" name="quote_credit_cost" min="1"
                                value="{{ old('quote_credit_cost', $general->quote_credit_cost ?? 1) }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Welcome credits on provider approval')</label>
                            <input type="number" class="form-control" name="provider_welcome_credits" min="0"
                                value="{{ old('provider_welcome_credits', $general->provider_welcome_credits ?? 0) }}" required>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update Settings')</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h6 class="mb-2">@lang('Blueprint §17')</h6>
                    <p class="text-muted mb-0">@lang('Customers remain free. Providers can buy lead credits or subscribe for unlimited quotes when monetisation is enabled.')</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.monetisation.packages') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-box"></i> @lang('Credit Packages')
    </a>
    <a href="{{ route('admin.monetisation.plans') }}" class="btn btn-sm btn-outline--info">
        <i class="las la-crown"></i> @lang('Subscription Plans')
    </a>
@endpush
