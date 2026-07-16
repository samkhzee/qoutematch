@extends('Template::layouts.app')
@section('panel')
    @if (empty($inertiaBridge))
    @include('Template::partials.header')


    <main>
        @if (!request()->routeIs('home') && !request()->routeIs('user.login') && !request()->routeIs('user.register'))
            @include('Template::partials.breadcrumb')
        @endif
        @yield('content')
    </main>

    @include('Template::partials.footer')
    @else
        @yield('content')
    @endif
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/odometer.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset(activeTemplate(true) . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset(activeTemplate(true) . 'js/viewport.jquery.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            "use strict";
            $('.s-highlight').each(function() {
                var $heading = $(this);
                var text = $heading.text().trim();
                var words = text.split(' ');
                var sBreakValue = parseInt($heading.data('s-break')) || 0;
                var sLengthValue = parseInt($heading.data('s-length')) || 1;

                var totalWords = words.length;
                var startIndex = (sBreakValue < 0) ? Math.max(0, totalWords + sBreakValue) : sBreakValue;
                var endIndex = Math.min(totalWords, startIndex + sLengthValue);

                var coloredText = words.map(function(word, index) {
                    if (index >= startIndex && index < endIndex) {
                        return '<span class="text--base">' + word + '</span>';
                    }
                    return word;
                }).join(' ');

                $heading.html(coloredText);
            });
        });
    </script>
@endpush
