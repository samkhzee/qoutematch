@extends('Template::layouts.buyer_master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header d-flex justify-content-end">

            <form class="table-search">
                <input class="form--control" name="search" type="search" value="{{ request()->search }}"
                    placeholder="Search Here...">
                <button class="table-search-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Gateway | Transaction')</th>
                        <th class="text-center">@lang('Initiated')</th>
                        <th class="text-center">@lang('Amount')</th>
                        <th class="text-center">@lang('Conversion')</th>
                        <th class="text-center">@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdraws as $withdraw)
                        @php
                            $details = [];
                            foreach ($withdraw->withdraw_information as $key => $info) {
                                $details[] = $info;
                                if ($info->type == 'file') {
                                    $details[$key]->value = route(
                                        'buyer.download.attachment',
                                        encrypt(getFilePath('verify') . '/' . $info->value),
                                    );
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <div>
                                    <span class="fw-bold"><span class="text-primary">
                                            {{ __(@$withdraw->method->name) }}</span></span>
                                    <br>
                                    <small>{{ $withdraw->trx }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                {{ showDateTime($withdraw->created_at) }} <br>
                                {{ diffForHumans($withdraw->created_at) }}
                            </td>
                            <td class="text-center">
                                <div>
                                    {{ showAmount($withdraw->amount) }} - <span class="text--danger"
                                        data-bs-toggle="tooltip"
                                        title="@lang('Processing Charge')">{{ showAmount($withdraw->charge) }} </span>
                                    <br>
                                    <strong data-bs-toggle="tooltip" title="@lang('Amount after charge')">
                                        {{ showAmount($withdraw->amount - $withdraw->charge) }}
                                    </strong>
                                </div>

                            </td>
                            <td class="text-center">
                                <div>
                                    {{ showAmount(1) }} = {{ showAmount($withdraw->rate, currencyFormat: false) }}
                                    {{ __($withdraw->currency) }}
                                    <br>
                                    <strong>{{ showAmount($withdraw->final_amount, currencyFormat: false) }}
                                        {{ __($withdraw->currency) }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                @php echo $withdraw->statusBadge @endphp
                            </td>
                            <td>
                                <button class="view-btn detailBtn" data-user_data="{{ json_encode($details) }}"
                                    @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                    <i class="la la-desktop"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center msg-center">
                                <div class="empty-message text-center py-5">
                                    <img src="{{ asset(activeTemplate(true) . 'images/empty.png') }}" alt="empty">
                                    <h6 class="text-muted mt-3">@lang('No withdrawal history found.')</h6>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($withdraws->hasPages())
            <div class="table-wrapper-footer">
                {{ paginateLinks($withdraws) }}
            </div>
        @endif
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal custom--modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">@lang('Withdraw Details')</h6>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush userData"></ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    } else {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span"><a href="${element.value}"><i class="fa-regular fa-file"></i> @lang('Attachment')</a></span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })(jQuery);
    </script>
@endpush
