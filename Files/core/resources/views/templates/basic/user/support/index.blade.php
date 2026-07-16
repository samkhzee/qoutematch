@extends('Template::layouts.master')
@section('content')
    <div class="table-wrapper">
        <div class="table-wrapper-header d-flex justify-content-end">
            <a class="btn ticket--btn" href="{{ route('ticket.open') }}">
                <i class="far fa-list-alt"></i>
                @lang('New Support')
            </a>
        </div>
        <div class="dashboard-table">
            <table class="table table--responsive--md">
                <thead>
                    <tr>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Priority')</th>
                        <th>@lang('Last Reply')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supports as $support)
                        <tr>
                            <td> <a class="fw-bold" href="{{ route('ticket.view', $support->ticket) }}">
                                    [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                            <td>
                                @php echo $support->statusBadge; @endphp
                            </td>
                            <td>
                                @php echo $support->priorityBadge; @endphp
                            </td>
                            <td>{{ diffForHumans($support->last_reply) }} </td>
                            <td>
                                <a class="view-btn" href="{{ route('ticket.view', $support->ticket) }}">
                                    <i class="las la-desktop"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center msg-center">
                                @include('Template::partials.empty', [
                                    'message' => 'Support ticket not found!',
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($supports->hasPages())
            <div class="table-wrapper-footer">
                {{ paginateLinks($supports) }}
            </div>
        @endif
    </div>
@endsection
