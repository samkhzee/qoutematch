@extends('admin.layouts.app')
@section('panel')


<div class="row justify-content-center">
    @if(request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method'))
        <div class="col-12">
            @include('admin.deposit.widget')
        </div>
    @endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Conversion')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($deposits as $deposit)
                            @php
                                $details = $deposit->detail ? json_encode($deposit->detail) : null;
                            @endphp
                            <tr>
                                <td>
                                    <div>
                                        <span class="fw-bold">
                                            <a href="{{ appendQuery('method',$deposit->method_code < 5000 ? @$deposit->gateway->alias : $deposit->method_code) }}">
                                                @if($deposit->method_code < 5000)
                                                    {{ __(@$deposit->gateway->name) }}
                                                @else
                                                    @lang('Google Pay')
                                                @endif
                                            </a>
                                        </span>
                                         <br>
                                         <small> {{ $deposit->trx }} </small>
                                    </div>
                                </td>

                                <td>
                                    {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                </td>
                                <td>
                                    <div>
                                        @if ($deposit->buyer)
                                            <span class="badge badge--primary">@lang('Buyer')</span>
                                            <span class="fw-bold d-block">{{ $deposit->buyer->fullname }}</span>
                                            <span class="small">
                                                <a href="{{ route('admin.buyers.detail', $deposit->buyer_id) }}"><span>@</span>{{ $deposit->buyer->username }}</a>
                                            </span>
                                        @elseif ($deposit->user)
                                            <span class="badge badge--info">@lang('Provider')</span>
                                            <span class="fw-bold d-block">{{ $deposit->user->fullname }}</span>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $deposit->user_id) }}"><span>@</span>{{ $deposit->user->username }}</a>
                                            </span>
                                            @if (!empty($deposit->detail->monetisation))
                                                <br><small class="text-muted">@lang('Lead credits')</small>
                                            @endif
                                        @else
                                            <span class="text-muted">@lang('N/A')</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ showAmount($deposit->amount) }} + <span class="text--danger" title="@lang('charge')">{{ showAmount($deposit->charge)}} </span>
                                         <br>
                                         <strong title="@lang('Amount with charge')">
                                         {{ showAmount($deposit->amount+$deposit->charge) }}
                                         </strong>
                                    </div>
                                </td>
                                <td>
                                    {{ showAmount(1) }} =  {{ showAmount($deposit->rate,currencyFormat:false) }} {{__($deposit->method_currency)}}
                                    <br>
                                    <strong>{{ showAmount($deposit->final_amount,currencyFormat:false) }} {{__($deposit->method_currency)}}</strong>
                                </td>
                                <td>
                                    @php echo $deposit->statusBadge @endphp
                                </td>
                                <td>
                                    <a href="{{ route('admin.deposit.details', $deposit->id) }}"
                                       class="btn btn-sm btn-outline--primary ms-1">
                                        <i class="la la-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if($deposits->hasPages())
            <div class="card-footer py-4">
                @php echo paginateLinks($deposits) @endphp
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Username / TRX' />
@endpush
