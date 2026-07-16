@extends('Template::layouts.buyer_master')
@section('content')
    <div class="container my-60">
        <form class="deposit-form" action="{{ route('buyer.deposit.insert') }}" method="post">
            @csrf
            <input name="currency" type="hidden">
            <div class="gateway-card">
                <div class="row justify-content-center gy-sm-4 gy-3">
                    <div class="col-lg-5">
                        <div class="payment-system-list is-scrollable gateway-option-list">
                            @forelse ($gatewayCurrency as $data)
                                <label class="payment-item @if ($loop->index > 4) d-none @endif gateway-option"
                                    for="{{ titleToKey($data->name) }}">
                                    <div class="payment-item-left">
                                        <div class="payment-item__thumb">
                                            <img class="payment-item__thumb-img"
                                                src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
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
                                        name="gateway" data-gateway='@json($data)'
                                        data-min-amount="{{ showAmount($data->min_amount) }}"
                                        data-max-amount="{{ showAmount($data->max_amount) }}" type="radio"
                                        value="{{ $data->method_code }}" hidden
                                        @if (old('gateway')) @checked(old('gateway') == $data->method_code) @else
                                @checked($loop->first) @endif>
                                </label>
                            @empty
                                <div class="alert alert-warning mb-0">
                                    @lang('No deposit methods are configured yet. Ask admin to add a payment gateway under Admin → Payment Gateways → Manual Gateway.')
                                </div>
                            @endforelse
                            @if ($gatewayCurrency->count() > 4)
                                <button class="payment-item__btn more-gateway-option" type="button">
                                    <p class="payment-item__btn-text">@lang('Show All Payment Options')</p>
                                    <span class="payment-item__btn__icon"><i class="las la-angle-down"></i></span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="payment-system-list deposit-panel">
                            <p class="deposit-panel__eyebrow">@lang('Deposit Summary')</p>
                            <h5 class="deposit-panel__heading">@lang('How much would you like to add?')</h5>

                            <label class="deposit-panel__label" for="deposit-amount-input">@lang('Amount')</label>
                            <div class="deposit-amount-field">
                                <span class="deposit-amount-field__prefix">{{ gs('cur_sym') }}</span>
                                <input id="deposit-amount-input"
                                    class="deposit-amount-field__input amount form--control"
                                    name="amount"
                                    type="text"
                                    inputmode="decimal"
                                    value="{{ old('amount', @$amount) }}"
                                    placeholder="0.00"
                                    @if (@$amount) readonly @endif
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
                                            title="@lang('Processing charge for payment gateways')"></i>
                                    </span>
                                    <strong><span class="processing-fee">@lang('0.00')</span> {{ __(gs('cur_text')) }}</strong>
                                </li>
                            </ul>

                            <div class="deposit-panel__total">
                                <span class="deposit-panel__total-label">@lang('Total Payable')</span>
                                <strong class="deposit-panel__total-value"><span class="final-amount">@lang('0.00')</span> {{ __(gs('cur_text')) }}</strong>
                            </div>

                            <div class="deposit-info gateway-conversion d-none">
                                <div class="deposit-info__title"><p class="text mb-0">@lang('Conversion')</p></div>
                                <div class="deposit-info__input"><p class="text mb-0"></p></div>
                            </div>
                            <div class="deposit-info conversion-currency d-none">
                                <div class="deposit-info__title"><p class="text mb-0">@lang('In') <span class="gateway-currency"></span></p></div>
                                <div class="deposit-info__input"><p class="text mb-0"><span class="in-currency"></span></p></div>
                            </div>
                            <div class="d-none crypto-message mb-3">
                                <div class="note-text">
                                    <span class="icon"><i class="fas fa-info-circle"></i></span>
                                    <p class="mb-0">@lang('Conversion with') <span class="gateway-currency"></span> @lang('and final value will Show on next step')</p>
                                </div>
                            </div>

                            <button class="btn btn--base w-100 deposit-panel__submit" type="submit" disabled>
                                @lang('Payment Confirm')
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
                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`
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
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('.gateway-input').change();
        })(jQuery);
    </script>
@endpush
