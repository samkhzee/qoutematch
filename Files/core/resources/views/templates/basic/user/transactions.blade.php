@extends('Template::layouts.master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header gap-3">
            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn--base showFilterBtn btn--sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="responsive-filter-card my-4">
                <form>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <input type="search" name="search" value="{{ request()->search }}"
                                placeholder="@lang('Transaction Number')" class="form-control form--control">
                        </div>
                        <div class="flex-grow-1 select2-parent">
                            <select name="trx_type" class="form-select form-control form--control select2"
                                data-minimum-results-for-search="-1">
                                <option value="">@lang('All Type')</option>
                                <option value="+" @selected(request()->trx_type == '+')>
                                    @lang('Plus')</option>
                                <option value="-" @selected(request()->trx_type == '-')>
                                    @lang('Minus')</option>
                            </select>
                        </div>
                        <div class="flex-grow-1 select2-parent">

                            <select class="form-select form-control form--control select2"
                                data-minimum-results-for-search="-1" name="remark">
                                <option value="">@lang('All Remark')</option>
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                        {{ __(keyToTitle($remark->remark)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-grow-1 align-self-end mb-1">
                            <button class="btn btn--base w-100 h-45"><i class="las la-filter"></i>
                                @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="dashboard-table">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Trx')</th>
                        <th>@lang('Transacted')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Post Balance')</th>
                        <th>@lang('Detail')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td><strong>{{ $trx->trx }}</strong></td>
                            <td> {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}</td>
                            <td>
                                <span
                                    class="fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                    {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                </span>
                            </td>
                            <td>{{ showAmount($trx->post_balance) }}</td>
                            <td><span class="clamping">{{ __($trx->details) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-muted text-center msg-center" colspan="100%">
                                @include('Template::partials.empty', [
                                    'message' => 'Transaction not found!',
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="card-footer">
                {{ paginateLinks($transactions) }}
            </div>
        @endif
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush
