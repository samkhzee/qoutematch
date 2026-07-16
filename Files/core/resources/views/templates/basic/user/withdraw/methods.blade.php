@extends('Template::layouts.master')
@section('content')
    <div class="container my-60">
        <form action="{{ route('user.withdraw.money') }}" method="post" class="withdraw-form">
            @csrf
            <div class="gateway-card">
                <div class="row justify-content-center gy-sm-4 gy-3">
                    <div class="col-lg-5">
                        <div class="payment-system-list is-scrollable gateway-option-list">
                            @foreach ($withdrawMethod as $data)
                                <label class="payment-item @if ($loop->index > 4) d-none @endif gateway-option"
                                    for="{{ titleToKey($data->name) }}">
                                    <div class="payment-item-left">
                                        <div class="payment-item__thumb">
                                            <img class="payment-item__thumb-img"
                                                src="{{ getImage(getFilePath('withdrawMethod') . '/' . $data->image) }}"
                                                alt="@lang('payment-thumb')">
                                        </div>
                                        <span class="payment-item__name">{{ __($data->name) }}</span>
                                    </div>
                                    <span class="check-type-icon">
                                        <svg class="check-circle" width="13" height="10" viewBox="0 0 13 10"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 5L4.5 8.5L12.5 0.5" stroke="currentColor" stroke-linecap="round"
                                                class="check"></path>
                                        </svg>
                                    </span>
                                    <input class="payment-item__radio gateway-input" id="{{ titleToKey($data->name) }}"
                                        name="method_code" value="{{ $data->id }}"
                                        data-gateway='@json($data)'
                                        data-min-amount="{{ showAmount($data->min_limit) }}"
                                        data-max-amount="{{ showAmount($data->max_limit) }}" type="radio"
                                        value="{{ $data->method_code }}" hidden
                                        @if (old('method_code')) @checked(old('method_code') == $data->method_code) @else
                                    @checked($loop->first) @endif>
                                </label>
                            @endforeach
                            @if ($withdrawMethod->count() > 4)
                                <button class="payment-item__btn more-gateway-option" type="button">
                                    <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                    <span class="payment-item__btn__icon"><i class="las la-angle-down"></i></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="payment-system-list deposit-panel">
                            <p class="deposit-panel__eyebrow">@lang('Withdraw Summary')</p>
                            <h5 class="deposit-panel__heading">@lang('How much would you like to withdraw?')</h5>

                            <label class="deposit-panel__label" for="withdraw-amount-input">@lang('Amount')</label>
                            <div class="deposit-amount-field">
                                <span class="deposit-amount-field__prefix">{{ gs('cur_sym') }}</span>
                                <input id="withdraw-amount-input"
                                    class="deposit-amount-field__input amount form--control"
                                    name="amount"
                                    type="text"
                                    inputmode="decimal"
                                    value="{{ old('amount') }}"
                                    placeholder="0.00"
                                    autocomplete="off">
                                <span class="deposit-amount-field__suffix">{{ gs('cur_text') }}</span>
                            </div>

                            <ul class="deposit-panel__meta">
                                <li>
                                    <span>@lang('Limit')</span>
                                    <strong class="gateway-limit">@lang('0.00')</strong>
                                </li>
                                <li>
                                    <span>
                                        @lang('Processing Charge')
                                        <i class="las la-info-circle proccessing-fee-info"
                                            data-bs-toggle="tooltip"
                                            title="@lang('Processing charge for withdraw method')"></i>
                                    </span>
                                    <strong>{{ gs('cur_sym') }}<span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</strong>
                                </li>
                            </ul>

                            <div class="deposit-panel__total">
                                <span class="deposit-panel__total-label">@lang('Receivable')</span>
                                <strong class="deposit-panel__total-value">{{ gs('cur_sym') }}<span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</strong>
                            </div>

                            <div class="deposit-info gateway-conversion d-none">
                                <div class="deposit-info__title"><p class="text mb-0">@lang('Conversion')</p></div>
                                <div class="deposit-info__input"><p class="text mb-0"></p></div>
                            </div>
                            <div class="deposit-info conversion-currency d-none">
                                <div class="deposit-info__title"><p class="text mb-0">@lang('In') <span class="gateway-currency"></span></p></div>
                                <div class="deposit-info__input"><p class="text mb-0"><span class="in-currency"></span></p></div>
                            </div>

                            <button type="submit" class="btn btn--base w-100 deposit-panel__submit" disabled>
                                @lang('Confirm Withdraw')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
@endsection



@push('script')
    <script>
        "use strict";
        (function($) {

            var amount = parseFloat($('.amount').val() || 0);
            var gateway, minAmount, maxAmount;

            $('.amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');

                let processingFeeInfo =
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for processing fees`
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);

                calculation();
            }

            gatewayChange();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);
                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) - totalPercentCharge - fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_limit) || amount > Number(gateway.max_limit)) {
                    $(".withdraw-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".withdraw-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}") {
                    $('.withdraw-form').addClass('adjust-height')
                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.withdraw-form').removeClass('adjust-height')
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });


            $('.gateway-input').change();
        })(jQuery);
    </script>
@endpush
