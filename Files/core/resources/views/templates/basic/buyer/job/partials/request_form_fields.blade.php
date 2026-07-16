<div class="row request-form-fields">
    @foreach ($formData as $data)
        @php
            $savedValue = $savedValues[$data->label]['value'] ?? old($data->label);
            $colClass = 'col-md-' . ($data->width ?? '12');
        @endphp
        <div class="{{ $colClass }}">
            <div class="form-group">
                <label class="form--label">
                    {{ __($data->name) }}
                    @if ($data->instruction)
                        <span data-bs-toggle="tooltip" title="{{ __($data->instruction) }}"><i class="fas fa-info-circle"></i></span>
                    @endif
                    @if ($data->is_required === 'required' && in_array($data->type, ['checkbox', 'radio']))
                        <span class="text--danger">*</span>
                    @endif
                </label>

                @if ($data->type === 'text')
                    <input type="text" class="form-control form--control" name="{{ $data->label }}"
                        value="{{ is_array($savedValue) ? '' : $savedValue }}"
                        @if ($data->is_required === 'required') required @endif>
                @elseif ($data->type === 'email')
                    <input type="email" class="form-control form--control" name="{{ $data->label }}"
                        value="{{ is_array($savedValue) ? '' : $savedValue }}"
                        @if ($data->is_required === 'required') required @endif>
                @elseif ($data->type === 'number')
                    <input type="number" class="form-control form--control" name="{{ $data->label }}"
                        value="{{ is_array($savedValue) ? '' : $savedValue }}" step="any"
                        @if ($data->is_required === 'required') required @endif>
                @elseif ($data->type === 'date')
                    <input type="date" class="form-control form--control" name="{{ $data->label }}"
                        value="{{ is_array($savedValue) ? '' : $savedValue }}"
                        @if ($data->is_required === 'required') required @endif>
                @elseif ($data->type === 'textarea')
                    <textarea class="form-control form--control" name="{{ $data->label }}"
                        @if ($data->is_required === 'required') required @endif>{{ is_array($savedValue) ? '' : $savedValue }}</textarea>
                @elseif ($data->type === 'select')
                    <select class="form-select form--control form-control select2-dynamic" name="{{ $data->label }}"
                        @if ($data->is_required === 'required') required @endif>
                        <option value="">@lang('Select One')</option>
                        @foreach ($data->options as $option)
                            <option value="{{ $option }}" @selected($option == $savedValue)>{{ __($option) }}</option>
                        @endforeach
                    </select>
                @elseif ($data->type === 'radio')
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach ($data->options as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="{{ $data->label }}"
                                    value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}"
                                    @checked($option == $savedValue)
                                    @if ($data->is_required === 'required') required @endif>
                                <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ __($option) }}</label>
                            </div>
                        @endforeach
                    </div>
                @elseif ($data->type === 'checkbox')
                    <div class="d-flex gap-3 flex-wrap">
                        @php $checkedValues = (array) ($savedValue ?? []); @endphp
                        @foreach ($data->options as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="{{ $data->label }}[]"
                                    value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}"
                                    @checked(in_array($option, $checkedValues))>
                                <label class="form-check-label" for="{{ $data->label }}_{{ titleToKey($option) }}">{{ __($option) }}</label>
                            </div>
                        @endforeach
                    </div>
                @elseif ($data->type === 'file')
                    @if (!empty($savedValue) && !is_array($savedValue))
                        <p class="mb-2">
                            <small class="text--success">
                                <i class="las la-paperclip"></i> @lang('File uploaded') —
                                <a href="{{ route('buyer.download.attachment', encrypt(getFilePath('requestDocuments') . '/' . $savedValue)) }}" target="_blank">@lang('View current file')</a>
                            </small>
                        </p>
                    @endif
                    <input type="file" class="form-control form--control" name="{{ $data->label }}"
                        @if ($data->is_required === 'required' && empty($savedValue)) required @endif
                        accept="@foreach (explode(',', $data->extensions) as $ext).{{ trim($ext) }}, @endforeach">
                    @if ($data->extensions)
                        <small class="text-muted d-block mt-1">@lang('Supported'): {{ $data->extensions }}</small>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
</div>
