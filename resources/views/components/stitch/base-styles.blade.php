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
    </style>
@include('components.stitch.motion-styles')
