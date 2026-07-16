<?php

/**
 * Enables deposit & withdraw for local/testing.
 * Creates a Manual Bank Transfer gateway + a Bank Transfer withdraw method when missing.
 *
 * Usage: php scripts/setup-wallet-methods.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Form;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\WithdrawMethod;

$currency = gs('cur_text') ?: 'USD';
$symbol = gs('cur_sym') ?: '$';

function ensureManualDepositForm(): Form
{
    $form = Form::where('act', 'manual_deposit')->first();
    if ($form) {
        return $form;
    }

    $form = new Form();
    $form->act = 'manual_deposit';
    $form->form_data = [
        'transaction_reference' => [
            'name' => 'Transaction Reference',
            'label' => 'transaction_reference',
            'is_required' => 'optional',
            'instruction' => 'Optional: enter your bank transfer reference number.',
            'extensions' => '',
            'options' => [],
            'type' => 'text',
            'width' => '12',
        ],
    ];
    $form->save();

    return $form;
}

$depositForm = ensureManualDepositForm();

Gateway::manual()->where(function ($q) {
    $q->whereNull('form_id')->orWhere('form_id', 0);
})->each(function (Gateway $gateway) use ($depositForm) {
    $gateway->form_id = $depositForm->id;
    $gateway->save();
});

$activeCurrencies = GatewayCurrency::whereHas('method', fn ($q) => $q->where('status', Status::ENABLE))->count();
if ($activeCurrencies === 0) {
    $lastManual = Gateway::manual()->orderByDesc('code')->first();
    $code = $lastManual ? ((int) $lastManual->code + 1) : 1000;

    $gateway = new Gateway();
    $gateway->form_id = $depositForm->id;
    $gateway->code = $code;
    $gateway->name = 'Bank Transfer';
    $gateway->alias = 'bank_transfer';
    $gateway->image = '';
    $gateway->status = Status::ENABLE;
    $gateway->gateway_parameters = json_encode([]);
    $gateway->supported_currencies = json_encode([]);
    $gateway->crypto = Status::DISABLE;
    $gateway->description = 'Transfer to our bank account. Admin will approve your deposit after verification.';
    $gateway->save();

    $gatewayCurrency = new GatewayCurrency();
    $gatewayCurrency->name = 'Bank Transfer';
    $gatewayCurrency->gateway_alias = 'bank_transfer';
    $gatewayCurrency->currency = $currency;
    $gatewayCurrency->symbol = $symbol;
    $gatewayCurrency->method_code = $code;
    $gatewayCurrency->min_amount = 10;
    $gatewayCurrency->max_amount = 100000;
    $gatewayCurrency->fixed_charge = 0;
    $gatewayCurrency->percent_charge = 0;
    $gatewayCurrency->rate = 1;
    $gatewayCurrency->save();

    echo "Created manual deposit gateway: Bank Transfer (code {$code}, currency {$currency})\n";
} else {
    echo "Deposit gateway already configured ({$activeCurrencies} active option(s)).\n";
}

if (!WithdrawMethod::active()->exists()) {
    $method = new WithdrawMethod();
    $method->form_id = 0;
    $method->name = 'Bank Transfer';
    $method->image = '';
    $method->min_limit = 10;
    $method->max_limit = 100000;
    $method->fixed_charge = 0;
    $method->percent_charge = 0;
    $method->rate = 1;
    $method->currency = $currency;
    $method->description = 'Withdraw to your bank account. Processing may take 1–3 business days.';
    $method->status = Status::ENABLE;
    $method->save();

    echo "Created withdraw method: Bank Transfer ({$currency})\n";
} else {
    echo "Withdraw method already configured.\n";
}

echo "Done. Buyers can use /buyer/deposit and /buyer/withdraw\n";
