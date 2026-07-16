@php
    $dimensions = \App\Constants\ReviewDimension::all();
    $prefix = $prefix ?? 'scores';
@endphp

<div class="structured-review-form">
    @foreach ($dimensions as $key => $label)
        <div class="form-group mb-3 structured-review-dimension">
            <label class="form--label">{{ __($label) }} <small class="text--danger">*</small></label>
            <div class="star-rating structured-review-stars" data-dimension="{{ $key }}">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" name="{{ $prefix }}[{{ $key }}]" value="{{ $i }}"
                        id="{{ $prefix }}_{{ $key }}_star{{ $i }}" class="star-input">
                    <label for="{{ $prefix }}_{{ $key }}_star{{ $i }}"><i class="las la-star"></i></label>
                @endfor
            </div>
        </div>
    @endforeach
</div>
