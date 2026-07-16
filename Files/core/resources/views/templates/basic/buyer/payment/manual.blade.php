@extends('Template::layouts.buyer_master')

@section('content')
    @php
        $formAction = $formAction ?? (
            !empty($data->user_id) && empty($data->buyer_id)
                ? route('user.monetisation.payment.manual.update')
                : route('buyer.deposit.manual.update')
        );
        $isProviderMonetisation = !empty($data->user_id) && empty($data->buyer_id);
    @endphp
    <div class="container">
        <div class="row justify-content-center my-60">
            <div class="col-xxl-8 col-lg-10 col-md-10">
                <div class="card custom--card">
                    <div class="card-body  ">
                        <form action="{{ $formAction }}" method="POST" class="disableSubmission"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-primary">
                                        <p class="mb-0"><i class="las la-info-circle"></i> @lang('You are requesting')
                                            <b>{{ showAmount($data['amount']) }}</b>
                                            @if ($isProviderMonetisation)
                                                @lang('to purchase lead credits.')
                                            @else
                                                @lang('to deposit.')
                                            @endif
                                            @lang('Please pay')
                                            <b>{{ showAmount($data['final_amount'], currencyFormat: false) . ' ' . $data['method_currency'] }}
                                            </b> @lang('for successful payment.')
                                        </p>
                                    </div>

                                    <div class="mb-3">@php echo  $data->gateway->description @endphp</div>

                                </div>

                                <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
