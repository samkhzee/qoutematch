@extends('Template::layouts.buyer_master')
@section('content')
    <div class="table-wrapper">
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
            @include('Template::buyer.job.job_list')
            @if ($jobs->hasPages())
                <div class="dashboard-table__bottom">
                    {{ paginateLinks($jobs) }}
                </div>
            @endif
        </div>
    </div>


    <div class="modal custom--modal" id="detailModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush jobDetailsContent">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $(".detailBtn").on("click", function() {
                let modal = $('#detailModal');
                let detailsList = $(".jobDetailsContent");
                detailsList.html('');
                $.each(this.dataset, function(key, value) {
                    let formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, char => char
                        .toUpperCase());

                    detailsList.append(
                        `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <span> <strong>${formattedKey}</strong></span>
                                <span>${value}</span>
                            </li>`
                    );
                });
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
