@extends('Template::layouts.frontend')
@section('content')
    <section class="my-120 privacy-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @php
                        echo $policy->data_values->details;
                    @endphp
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .privacy-page h5 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .privacy-page ul,
        .privacy-page ol {
            padding-left: 20px;
        }

        .privacy-page ul,
        .privacy-page li {
            list-style: auto;
        }
    </style>
@endpush
