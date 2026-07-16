@extends('Template::layouts.master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h6 class="mb-1">@lang('Your submitted quotes')</h6>
                <p class="text-muted mb-0 small">@lang('To place a new bid, browse open customer requests first.')</p>
            </div>
            <a href="{{ route('freelance.jobs') }}" class="btn btn--base">
                <i class="las la-search"></i> @lang('Browse Requests & Bid')
            </a>
        </div>
        <div class="table-wrapper-header d-flex justify-content-end">
            <form class="table-search">
                <input class="form-control form--control" name="search" type="search" value="{{ request()->search }}"
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
        <div class="dashboard-table">
            @include('Template::user.bid.bid_list')
        </div>
        @if ($bids->hasPages())
            <div class="table-wrapper-footer">
                {{ paginateLinks($bids) }}
            </div>
        @endif
    </div>


    <div class="modal custom--modal" id="moreModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Quote')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="las la-quote-left"></i>
                    <p class="fw-bold bid-quote"></p><i class="las la-quote-right"></i></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <div id="withdrawModal" class="modal custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="question"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            let curText = `{{ gs('cur_text') }}`;

            $(".moreModalBtn").on('click', function() {
                let moreModal = $("#moreModal");
                $('.job-title').text($(this).data('title'));
                $('.freelancer-name').text($(this).data('freelancer'));
                $('.bid-quote').text($(this).data('quote'));
                moreModal.modal("show");
            });

            $('.withdrawModalBtn').on('click', function() {
                var modal = $('#withdrawModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            })

        })(jQuery);
    </script>
@endpush
