@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Post Jobs')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buyers as $buyer)
                                    <tr>
                                        <td>
                                            <div>
                                                <span class="fw-bold">{{ $buyer->fullname }}</span>
                                                <br>
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.buyers.detail', $buyer->id) }}"><span>@</span>{{ $buyer->username }}</a>
                                                </span>
                                            </div>
                                        </td>
                                       
                                        <td>
                                            {{ $buyer->email }}<br>{{ $buyer->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"
                                                title="{{ @$buyer->country_name }}">{{ $buyer->country_code }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ showDateTime($buyer->created_at) }} <br>
                                                {{ diffForHumans($buyer->created_at) }}
                                            </div>
                                        </td>
                                        <td>
                                            <a class="badge badge--primary" href="#">{{ $buyer->jobs_count }}</a>
                                        </td>
                                        <td>
                                            <span class="fw-bold">

                                                {{ showAmount($buyer->balance) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.buyers.detail', $buyer->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.buyers.kyc.pending'))
                                                    <a href="{{ route('admin.buyers.kyc.details', $buyer->id) }}"
                                                        target="__blank" class="btn btn-sm btn-outline--dark">
                                                        <i class="las la-user-check"></i>@lang('KYC Data')
                                                    </a>
                                                @endif
                                            </div>
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
                @if ($buyers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($buyers) }}
                    </div>
                @endif
            </div>
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
