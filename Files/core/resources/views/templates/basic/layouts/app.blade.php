@if (!empty($inertiaBridge))
    @yield('panel')
    @stack('style-lib')
    @stack('style')
    @stack('script-lib')
    @include('partials.notify')
    @stack('script')
@else
<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!--Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/custom.css') }}">

    @stack('style')

    <link rel="stylesheet"
        href="{{ asset(activeTemplate(true) . 'css/color.php') }}?color={{ gs('base_color') }}&secondColor={{ gs('secondary_color') }}">
</head>
@php echo loadExtension('google-analytics') @endphp

<body>

    @stack('fbComment')

    @include('Template::partials.preloader')

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @yield('panel')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp
    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <!--cookies start -->
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="cookies-card__desc">
                {{ @$cookie->data_values->short_desc }} <a class="text--base" href="{{ route('cookie.policy') }}"
                    target="_blank">@lang('learn more')</a>
            </p>
            <div class="cookies-card__btn">
                <a href="javascript:void(0)" class="btn btn--base btn--sm policy">@lang('Allow')</a>
                <a href="javascript:void(0)" class="btn btn-outline--secondary btn--sm policy">@lang('Reject')</a>
            </div>
        </div>
        <!--cookies  end-->
    @endif

    <!--jQuery first, then Popper.js, then Bootstrap JS-->
    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        (function ($) {
            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                return;
            }

            $.fn.modal = function (action) {
                return this.each(function () {
                    const instance = bootstrap.Modal.getOrCreateInstance(this);

                    if (action === 'show') {
                        instance.show();
                    } else if (action === 'hide') {
                        instance.hide();
                    } else if (action === 'toggle') {
                        instance.toggle();
                    } else if (action === 'dispose') {
                        instance.dispose();
                    }
                });
            };
        })(jQuery);
    </script>

    @stack('script-lib')

    <script src="{{ asset(activeTemplate(true) . 'js/main.js') }}"></script>
    <script src="{{ asset(activeTemplate(true) . 'js/slick.min.js') }}"></script>

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    @stack('script')

    <script>
        (function($) {
            "use strict";

            $(".langSel").on("click", function() {
                let code = $(this).data('code');
                window.location.href = "{{ route('home') }}/change/" + code;
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }

            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);
    </script>

</body>

</html>
@endif
