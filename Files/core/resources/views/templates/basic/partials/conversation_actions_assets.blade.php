<style>
    .conversation-actions {
        position: absolute;
        right: 0;
        top: 0;
        z-index: 4;
    }

    .conversation-actions__toggle {
        border: 0;
        background: #f3f4f6;
        color: #5b6671;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s linear;
    }

    .conversation-actions__toggle:hover,
    .conversation-actions.is-open .conversation-actions__toggle {
        background: hsl(var(--base) / 0.12);
        color: hsl(var(--base));
    }

    .conversation-actions__menu {
        position: absolute;
        top: calc(100% + 6px);
        right: 0;
        min-width: 170px;
        margin: 0;
        padding: 6px;
        list-style: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        visibility: hidden;
        opacity: 0;
        transform: translateY(-4px);
        transition: .15s ease;
        pointer-events: none;
    }

    .conversation-actions.is-open .conversation-actions__menu {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .conversation-actions__item {
        width: 100%;
        border: 0;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        text-align: left;
        cursor: pointer;
    }

    .conversation-actions__item:hover {
        background: #f8fafc;
    }

    .conversation-actions__item--danger {
        color: #dc2626;
    }

    .conversation-actions__item--success {
        color: #16a34a;
    }

    .conversation-actions__item--muted {
        color: #64748b;
    }

    .chat-item {
        position: relative;
        padding-right: 36px;
    }

    .chat-board-left-item li.disabled {
        cursor: not-allowed;
        background-color: #eef0f2;
        opacity: 0.85;
    }

    .chat-board-left-item li.disabled .conversation-actions__toggle {
        pointer-events: auto;
    }
</style>

<script>
    (function ($) {
        'use strict';

        $(document).on('click', '.conversation-actions__toggle', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const $actions = $(this).closest('.conversation-actions');
            $('.conversation-actions.is-open').not($actions).removeClass('is-open');
            $actions.toggleClass('is-open');
        });

        $(document).on('click', function () {
            $('.conversation-actions.is-open').removeClass('is-open');
        });

        $(document).on('click', '.conversation-actions__menu', function (event) {
            event.stopPropagation();
        });
    })(jQuery);
</script>
