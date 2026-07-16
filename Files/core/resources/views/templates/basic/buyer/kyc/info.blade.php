@extends('Template::layouts.buyer_master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card custom--card">
                <div class="card-body">
                    @if ($user->kyc_data)
                        <ul class="list-group">
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ __($val->name) }}
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            <a
                                                href="{{ route('buyer.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"><i
                                                    class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                        @else
                                            <p>{{ __($val->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        @include('Template::partials.empty', ['message' => 'KYC data not found!'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
