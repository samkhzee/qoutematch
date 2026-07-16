@extends('Template::layouts.app')
@section('panel')
    <div class="maintenance-page">
        <div class="container">
            <div class="maintenance-content">
                <img class="maintenance-image"
                    src="{{ getImage(getFilePath('maintenance') . '/' . @$maintenance->data_values->image, getFileSize('maintenance')) }}"
                    alt="@lang('image')">
                <h4 class="text--danger maintenance-text">{{ __(@$maintenance->data_values->heading) }}</h4>
                <p class="text-white">@php echo @$maintenance->data_values->description @endphp</p>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        body {
            display: flex;
            align-items: center;
            height: 100vh;
            justify-content: center;
        }

        .maintenance-content {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .maintenance-image {
            max-width: 500px;
            width: 100%;
            margin: 0 auto 24px;
        }

        .maintenance-text {
            margin: 0;
            margin-bottom: 12px;
        }
    </style>
@endpush
