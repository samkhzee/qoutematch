import { useForm } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';

export default function WithdrawMethods({ methods, storeUrl, currencySymbol, currencyText }) {
    const [selectedId, setSelectedId] = useState(methods[0]?.id ?? null);
    const [amount, setAmount] = useState('');
    const form = useForm({ method_code: selectedId, amount: '' });

    const selected = useMemo(
        () => methods.find((method) => method.id === selectedId) ?? methods[0],
        [methods, selectedId],
    );

    const calculation = useMemo(() => {
        const value = parseFloat(amount) || 0;
        if (!selected || !value) {
            return { charge: 0, receivable: 0, inCurrency: 0 };
        }
        const charge = selected.fixedCharge + (value * selected.percentCharge) / 100;
        const afterCharge = value - charge;
        const receivable = afterCharge > 0 ? afterCharge * selected.rate : 0;
        return { charge, receivable, inCurrency: receivable };
    }, [amount, selected]);

    useEffect(() => {
        if (selected) {
            form.setData('method_code', selected.id);
        }
    }, [selected]);

    const submit = (event) => {
        event.preventDefault();
        form.setData({ method_code: selected?.id, amount });
        form.post(storeUrl);
    };

    const canSubmit = selected && amount && parseFloat(amount) >= selected.minLimit && parseFloat(amount) <= selected.maxLimit;

    return (
        <form onSubmit={submit} className="withdraw-form">
            <div className="gateway-card">
                <div className="row justify-content-center gy-sm-4 gy-3">
                    <div className="col-xl-6">
                        <div className="payment-system-list is-scrollable gateway-option-list">
                            {methods.map((method) => (
                                <label key={method.id} className={`payment-item gateway-option ${selectedId === method.id ? 'active' : ''}`}>
                                    <div className="payment-item__info">
                                        <span className="payment-item__check" />
                                        <span className="payment-item__name">{method.name}</span>
                                    </div>
                                    <div className="payment-item__thumb">
                                        <img className="payment-item__thumb-img" src={method.image} alt="" />
                                    </div>
                                    <input
                                        type="radio"
                                        className="payment-item__radio gateway-input"
                                        name="method_code"
                                        value={method.id}
                                        checked={selectedId === method.id}
                                        onChange={() => setSelectedId(method.id)}
                                        hidden
                                    />
                                </label>
                            ))}
                        </div>
                    </div>
                    <div className="col-xl-6">
                        <div className="payment-system-list deposit-panel">
                            <p className="deposit-panel__eyebrow">Withdraw Summary</p>
                            <h5 className="deposit-panel__heading">How much would you like to withdraw?</h5>
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
                                    <strong>{selected ? `${selected.minLimitFormatted} - ${selected.maxLimitFormatted}` : '—'}</strong>
                                </li>
                                <li>
                                    <span>Processing Charge</span>
                                    <strong>{currencySymbol}{calculation.charge.toFixed(2)} {currencyText}</strong>
                                </li>
                            </ul>
                            <div className="deposit-panel__total">
                                <span className="deposit-panel__total-label">Receivable</span>
                                <strong className="deposit-panel__total-value">
                                    {currencySymbol}{calculation.receivable.toFixed(2)} {currencyText}
                                    {selected && (
                                        <small className="d-block text-muted">
                                            In {selected.currency}: {calculation.inCurrency.toFixed(2)}
                                        </small>
                                    )}
                                </strong>
                            </div>
                            <button type="submit" className="btn btn--base w-100 deposit-panel__submit" disabled={!canSubmit || form.processing}>
                                Confirm Withdraw
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    );
}
