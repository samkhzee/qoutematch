@if (!empty($requestFields))
    <div class="request-data-summary mt-4">
        <h6 class="mb-3">@lang('Request Details')</h6>
        <div class="row gy-3">
            @foreach ($requestFields as $field)
                <div class="col-md-6">
                    <div class="request-data-item">
                        <span class="request-data-item__label">{{ __($field['name']) }}</span>
                        @if ($field['isFile'])
                            <a href="{{ $field['value'] }}" class="request-data-item__value" target="_blank">
                                <i class="las la-download"></i> @lang('Download file')
                            </a>
                        @else
                            <span class="request-data-item__value">{{ __($field['value']) }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
