<style>
    html {
        scroll-behavior: smooth;
    }

    @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    @keyframes stitch-fade-up {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes stitch-fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* —— Page & section enter —— */
    .stitch-page-enter {
        animation: stitch-fade-up 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    .stitch-section {
        opacity: 0;
        animation: stitch-fade-up 0.45s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .stitch-fade-up {
        opacity: 0;
        animation: stitch-fade-up 0.45s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .stitch-stagger > .stitch-stagger-item:nth-child(1) { animation-delay: 0.04s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(2) { animation-delay: 0.1s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(3) { animation-delay: 0.16s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(4) { animation-delay: 0.22s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(5) { animation-delay: 0.28s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(6) { animation-delay: 0.34s; }
    .stitch-stagger > .stitch-stagger-item:nth-child(n+7) { animation-delay: 0.4s; }

    .stitch-list-refresh .stitch-stagger-item,
    .stitch-list-refresh > a,
    .stitch-list-refresh .grid > a {
        animation: stitch-fade-up 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    .stitch-list-refresh .grid > a:nth-child(1),
    .stitch-list-refresh > a:nth-child(1) { animation-delay: 0.03s; }
    .stitch-list-refresh .grid > a:nth-child(2),
    .stitch-list-refresh > a:nth-child(2) { animation-delay: 0.09s; }
    .stitch-list-refresh .grid > a:nth-child(3),
    .stitch-list-refresh > a:nth-child(3) { animation-delay: 0.15s; }
    .stitch-list-refresh .grid > a:nth-child(4),
    .stitch-list-refresh > a:nth-child(4) { animation-delay: 0.21s; }
    .stitch-list-refresh .grid > a:nth-child(n+5),
    .stitch-list-refresh > a:nth-child(n+5) { animation-delay: 0.27s; }

    /* —— Listing cards (desktop hover depth) —— */
    .stitch-card {
        transition:
            box-shadow 0.35s cubic-bezier(0.22, 1, 0.36, 1),
            transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
            border-color 0.25s ease;
    }

    @media (min-width: 768px) {
        .stitch-card:hover {
            box-shadow: 0 14px 36px rgba(21, 28, 39, 0.14);
            transform: translateY(-3px);
        }

        .stitch-card:hover .stitch-card-image {
            transform: scale(1.05);
        }
    }

    .stitch-card-image {
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .stitch-card:active {
        transform: scale(0.99);
        transition-duration: 0.12s;
    }

    /* —— Filter chips & toolbar buttons —— */
    .stitch-chip,
    .stitch-filter-chip,
    .stitch-filter-btn {
        transition:
            background-color 0.25s ease,
            color 0.25s ease,
            border-color 0.25s ease,
            box-shadow 0.25s ease,
            transform 0.2s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @media (min-width: 768px) {
        .stitch-chip:not(.is-active):hover,
        .stitch-filter-chip:not(.is-active):hover {
            background-color: #e2e8f8 !important;
            color: #151c27 !important;
        }

        .stitch-filter-btn:hover {
            background-color: #e7eefe;
            border-color: #737686;
        }
    }

    .stitch-chip:active,
    .stitch-filter-chip:active,
    .stitch-filter-btn:active {
        transform: scale(0.96);
    }

    .stitch-chip.is-active {
        box-shadow: 0 2px 8px rgba(26, 86, 219, 0.2);
    }

    /* —— Primary CTA (brand orange + elevation) —— */
    .stitch-cta-primary {
        background-color: #fd761a !important;
        color: #ffffff !important;
        box-shadow:
            0 4px 6px rgba(253, 118, 26, 0.2),
            0 10px 28px rgba(253, 118, 26, 0.35);
        transition:
            box-shadow 0.3s cubic-bezier(0.22, 1, 0.36, 1),
            transform 0.2s cubic-bezier(0.22, 1, 0.36, 1),
            background-color 0.25s ease,
            filter 0.25s ease;
    }

    .stitch-cta-primary:hover {
        background-color: #e56510 !important;
        box-shadow:
            0 6px 10px rgba(253, 118, 26, 0.25),
            0 14px 36px rgba(253, 118, 26, 0.45);
        filter: brightness(1.02);
    }

    .stitch-cta-primary:active {
        transform: scale(0.97);
        box-shadow:
            0 2px 4px rgba(253, 118, 26, 0.2),
            0 6px 16px rgba(253, 118, 26, 0.3);
    }

    /* —— Secondary / FAB —— */
    .glass-fab {
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease;
    }

    .glass-fab:hover {
        box-shadow: 0 8px 24px rgba(0, 63, 177, 0.45);
        transform: scale(1.03);
    }

    /* —— Responsive: home feed → 2 cols on large desktop —— */
    @media (min-width: 1280px) {
        .stitch-home-feed {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            max-width: 1200px;
        }

        .stitch-home-shell {
            max-width: 1200px;
        }

        .stitch-home-shell header {
            max-width: 1200px;
        }
    }

    /* —— Map layout panel transition —— */
    .stitch-map-panel {
        transition: opacity 0.3s ease;
    }

    body.stitch-ready main,
    body.stitch-ready .stitch-map-desktop > main {
        animation: stitch-fade-in 0.35s ease both;
    }

    .stitch-detail-gallery {
        animation: stitch-fade-in 0.4s ease both;
    }

    @media (max-width: 767px) {
        #gallery-viewport.stitch-detail-gallery {
            height: min(50vh, 420px);
            min-height: max(280px, 42dvh);
        }
    }

    /* Listing detail: avoid double fade on main when sections animate */
    body.stitch-ready main:has(.stitch-detail-gallery) {
        animation: none;
    }
</style>
