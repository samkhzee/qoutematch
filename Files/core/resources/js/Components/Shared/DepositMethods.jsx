import { useForm } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import RequestFormFields from '@/Components/Jobs/RequestFormFields';

export default function DepositMethods({ gateways, storeUrl, currencySymbol, currencyText }) {
    const [selectedIndex, setSelectedIndex] = useState(0);
    const [amount, setAmount] = useState('');
    const form = useForm({ gateway: '', currency: '', amount: '' });

    const selected = gateways[selectedIndex] ?? gateways[0];

    const calculation = useMemo(() => {
        const value = parseFloat(amount) || 0;
        if (!selected || !value) {
            return { charge: 0, payable: 0 };
        }
        const charge = selected.fixedCharge + (value * selected.percentCharge) / 100;
        return { charge, payable: value + charge };
    }, [amount, selected]);

    useEffect(() => {
        if (selected) {
            form.setData({ gateway: selected.methodCode, currency: selected.currency });
        }
    }, [selected]);

    const submit = (event) => {
        event.preventDefault();
        form.setData({ gateway: selected?.methodCode, currency: selected?.currency, amount });
        form.post(storeUrl);
    };

    if (!gateways.length) {
        return (
            <div className="alert alert-warning mb-0">
                No deposit methods are configured yet. Ask admin to add a payment gateway.
            </div>
        );
    }

    const canSubmit = selected && amount && parseFloat(amount) >= selected.minAmount && parseFloat(amount) <= selected.maxAmount;

    return (
        <form onSubmit={submit} className="deposit-form">
            <input type="hidden" name="currency" value={form.data.currency} />
            <div className="gateway-card">
                <div className="row justify-content-center gy-sm-4 gy-3">
                    <div className="col-xl-6">
                        <div className="payment-system-list is-scrollable gateway-option-list">
                            {gateways.map((gateway, index) => (
                                <label key={`${gateway.methodCode}-${gateway.currency}`} className={`payment-item gateway-option ${selectedIndex === index ? 'active' : ''}`}>
                                    <div className="payment-item__info">
                                        <span className="payment-item__check" />
                                        <span className="payment-item__name">{gateway.name}</span>
                                    </div>
                                    <div className="payment-item__thumb">
                                        <img className="payment-item__thumb-img" src={gateway.image} alt="" />
                                    </div>
                                    <input
                                        type="radio"
                                        className="payment-item__radio gateway-input"
                                        checked={selectedIndex === index}
                                        onChange={() => setSelectedIndex(index)}
                                        hidden
                                    />
                                </label>
                            ))}
                        </div>
                    </div>
                    <div className="col-xl-6">
                        <div className="payment-system-list deposit-panel">
                            <p className="deposit-panel__eyebrow">Deposit Summary</p>
                            <h5 className="deposit-panel__heading">How much would you like to add?</h5>
                            <label className="deposit-panel__label">Amount</label>
                            <div className="deposit-amount-field">
                                <span className="deposit-amount-field__prefix">{currencySymbol}</span>
                                <input
                                    className="deposit-amount-field__input amount form--control"
                                    type="text"
                                    inputMode="decimal"
                                    value={amount}
                                    onChange={(e) => setAmount(e.target.value)}
                                    placeholder="0.00"
                                />
                                <span className="deposit-amount-field__suffix">{currencyText}</span>
                            </div>
                            <ul className="deposit-panel__meta">
                                <li>
                                    <span>Limit</span>
                                    <strong>{selected ? `${selected.minAmountFormatted} - ${selected.maxAmountFormatted}` : '—'}</strong>
                                </li>
                                <li>
                                    <span>Processing Charge</span>
                                    <strong>{currencySymbol}{calculation.charge.toFixed(2)} {currencyText}</strong>
                                </li>
                            </ul>
                            <div className="deposit-panel__total">
                                <span className="deposit-panel__total-label">Total Payable</span>
                                <strong className="deposit-panel__total-value">
                                    {currencySymbol}{calculation.payable.toFixed(2)} {currencyText}
                                </strong>
                            </div>
                            <button type="submit" className="btn btn--base w-100 deposit-panel__submit" disabled={!canSubmit || form.processing}>
                                Confirm Deposit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    );
}

export function ManualPaymentConfirm({ payment }) {
    const [fieldValues, setFieldValues] = useState({});
    const form = useForm({});

    const submit = (event) => {
        event.preventDefault();
        const data = new FormData();
        Object.entries(fieldValues).forEach(([key, value]) => {
            if (value instanceof File) {
                data.append(key, value);
            } else if (Array.isArray(value)) {
                value.forEach((item) => data.append(`${key}[]`, item));
            } else if (value !== null && value !== undefined) {
                data.append(key, value);
            }
        });
        form.transform(() => Object.fromEntries(data.entries()));
        form.post(payment.submitUrl, { forceFormData: true });
    };

    return (
        <div className="card custom--card">
            <div className="card-body">
                <div className="alert alert-primary">
                    You are requesting <b>{payment.amount}</b>{' '}
                    {payment.isProviderMonetisation ? 'to purchase lead credits.' : 'to deposit.'}{' '}
                    Please pay <b>{payment.finalAmount}</b> for successful payment.
                </div>
                {payment.description && (
                    <div className="mb-3" dangerouslySetInnerHTML={{ __html: payment.description }} />
                )}
                <form onSubmit={submit} encType="multipart/form-data">
                    <RequestFormFields
                        fields={payment.fields}
                        values={fieldValues}
                        onChange={(label, value) => setFieldValues((prev) => ({ ...prev, [label]: value }))}
                    />
                    <button type="submit" className="btn btn--base w-100 mt-3" disabled={form.processing}>
                        Pay Now
                    </button>
                </form>
            </div>
        </div>
    );
}
