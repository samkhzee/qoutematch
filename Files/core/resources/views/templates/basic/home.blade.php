@extends('Template::layouts.frontend')
@section('content')
    @include('Template::partials.banner')

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include('Template::sections.' . $sec)
        @endforeach
    @endif

    @php
        $sellerGoogleEnabled = optional(gs('socialite_credentials')->google)->status == Status::ENABLE;
        $buyerGoogleEnabled = optional(gs('socialite_buyer_credentials')->google)->status == Status::ENABLE;
        $anyGoogleEnabled = $sellerGoogleEnabled || $buyerGoogleEnabled;

        $anyLoggedIn = auth()->check() || auth()->guard('buyer')->check();
    @endphp
    @if (!$anyLoggedIn && $anyGoogleEnabled)
        <div id="g_id_onload" data-size="small" data-client_id="{{ @gs('socialite_credentials')->google->client_id }}"
            data-callback="googleLoginResponse" data-auto_prompt="true" data-use_fedcm_for_prompt="false"> </div>


        <div class="modal fade" id="typeModalCenter" tabindex="-1" role="dialog" aria-labelledby="typeModalCenterTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="typeModalLongTitle">@lang('Continue as')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="d-flex gap-3 justify-content-between flex-wrap">
                            <label
                                class="type-option flex-fill p-3 border rounded-3 d-flex align-items-center justify-content-center text-center cursor-pointer">
                                <input class="form-check-input me-2" type="radio" name="userType" id="apply-freelancer"
                                    value="freelancer" hidden>
                                <div>
                                    <i class="las la-briefcase fs-3 text--base"></i>
                                    <div class="fw-semibold mt-2">@lang('Continue as a Freelancer')</div>
                                </div>
                            </label>

                            <label
                                class="type-option flex-fill p-3 border rounded-3 d-flex align-items-center justify-content-center text-center cursor-pointer">
                                <input class="form-check-input me-2" type="radio" name="userType" id="apply-buyer"
                                    value="buyer" hidden>
                                <div>
                                    <i class="las la-shopping-cart fs-3 text--base"></i>
                                    <div class="fw-semibold mt-2">@lang('Continue as a Buyer')</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="button" id="continueLogin"
                            class="btn btn--base d-flex align-items-center justify-content-center gap-2">
                            <span class="btn-text">@lang('Continue')</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('style')
    <style>
        .empty-message {
            background: unset;
            border: 1px solid hsl(var(--white));
        }

        .type-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .type-option:hover {
            background-color: hsl(var(--white)) !important;
            border-color: hsl(var(--base)) !important;
        }

        .type-option.active {
            border: 2px solid hsl(var(--base)) !important;
            background-color: hsl(var(--white)) !important;
        }
    </style>
@endpush


@pushIf($anyGoogleEnabled, 'script')
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    let googleToken = null;

    function googleLoginResponse(response) {
        googleToken = response.credential;
        $('#typeModalCenter').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const continueBtn = document.getElementById('continueLogin');
        const originalBtnHtml = continueBtn.innerHTML;
        const options = document.querySelectorAll('.type-option');

        const preChecked = document.querySelector('input[name="userType"]:checked');
        if (preChecked) {
            const parent = preChecked.closest('.type-option');
            if (parent) parent.classList.add('active');
        }

        options.forEach(option => {
            option.addEventListener('click', (e) => {
                const input = option.querySelector('input[name="userType"]');
                if (input) {
                    input.checked = true;
                }

                options.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
            });

            const input = option.querySelector('input[name="userType"]');
            if (input) {
                input.addEventListener('change', () => {
                    if (input.checked) {
                        options.forEach(opt => opt.classList.remove('active'));
                        option.classList.add('active');
                    }
                });
            }
        });

        continueBtn.addEventListener('click', function() {
            const selectedInput = document.querySelector('input[name="userType"]:checked');
            const type = selectedInput ? selectedInput.value : null;

            if (!type) {
                notify('error', 'Please select an account type');
                return;
            }

            if (!googleToken) {
                notify('error', 'Google token missing, please try again');
                return;
            }

            const routeTemplate = "{{ route('login.google', ['type' => ':type']) }}";
            const routeUrl = routeTemplate.replace(':type', type);

            continueBtn.disabled = true;
            continueBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Loading...`;

            fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        token: googleToken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = data.data.redirect_url ?? '/';
                    } else {
                        notify('error', 'Login error');
                        console.error(data);
                    }
                })
                .catch(err => {
                    console.error(err);
                    notify('error', 'An error occurred during login');
                })
                .finally(() => {
                    setTimeout(() => {
                        continueBtn.disabled = false;
                        continueBtn.innerHTML = originalBtnHtml;
                    }, 300);
                });
        });
    });
</script>
@endPushIf
