@php
    use App\Lib\VerificationBadgeService;

    $badges = $badges ?? ($user ? VerificationBadgeService::badgesForUser($user) : []);

    if (!empty($only)) {
        $onlyKeys = is_array($only) ? $only : [$only];
        $badges = array_values(array_filter($badges, fn ($badge) => in_array($badge['key'], $onlyKeys, true)));
    }
@endphp

@if (count($badges))
    <span class="verification-badges {{ $class ?? '' }}">
        @foreach ($badges as $badge)
            <span
                class="verification-badge verification-badge--{{ $badge['tone'] ?? $badge['key'] }}{{ !empty($compact) ? ' verification-badge--compact' : '' }}"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="{{ __($badge['label']) }}"
                aria-label="{{ __($badge['label']) }}"
            >
                <i class="{{ $badge['icon'] }}" aria-hidden="true"></i>
                @empty($compact)
                    <span>{{ __($badge['label']) }}</span>
                @endempty
            </span>
        @endforeach
    </span>

    @once
        @push('script')
            <script>
                (function () {
                    function initVerificationBadgeTooltips() {
                        if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
                            return;
                        }

                        document.querySelectorAll('.verification-badge[data-bs-toggle="tooltip"]').forEach(function (element) {
                            bootstrap.Tooltip.getInstance(element)?.dispose();
                            new bootstrap.Tooltip(element);
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initVerificationBadgeTooltips);
                    } else {
                        initVerificationBadgeTooltips();
                    }
                })();
            </script>
        @endpush
    @endonce
@endif
