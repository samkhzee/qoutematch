@php
    $scores = is_array($scores ?? null) ? $scores : [];
    $dimensions = \App\Constants\ReviewDimension::all();
@endphp

<div class="structured-review-scores">
    @foreach ($dimensions as $key => $label)
        @php $score = (int) ($scores[$key] ?? 0); @endphp
        <div class="structured-review-score-row">
            <span class="structured-review-score-label">{{ __($label) }}</span>
            <ul class="rating-list structured-review-score-stars mb-0">
                @for ($i = 1; $i <= 5; $i++)
                    <li class="rating-list__item">
                        <i class="las la-star {{ $i <= $score ? 'text--warning' : 'text-muted' }}"></i>
                    </li>
                @endfor
            </ul>
            <span class="structured-review-score-value">{{ $score }}/5</span>
        </div>
    @endforeach
</div>
