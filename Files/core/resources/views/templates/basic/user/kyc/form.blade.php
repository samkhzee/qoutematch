@extends('Template::layouts.master')
@section('content')
    <div class="container-fluid ">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card custom--card">
                    <div class="card-body">
                        @if (!$form || empty($form->form_data))
                            <div class="alert alert-warning mb-0">
                                @lang('KYC verification is not configured yet. Please contact support or try again later.')
                            </div>
                        @else
                            <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <x-viser-form identifier="act" identifierValue="kyc" />

                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/global/css/select2.min.css') }}" rel="stylesheet">
@endpush
