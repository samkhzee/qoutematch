@php
    $content = getContent('subscribe.content', true)->data_values;
@endphp

<div class="subscribe-section pt-120">
    <div class="container">
        <div class="subscribe-wrapper highlight">
            <div class="subscribe-wrapper__shape">
                <img src="{{ frontendImage('subscribe', @$content->shape, '130x290') }}" alt="">
            </div>
            <div class="subscribe-content">
                <span class="subscribe-content__shape">
                    <img src="{{ asset(activeTemplate(true) . '/shape/subscribe.png') }}" alt="">
                </span>
                <h4 class="subscribe-content__title s-highlight" data-s-break="-1" data-s-length="1">
                    {{ __(@$content->heading) }}</h4>
                <p class="subscribe-content__text"> <span class="fw-bold"> {{ __(@$content->subheading) }}</span></p>
                <form class="subscribe-form">
                    <div class="input-group">
                        <input type="email" class="form-control form--control h-50" required name="email"
                            placeholder="@lang('Enter your email address')">
                        <button class="input-group-text input-text-style"> <i
                                class="fa-regular fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
            <div class="subscribe-thumb">
                <img src="{{ frontendImage('subscribe', @$content->image, '1070x930') }}" alt="">
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        'use strict';

        $(function() {
            $('.subscribe-form').on('submit', function(event) {
                event.preventDefault();
                var email = $('.subscribe-form').find('[name="email"]').val();
                if (!email) {
                    notify('error', 'Email field is required');
                } else {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        url: "{{ route('subscribe') }}",
                        method: "POST",
                        data: {
                            email: email
                        },
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                                $('.subscribe-form').find('[name="email"]').val('');
                            } else {
                                notify('error', response.error);
                            }
                        }
                    });
                }
            });

        })
    </script>
@endpush
