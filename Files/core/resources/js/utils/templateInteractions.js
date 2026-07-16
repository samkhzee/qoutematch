/**
 * Restores legacy jQuery template behaviours on Inertia / bridged pages.
 * Blade @push('script') blocks are omitted when inertiaBridge renders panel-only HTML.
 */

export function patchBootstrapModalBridge() {
    if (typeof window.jQuery === 'undefined' || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return;
    }

    const $ = window.jQuery;

    if ($.fn.modal?.__bs5Bridge) {
        return;
    }

    $.fn.modal = function modalBridge(action) {
        return this.each(function handleModal() {
            const instance = bootstrap.Modal.getOrCreateInstance(this);

            if (action === 'show') {
                instance.show();
            } else if (action === 'hide') {
                instance.hide();
            } else if (action === 'toggle') {
                instance.toggle();
            } else if (action === 'dispose') {
                instance.dispose();
            }
        });
    };

    $.fn.modal.__bs5Bridge = true;
}

function ensureConfirmationModal() {
    if (document.getElementById('confirmationModal')) {
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
        <div id="confirmationModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}" />
                        <div class="modal-body">
                            <p class="question"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--dark" data-bs-dismiss="modal">No</button>
                            <button type="submit" class="btn btn--base">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(wrapper.firstElementChild);
}

function bindDelegatedHandlers() {
    if (typeof window.jQuery === 'undefined') {
        return;
    }

    const $ = window.jQuery;

    if (window.__templateInteractionsBound) {
        return;
    }

    window.__templateInteractionsBound = true;

    $(document).on('click', '.confirmationBtn', function onConfirmClick(event) {
        event.preventDefault();
        ensureConfirmationModal();

        const $button = $(this);
        const modal = $('#confirmationModal');
        modal.find('.question').text($button.data('question') || 'Are you sure?');
        modal.find('form').attr('action', $button.data('action') || '#');
        modal.modal('show');
    });

    $(document).on('click', '.withdrawModalBtn, .withdrawModalBtn *', function onWithdrawClick(event) {
        event.preventDefault();
        const $button = $(event.target).closest('.withdrawModalBtn');
        if (!$button.length) {
            return;
        }

        const modal = $('#withdrawModal');

        if (!modal.length) {
            return;
        }

        modal.find('.question').text($button.data('question') || 'Are you sure?');
        modal.find('form').attr('action', $button.data('action') || '#');
        modal.modal('show');
    });

    $(document).on('click', '.moreModalBtn, .moreModalBtn *', function onMoreClick(event) {
        event.preventDefault();
        const $button = $(event.target).closest('.moreModalBtn');
        if (!$button.length) {
            return;
        }

        const modal = $('#moreModal');

        if (!modal.length) {
            return;
        }

        modal.find('.job-title').text($button.data('title') || '');
        modal.find('.freelancer-name').text($button.data('freelancer') || '');
        modal.find('.bid-quote').text($button.data('quote') || '');
        modal.modal('show');
    });

    $(document).on('click', '.portfolioEDBtn', function onPortfolioToggleClick(event) {
        event.preventDefault();
        ensureConfirmationModal();

        const $button = $(this);
        const modal = $('#confirmationModal');
        modal.find('.question').text($button.data('question') || 'Are you sure?');
        modal.find('form').attr('action', $button.data('action') || '#');
        modal.modal('show');
    });

    $(document).on('click', '.rejectModalBtn', function onRejectClick(event) {
        event.preventDefault();
        const modal = $('#rejectModal');
        if (modal.length) {
            modal.modal('show');
        }
    });

    $(document).on('click', '.action-btn__icon', function onActionToggle(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.action-dropdown').removeClass('show');
        $(this).siblings('.action-dropdown').toggleClass('show');
    });

    $(document).on('click', function onDocumentClick() {
        $('.action-dropdown').removeClass('show');
    });

    $(document).on('click', '.action-dropdown', function onDropdownClick(event) {
        event.stopPropagation();
    });

    $(document).on('click', '.dashboard-body__bar, .dashboard-body__bar-icon', function onSidebarOpen(event) {
        event.preventDefault();
        $('.sidebar-menu').addClass('show-sidebar');
        $('.sidebar-overlay').addClass('show');
    });

    $(document).on('click', '.has-dropdown > .sidebar-menu-list__link', function onSidebarDropdownToggle(event) {
        event.preventDefault();
        const $item = $(this).closest('.has-dropdown');
        const isOpen = $item.hasClass('active') || $item.hasClass('dropdown-open');

        $('.has-dropdown').removeClass('active dropdown-open');
        $('.sidebar-submenu').removeClass('open-submenu');

        if (!isOpen) {
            $item.addClass('active dropdown-open');
            $item.find('.sidebar-submenu').first().addClass('open-submenu');
        }
    });

    $(document).on('click', '.sidebar-menu__close, .sidebar-overlay', function onSidebarClose() {
        $('.sidebar-menu').removeClass('show-sidebar');
        $('.sidebar-overlay').removeClass('show');
    });

    $(document).on('click', '[data-dashboard-user-menu-trigger]', function onDashboardMenuToggle(event) {
        event.preventDefault();
        event.stopPropagation();

        const menu = $(this).closest('[data-dashboard-user-menu]');
        const isOpen = menu.hasClass('is-open');
        $('[data-dashboard-user-menu]').removeClass('is-open');
        menu.find('[data-dashboard-user-menu-panel]').toggleClass('show', !isOpen);
        menu.toggleClass('is-open', !isOpen);
        $(this).attr('aria-expanded', !isOpen ? 'true' : 'false');
        menu.find('[data-dashboard-user-chevron]')
            .toggleClass('la-angle-down', isOpen)
            .toggleClass('la-angle-up', !isOpen);
    });

    $(document).on('click', function onDashboardMenuClose(event) {
        if (!$(event.target).closest('[data-dashboard-user-menu]').length) {
            $('[data-dashboard-user-menu]').removeClass('is-open');
            $('[data-dashboard-user-menu-panel]').removeClass('show');
            $('[data-dashboard-user-menu-trigger]').attr('aria-expanded', 'false');
            $('[data-dashboard-user-chevron]').removeClass('la-angle-up').addClass('la-angle-down');
        }
    });

    $(document).on('click', '.user-info__button, .user-info__right', function onUserMenuToggle(event) {
        if ($(event.target).closest('[data-dashboard-user-menu]').length) {
            return;
        }
        if ($(event.target).closest('.notification-link').length) {
            return;
        }
        $(this).closest('.user-info').find('.user-info-dropdown').toggleClass('show');
    });

    $(document).on('click', '.reviewRatingBtn:not(.disabled)', function onReviewRatingClick(event) {
        event.preventDefault();
        event.stopPropagation();

        const $btn = $(this);
        const $form = $('#projectReviewForm');
        const $modal = $('#ProjectReviewRatingModal');

        if (!$modal.length || !$form.length) {
            return;
        }

        const rating = $btn.data('rating');
        const review = $btn.data('review');
        const freeRating = $btn.data('freeRating') ?? $btn.data('free-rating');
        const freeReview = $btn.data('freeReview') ?? $btn.data('free-review');
        const formAction = $btn.data('action');

        $form[0].reset();
        $form.find('.star-input, input[name="rating"], input[name="buyer-rating"]').prop('checked', false);

        if (formAction) {
            $form.attr('action', formAction);
        }

        if ($('.buyer-review-wrapper').length && $('.freelancer-review-wrapper').length) {
            if (rating) {
                $('.buyer-review-wrapper').show();
                $(`.buyer-review-wrapper #buyer-star${rating}`).prop('checked', true);
                $('.buyer-review').text(review || 'No review provided');
            } else {
                $('.buyer-review-wrapper').hide();
            }

            if (freeRating) {
                $(`.freelancer-review-wrapper #star${freeRating}`).prop('checked', true);
                $('#review').val(freeReview || '');
            }
        } else {
            if (rating) {
                $(`#star${rating}`).prop('checked', true);
            }
            $('#review').val(review || '');

            $('.freelancer-review-wrapper .star-rating input').prop('checked', false);
            if (freeRating) {
                $('.freelancer-review-wrapper').show();
                $(`.freelancer-review-wrapper #star${freeRating}`).prop('checked', true);
                $('.freelancer-review').text(freeReview || 'No review provided');
            } else {
                $('.freelancer-review-wrapper').hide();
            }
        }

        $modal.modal('show');
    });

    $(document).on('click', function onUserMenuClose(event) {
        if ($(event.target).closest('[data-dashboard-user-menu]').length) {
            return;
        }
        if (!$(event.target).closest('.user-info').length) {
            $('.user-info-dropdown').removeClass('show');
        }
    });
}

function loadStylesheet(href) {
    return new Promise((resolve) => {
        if (document.querySelector(`link[href="${href}"]`)) {
            resolve();
            return;
        }

        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        link.onload = () => resolve();
        link.onerror = () => resolve();
        document.head.appendChild(link);
    });
}

function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = src;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error(`Failed to load ${src}`));
        document.body.appendChild(script);
    });
}

export function initSelect2AutoTokenize() {
    if (typeof window.jQuery === 'undefined') {
        return;
    }

    const $ = window.jQuery;
    const fields = $('.select2-auto-tokenize').filter(function filterUninitialized() {
        return !$(this).hasClass('select2-hidden-accessible');
    });

    if (!fields.length) {
        return;
    }

    const boot = async () => {
        await loadStylesheet('/assets/global/css/select2.min.css');

        if (!$.fn.select2) {
            await loadScriptOnce('/assets/global/js/select2.min.js');
        }

        fields.each(function initField() {
            const $field = $(this);
            if ($field.hasClass('select2-hidden-accessible')) {
                return;
            }

            if (!$field.parent().hasClass('select2-tokenize-wrap')) {
                $field.wrap('<div class="position-relative select2-tokenize-wrap"></div>');
            }

            $field.select2({
                tags: true,
                maximumSelectionLength: 10,
                tokenSeparators: [','],
                dropdownParent: $field.parent(),
                width: '100%',
            });
        });
    };

    boot().catch(() => {});
}

export function initVerificationBadgeTooltips(root = document) {
    if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
        return;
    }

    root.querySelectorAll('.verification-badge[data-bs-toggle="tooltip"]').forEach((element) => {
        bootstrap.Tooltip.getInstance(element)?.dispose();
        new bootstrap.Tooltip(element);
    });
}

export function initTemplateInteractions() {
    patchBootstrapModalBridge();
    ensureConfirmationModal();
    bindDelegatedHandlers();
    initSelect2AutoTokenize();
    initVerificationBadgeTooltips();
}
