<?php

namespace App\Lib;

use App\Models\Deposit;
use App\Models\Form;
use App\Models\GatewayCurrency;
use Illuminate\Support\Collection;

class PaymentResource
{
    public static function depositGateways(Collection $gatewayCurrency): array
    {
        return $gatewayCurrency->map(fn (GatewayCurrency $gateway) => [
            'methodCode' => $gateway->method_code,
            'currency' => $gateway->currency,
            'name' => __($gateway->name),
            'image' => getImage(getFilePath('gateway') . '/' . $gateway->method->image),
            'minAmount' => (float) $gateway->min_amount,
            'maxAmount' => (float) $gateway->max_amount,
            'fixedCharge' => (float) $gateway->fixed_charge,
            'percentCharge' => (float) $gateway->percent_charge,
            'rate' => (float) $gateway->rate,
            'minAmountFormatted' => showAmount($gateway->min_amount),
            'maxAmountFormatted' => showAmount($gateway->max_amount),
        ])->values()->all();
    }

    public static function manualPayment(Deposit $deposit, string $formAction, bool $isProviderMonetisation = false): array
    {
        $gateway = $deposit->gatewayCurrency()?->method;
        $form = $gateway?->form;

        return [
            'amount' => showAmount($deposit->amount),
            'finalAmount' => showAmount($deposit->final_amount, currencyFormat: false) . ' ' . $deposit->method_currency,
            'description' => $gateway?->description,
            'isProviderMonetisation' => $isProviderMonetisation,
            'fields' => AccountResource::formFields($form instanceof Form ? $form : null),
            'submitUrl' => $formAction,
        ];
    }

    public static function gatewayCheckout(string $layout, object $gatewayData, Deposit $deposit, string $pageTitle): \Inertia\Response
    {
        $html = view("Template::{$gatewayData->view}", [
            'data' => $gatewayData,
            'pageTitle' => $pageTitle,
            'deposit' => $deposit,
            'inertiaBridge' => true,
        ])->render();

        return \Inertia\Inertia::render('Shared/GatewayCheckout', [
            'layout' => $layout,
            'html' => $html,
            'pageTitle' => $pageTitle,
            'deposit' => [
                'amount' => showAmount($deposit->amount),
                'trx' => $deposit->trx,
                'finalAmount' => showAmount($deposit->final_amount, currencyFormat: false) . ' ' . $deposit->method_currency,
            ],
            'gateway' => [
                'name' => $deposit->gateway?->name ?? $deposit->methodName(),
                'alias' => (string) $deposit->method_code,
                'currency' => (string) $deposit->method_currency,
            ],
        ]);
    }
}
