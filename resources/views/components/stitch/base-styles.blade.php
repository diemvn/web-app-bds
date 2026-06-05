<style>
        [x-cloak] { display: none !important; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hide-scrollbar::-webkit-scrollbar,
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar,
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .property-card-shadow {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.05);
        }
        .glass-fab {
            background: rgba(0, 63, 177, 0.9);
            backdrop-filter: blur(8px);
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
        }
        .form-input-focus:focus {
            border-color: #003fb1;
            box-shadow: 0 0 0 1px #003fb1;
        }
        .range-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            background: #dce2f3;
            border-radius: 3px;
            outline: none;
        }
        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 24px;
            height: 24px;
            background: #003fb1;
            border-radius: 50%;
            cursor: pointer;
            border: 4px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .safe-bottom, .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
        .map-gradient {
            background: radial-gradient(circle at 50% 50%, #f0f3ff 0%, #dce2f3 100%);
        }

        /* Taste-Skill Utilities */
        .taste-container {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        @media (min-width: 768px) {
            .taste-container { padding-left: 1.5rem; padding-right: 1.5rem; }
        }
        @media (min-width: 1024px) {
            .taste-container { padding-left: 2rem; padding-right: 2rem; }
        }

        .taste-section {
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
        @media (min-width: 768px) {
            .taste-section { padding-top: 6rem; padding-bottom: 6rem; }
        }
        @media (min-width: 1024px) {
            .taste-section { padding-top: 8rem; padding-bottom: 8rem; }
        }

        .taste-headline {
            font-size: 1.875rem;
            line-height: 2.25rem;
            letter-spacing: -0.05em;
            font-weight: 700;
        }
        @media (min-width: 768px) {
            .taste-headline { font-size: 2.25rem; line-height: 2.5rem; }
        }
        @media (min-width: 1024px) {
            .taste-headline { font-size: 3rem; line-height: 1; }
        }

        .taste-body {
            font-size: 1rem;
            line-height: 1.625;
            color: #434654; /* on-surface-variant */
            max-width: 65ch;
        }

        .tactile-feedback:active {
            transform: scale(0.98);
            transition: transform 0.1s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
@include('components.stitch.motion-styles')
