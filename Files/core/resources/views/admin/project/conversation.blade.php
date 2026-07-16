@extends('admin.layouts.app')
@section('panel')
    <div class="conversation-body">
        <div class="sidebar-overlay"></div>
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-xxl-3 col-lg-4">
                    <div class="message-left-bar custom-box">
                        <div class="buyer--wrapper">
                            <h5 class="custom-title">@lang('Buyer')</h5>
                            <div class="user-list">
                                <div class="buyer-image">
                                    <img src="{{ getImage(getFilePath('buyerProfile') . '/' . @$project->buyer->image, avatar: true) }}"
                                        alt="Buyer Image" class="custom-img">
                                </div>
                                <div class="buyer-details">
                                    <div class="detail-row">
                                        <span class="label">@lang('Name'):</span>
                                        <span class="value">{{ $project->buyer->fullname ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">@lang('Username'):</span>
                                        <a href="{{ route('admin.buyers.detail', $project->buyer_id) }}" class="value link">
                                            {{ '@' . $project->buyer->username ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="freelancer--wrapper">
                            <h5 class="custom-title">@lang('Freelancer')</h5>
                            <div class="user-list">
                                <div class="buyer-image">
                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . @$project->user->image, avatar: true) }}"
                                        alt="Freelancer Image" class="custom-img">
                                </div>
                                <div class="buyer-details">
                                    <div class="detail-row">
                                        <span class="label">@lang('Name'):</span>
                                        <span class="value">{{ $project->user->fullname ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">@lang('Username'):</span>
                                        <a href="{{ route('admin.users.detail', $project->user_id) }}" class="value link">
                                            {{ '@' . $project->user->username ?? 'N/A' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-9 col-lg-8">
                    <div class="message-middle-bar">
                        @if ($project->status == Status::PROJECT_REPORTED)
                            <div class="deal-action-page mb-3">
                                <p class="pb-2">
                                    @lang(':buyerName has reported this project for potential issues. Please resolve any conflicts and finalize the decision.', ['buyerName' => $project->buyer->fullname])
                                </p>
                                <div class="mb-2">
                                    <h6 class="text--danger">@lang('Report Reason')</h6>
                                    <p class="d-block">{{ __($project->report_reason) }}</p>
                                </div>
                                <button class="btn btn--success confirmationBtn" data-question="@lang('Are you sure to complete or resolve this project?')"
                                    data-action="{{ route('admin.project.complete', $project->id) }}">
                                    @lang('Complete')
                                </button>
                                <button class="btn btn--danger ms-2 confirmationBtn" data-question="@lang('Are you sure to reject this project?')"
                                    data-action="{{ route('admin.project.reject', $project->id) }}">
                                    @lang('Reject')
                                </button>
                                <button class="btn btn--primary ms-2 partialBtn" data-bs-toggle="modal"
                                    data-bs-target="#partialModal">
                                    <i class="las la-check-circle"></i> @lang('Partial Complete')
                                </button>
                            </div>
                        @endif


                        <div class="main-message-box" id="messageContainer">
                            @foreach ($messages as $message)
                                @php
                                    $styleClass = '';
                                    $profileImage = '';

                                    if ($message->admin_id) {
                                        $styleClass = 'escrow-message box-right';
                                        $profileImage = getImage(
                                            getFilePath('adminProfile') . '/' . @$message->admin->image,
                                            avatar: true,
                                        );
                                    } elseif ($message->buyer_id == auth()->guard('buyer')->id()) {
                                        $styleClass = 'message-buyer box-left';
                                        $profileImage = getImage(
                                            getFilePath('buyerProfile') . '/' . @$message->buyer->image,
                                            avatar: true,
                                        );
                                    } elseif ($message->user_id) {
                                        $styleClass = 'message-user box-left';
                                        $profileImage = getImage(
                                            getFilePath('userProfile') . '/' . @$message->user->image,
                                            avatar: true,
                                        );
                                    }
                                @endphp

                                <div class="message-box {{ $styleClass }}">
                                    <span class="message-box__icon">
                                        <img src="{{ $profileImage }}" alt="Profile Image"
                                            @if ($styleClass == 'escrow-message box-right') title="@lang('Escrow')"
                                            @elseif ($styleClass == 'message-buyer box-left') 
                                                title="@lang('Buyer')"
                                            @else 
                                                title="@lang('Freelancer')" @endif>
                                    </span>
                                    <div class="message-box__text">
                                        {{ nl2br(e($message->message)) }}
                                        @if ($message->files)
                                            <p class="{{ $message->action == Status::YES ? 'action-message-box' : '' }}">
                                                @php
                                                    $files = is_array($message->files)
                                                        ? $message->files
                                                        : json_decode($message->files, true);
                                                    $files = is_array($files) ? $files : [];
                                                @endphp
                                                @foreach ($files as $file)
                                                    <a href="{{ route('buyer.download.attachment', encrypt(getFilePath('message') . '/' . $file)) }}"
                                                        target="_blank">
                                                        <i class="las la-file"></i> {{ basename($file) }}
                                                    </a>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Message Form --}}
                        <form class="chat-box" id="messageForm" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="files-here d-none">
                                <span>@lang(' Selected') <b></b> @lang('Files')<i
                                        class="las la-times removeFile"></i></span>
                            </div>

                            <textarea class="form--control form-control" name="message" cols="30" rows="10"
                                placeholder="@lang('Enter your message')"></textarea>
                            <div class="chat-box__icon">
                                <label data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="@lang('Supported Files: .jpg, .jpeg, .png, .pdf, .docx')"
                                    for="file">
                                    <i class="las la-paperclip"></i>
                                </label>
                                <input class="messageFileUpload" id="file" name="message_files[]" type="file" hidden
                                    accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" multiple>
                                <button class="chat-box__icon-btn" type="submit">
                                    <i class="las la-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="partialModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Partial Completed')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div> 
                <form action="{{ route('admin.project.partial.complete', $project->id) }}" class="disableSubmission"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Freelancer Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="freelancer_amount" class="form-control"
                                    placeholder="@lang('Please provide freelancer percentage')" max="100" min="0">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Buyer Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="buyer_amount"
                                    placeholder="@lang('Please provide buyer percentage')" class="form-control">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Reason')</label>
                            <textarea class="form-control" placeholder="@lang('Reason')" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function scrollToBottom() {
                const messageContainer = $('#messageContainer');
                if (messageContainer.length) {
                    messageContainer.scrollTop(messageContainer[0].scrollHeight);
                }
            }


            setTimeout(scrollToBottom, 500);


            const observer = new MutationObserver(scrollToBottom);
            observer.observe(document.getElementById('messageContainer'), {
                childList: true,
                subtree: true
            });

            // Message submission
            function messageSubmit() {
                const url = `{{ route('admin.project.conversation.store', $id) }}`;
                const formData = new FormData($('#messageForm')[0]);

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#messageForm')[0].reset();
                            $('.files-here').addClass('d-none');
                            $('.chat-box').removeClass('add-file');
                            scrollToBottom();
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function(response) {
                        notify('error', response.responseText);
                    }
                });
            }

            // Form submission event handlers
            $('#messageForm').on('submit', function(e) {
                e.preventDefault();
                messageSubmit();
            }).on('keypress', function(e) {
                if (e.which == 13 && !e.shiftKey) {
                    e.preventDefault();
                    messageSubmit();
                }
            });

            // File upload handling
            $(".messageFileUpload").on('change', function() {
                if (this.files && this.files.length > 0) {
                    $('.files-here').removeClass('d-none');
                    $('.chat-box').addClass('add-file');
                    $('.files-here span b').text(this.files.length);
                }
            });

            $(".removeFile").on('click', function() {
                $(".chat-box").find('input[type=file]').val('');
                $('.files-here').addClass('d-none');
                $('.chat-box').removeClass('add-file');
            });

            // Pusher Configuration
            Pusher.logToConsole = true;
            const pusher = new Pusher("{{ gs()->pusher_config->app_key }}", {
                cluster: "{{ gs()->pusher_config->cluster }}",
            });

            // Pusher connection and message handling
            const pusherConnection = (eventName, callback) => {
                pusher.connection.bind('connected', () => {
                    const SOCKET_ID = pusher.connection.socket_id;
                    const BASE_URL = "{{ route('home') }}";
                    const CHANNEL_NAME = `private-${eventName}`;
                    const url = `${BASE_URL}/pusher/auth/${SOCKET_ID}/${CHANNEL_NAME}`;
                    pusher.config.authEndpoint = url;

                    const channel = pusher.subscribe(CHANNEL_NAME);
                    channel.bind('pusher:subscription_succeeded', function() {
                        channel.bind(eventName, callback);
                    });
                });
            };

            // Live chat handler
            function liveChat(data) {
                const styleClass = data.buyerId == {{ @$conversation->buyer_id ?? 0 }} ?
                    'message-buyer box-left' :
                    (data.userId == {{ @$conversation->user_id ?? 0 }} ?
                        'message-user box-left' :
                        'escrow-message box-right');

                const fileLinks = data.files && data.files.length ?
                    data.files.map(file =>
                        `<a href="{{ asset(getFilePath('message')) }}/${file}" download>
                        <i class="las la-file"></i>${file}
                    </a>`
                    ).join('') :
                    '';

                const messageHtml = `
                <div class="message-box ${styleClass}">
                    <span class="message-box__icon">
                        <img src="${data.userImage}" alt="profile">
                    </span>
                    <div class="message-box__text ${data.action ? 'action-message-box' : ''}">
                        ${fileLinks ? `<p>${fileLinks}</p>` : ''}
                        ${data.message ? data.message.replace(/\n/g, '<br>') : ''}
                    </div>
                </div>
            `;

                $('#messageContainer').append(messageHtml);
                scrollToBottom();
            }

            // Initialize Pusher connection
            pusherConnection('conversation_{{ @$id ?? 0 }}', liveChat);

            const total = 100;

            $('input[name="freelancer_amount"]').on('input', function() {
                let freelancer = parseFloat($(this).val());

                if (!isNaN(freelancer) && freelancer <= total) {
                    let buyer = total - freelancer;
                    $('input[name="buyer_amount"]')
                        .val(buyer)
                        .prop('readonly', true);
                } else {
                    $('input[name="buyer_amount"]')
                        .val('')
                        .prop('readonly', false);
                }
            });

            $('input[name="buyer_amount"]').on('input', function() {
                let buyer = parseFloat($(this).val());

                if (!isNaN(buyer) && buyer <= total) {
                    let freelancer = total - buyer;
                    $('input[name="freelancer_amount"]')
                        .val(freelancer)
                        .prop('readonly', true);
                } else {
                    $('input[name="freelancer_amount"]')
                        .val('')
                        .prop('readonly', false);
                }
            });
        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .message-single-profile {
            position: relative;
        }

        .message-single-profile a {
            display: flex;
            gap: 10px;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 8px;
            transition: .4s;
            border-bottom: 1px dotted #ddd;
        }

        .message-single-profile a:last-child {
            border-bottom: none;
        }

        .message-left-bar {
            overflow-y: scroll;
            height: 100vh;
            padding: 30px 20px;
            position: relative;
            padding-bottom: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        @media (max-width:991px) {
            .message-left-bar {
                width: 320px;
                position: fixed;
                height: 100vh;
                opacity: 0;
                top: 0;
                left: 0;
                visibility: hidden;
                transform: translateX(-100%);
                transition: .5s linear;
                padding: 80px 20px;
                padding-bottom: 0;
                overflow-y: scroll;
                z-index: 9991;
                background-color: #fff;
            }

            .message-left-bar.show {
                opacity: 1;
                visibility: visible;
                transform: translateX(0);
            }
        }

        .message-single-profile a.active-message {
            background: #f0f0f0;
            border-left: 3px solid #28c76f;
            border-bottom: none;
        }

        .message-single-profile a:hover {
            background: #f0f0f0;
        }

        .message-single-profile a img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .message-single-profile a b {
            color: #4f4f4f;
            font-weight: 500;
        }

        .message-single-profile a p {
            color: #6c6c6c;
            padding: 4px 0;
        }

        .message-single-profile a span {
            color: #9b9b9b;
            font-size: 14px;
        }

        .escrow-user {
            position: sticky;
            bottom: 0;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 10px;
            gap: 20px;
            border-radius: 4px;
            margin-bottom: 2px;
            border: 1px solid #0000;
            margin-top: auto;
        }

        .escrow-user p {
            color: #ddd;
        }

        .escrow-user b {
            color: #ddd;
        }

        .escrow-user span {
            font-size: 14px;
            color: hsl(var(--base) / .5);
        }

        .escrow-user img {
            max-width: 40px;
            max-height: 40px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .escrow-user.active-message {
            background: #ddd;
        }

        .message-box {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        @media (max-width:575px) {
            .message-box {
                gap: 15px;
            }
        }

        .message-box.box-right .message-box__icon {
            order: 1;
        }

        .main-message-box:last-child {
            margin-bottom: 0;
        }

        .message-middle-bar {
            padding: 30px;
            height: 100vh;
            padding-bottom: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        @media (max-width:575px) {
            .message-middle-bar {
                padding: 60px 10px;
                padding-bottom: 0;
            }
        }

        .message-box__icon {
            height: 40px;
            width: 40px;
        }

        .message-box__icon img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .message-box__text {
            background: #cff7dafc;
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 15px;
            position: relative;
            z-index: 1;
            color: #686868;
            max-width: 85%;
        }

        .message-box.box-right .message-box__text span {
            justify-content: flex-end;
        }

        .message-box.box-right .message-box__text {
            text-align: right;
        }

        .message-box__text::before {
            position: absolute;
            content: "";
            top: 50%;
            left: -8px;
            transform: translateY(-50%) rotate(45deg);
            width: 20px;
            height: 20px;
            background: #cff7dafc;
            z-index: -1;
        }

        .message-box.box-right .message-box__text {
            margin-left: auto;
            text-align: right;
            background: #ebbe95;
        }


        .message-box.box-right .message-box__text::before {
            right: -9px;
            left: unset;
            background: #ebbe95;
        }

        .message-box.scrow-message .message-box__text {
            background-color: #a5daf1;
        }

        .message-box.scrow-message .message-box__text::before {
            background-color: #a5daf1;
        }

        .chat-box {
            position: sticky;
            bottom: 0;
            width: 100%;
            background: #fff;
            z-index: 3;
            display: flex;
            gap: 20px;
            border: 1px solid #ececec;
            border-radius: 10px;
            margin-bottom: 2px;
            margin-top: auto;
        }

        .chat-box .form--control {
            height: 70px;
            resize: none !important;
            width: calc(100% - 100px);
            border: 0 !important;
            box-shadow: none;
        }

        .chat-box__icon {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 24px;
            z-index: 9;
            width: 100px;
        }

        .chat-box__icon-btn {
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #28c76f;
            display: flex;
            justify-content: center;
            align-items: center;
        }




        .message-user-profile__thumb {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .message-user-profile__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }



        .user-name {
            display: block;
            margin-top: 10px;
            color: #ffff;
        }

        .message-user-profile {
            text-align: center;
        }

        .left-sidebar__filter {
            background: #282828;
            font-size: 24px;
            color: #fff;
            border-radius: 4px;
            width: 45px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9;
            cursor: pointer;
            margin-bottom: 25px;
        }



        .chat-box__icon label {
            cursor: pointer;
        }

        .chat-box__icon i {
            font-size: 20px;
        }

        .chat-box__icon label {
            margin-bottom: 0 !important;
        }

        .dual-users {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .dual-users a {
            color: #4f4f4f;
            padding: 10px 15px;
            border: 1px solid #4f4f4f;
            border-radius: 4px;
        }

        .dual-users a.user-active {
            color: #fff;
            border-color: #28c76f;
            background: #28c76f;
        }

        .deal-listing-details {
            margin-top: 25px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 3px;
        }

        .sidebar-overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            content: "";
            left: 0;
            top: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9991;
            transition: 0.2s linear;
            visibility: hidden;
            opacity: 0;
        }

        .sidebar-overlay.show {
            visibility: visible;
            opacity: 1;
            z-index: 991;
        }

        .message-box__text span {
            display: flex;
            gap: 20px;
            padding-bottom: 10px;
            flex-wrap: wrap;
        }

        .chat-box .files-here {
            position: absolute;
            top: 8px;
            left: 15px;
        }

        .add-file {
            padding-top: 40px;
        }

        .chat-box .files-here span {
            background: #6c6c6c;
            color: #fff;
            padding: 2px 10px;
            border-radius: 5px;
            font-size: 14px;
            position: relative;
            padding-right: 35px;
            overflow: hidden !important;
        }

        .chat-box .files-here span i {
            cursor: pointer;
            background-color: red;
            color: #fff;
            position: absolute;
            height: 100%;
            right: 0;
            padding: 0 5px;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .files-here span b {
            font-weight: 500;
        }

        .deal-info-top {
            border: 1px solid #6c6c6c;
            border-radius: 10px;
            padding: 20px;
            background: #ffffff;
        }

        .deal-info-top .escrow-step {
            background: hsla(228, 18.5%, 10.6%, 0.89);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .deal-info-top {
            margin-bottom: 20px;
        }

        .already-paid-page {
            margin-bottom: 20px;
            border: 1px solid #ff00008f;
            padding: 15px 20px;
            border-radius: 10px;
            background: #ff00008f;
        }

        .already-paid-page span {
            color: #ffffff;
        }

        .message-box__text.action-message-box {
            color: rgb(255, 114, 0);
        }

        .deal-action-page {
            border: 1px solid #ff8510;
            padding: 10px;
            border-radius: 10px;
            position: sticky;
            top: -8px;
            background: #ffffff;
            z-index: 9;


            /* General Styles */
            .custom-box {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                background-color: #f9f9f9;
                max-width: 350px;
                /* Adjust as needed */
                margin: 0 auto;
                font-family: Arial, sans-serif;
            }

            .custom-title {
                font-size: 1.2rem;
                margin-bottom: 15px;
                color: #333;
                text-align: center;
            }

            .buyer-image {
                text-align: center;
                margin-bottom: 15px;
            }

            .custom-img {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                border: 2px solid #ccc;
            }

            .buyer-details {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .detail-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px 0;
            }

            .label {
                font-weight: bold;
                color: #555;
                flex: 1;
            }

            .value {
                color: #333;
                flex: 2;
                text-align: right;
            }

            .value.link {
                color: #007bff;
                text-decoration: none;
            }

            .value.link:hover {
                text-decoration: underline;
            }

        }

        .main-message-box {
            min-height: 426px;
            overflow-y: auto;
        }

        .message-box__text a {
            display: flex;
            align-items: flex-start;
            line-height: 1;
            gap: 5px;
            margin-top: 8px;
        }

        .message-box__text a i {
            line-height: 1;
        }

        .buyer-image {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
        }

        .buyer-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-list {
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f3ececbd;
            padding-bottom: 10px;
        }

        .custom-title {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #d9d3d3;
        }

        .detail-row .label {
            color: hsl(var(--black));
            font-weight: 500;
            font-size: 14px;
        }

        .freelancer--wrapper {
            margin-top: 60px;
        }

        .detail-row .value {
            font-size: 14px;
        }

        .bodywrapper__inner {
            position: relative;
        }

        .sidebar-close-icon {
            position: absolute;
            color: #000;
            top: 20px;
            right: 20px;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ddd;
            border-radius: 50%;
        }

        .sidebar-close-icon:hover {
            color: red;
        }

        .message-buyer.box-left .message-box__text {
            background: #c3c4ff !important;
        }

        .message-buyer.box-left .message-box__text::before {
            background: #c3c4ff !important;
        }
    </style>
@endpush
