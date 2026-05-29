function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function animateList(container) {
    if (!container || prefersReducedMotion()) {
        return;
    }

    container.classList.remove('stitch-list-refresh');
    void container.offsetWidth;
    container.classList.add('stitch-list-refresh');
}

document.addEventListener('alpine:init', () => {
    Alpine.store('stitch', {
        animateList,
    });

    Alpine.store('filterSheet', {
        open: false,
        show() {
            this.open = true;
            document.body.classList.add('overflow-hidden');

            queueMicrotask(() => {
                const root = document.querySelector('[data-filter-sheet-root]');
                if (!root || typeof Alpine.$data !== 'function') {
                    return;
                }
                const sheet = Alpine.$data(root);
                const headerQ = document.querySelector('header input[name="q"]');
                if (sheet && headerQ) {
                    sheet.q = headerQ.value;
                }
            });
        },
        close() {
            this.open = false;
            document.body.classList.remove('overflow-hidden');
        },
        async quickSearch() {
            const root = document.querySelector('[data-filter-sheet-root]');
            const sheet = root ? Alpine.$data(root) : null;
            if (!sheet) {
                return;
            }
            const headerQ = document.querySelector('header input[name="q"]');
            if (headerQ) {
                sheet.q = headerQ.value;
            }
            await sheet.apply();
        },
    });

    Alpine.data('stitchFilterSheet', (initial = {}) => ({
        loading: false,
        resultCount: 0,
        q: initial.q ?? '',
        priceMin: initial.price_min ?? '',
        priceMax: initial.price_max ?? '',
        areaMin: initial.area_min ?? '',
        districts: ['Bình Thạnh', 'Quận 1', 'Thủ Đức', 'Gò Vấp'],
        areaOptions: [
            { value: '', label: 'Tất cả' },
            { value: '20', label: 'Dưới 20m²' },
            { value: '25', label: '20 - 30m²' },
            { value: '35', label: 'Trên 30m²' },
        ],

        init() {
            const feed = document.getElementById('listings-feed');
            const total = feed?.querySelector('[data-total]')?.dataset?.total;
            this.resultCount = total ? parseInt(total, 10) : 0;

            if (initial.openOnLoad) {
                Alpine.store('filterSheet').show();
            }
        },

        get priceLabel() {
            const min = this.priceMin !== '' && this.priceMin !== null ? `${this.priceMin}tr` : '1tr';
            const max = this.priceMax !== '' && this.priceMax !== null ? `${this.priceMax}tr` : '20tr+';
            if (!this.priceMin && !this.priceMax) {
                return '1tr - 20tr+';
            }
            return `${min} - ${max}`;
        },

        districtChipClass(district) {
            const base = 'stitch-filter-chip px-4 py-2 rounded-full text-label-md font-label-md';
            return this.q === district
                ? `${base} is-active bg-primary-fixed text-on-primary-fixed`
                : `${base} bg-surface-container-high text-on-surface-variant`;
        },

        areaChipClass(value) {
            const base = 'flex-shrink-0 px-6 py-2 border-2 rounded-xl text-label-md font-label-md transition-all duration-300';
            if (this.areaMin === value) {
                return `${base} border-primary bg-primary-fixed text-primary font-bold`;
            }
            return `${base} border-outline-variant bg-surface-container-lowest text-on-surface-variant`;
        },

        reset() {
            this.q = '';
            this.priceMin = '';
            this.priceMax = '';
            this.areaMin = '';
        },

        buildParams() {
            const params = new URLSearchParams();
            if (this.q?.trim()) {
                params.set('q', this.q.trim());
            }
            if (this.priceMin !== '' && this.priceMin !== null) {
                params.set('price_min', String(this.priceMin));
            }
            if (this.priceMax !== '' && this.priceMax !== null) {
                params.set('price_max', String(this.priceMax));
            }
            if (this.areaMin !== '' && this.areaMin !== null) {
                params.set('area_min', String(this.areaMin));
            }
            return params;
        },

        async apply() {
            this.loading = true;
            const params = this.buildParams();

            try {
                const response = await fetch(`/?${params.toString()}`, {
                    headers: {
                        'X-Partial-Listings': '1',
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Filter request failed');
                }

                const html = await response.text();
                const feed = document.getElementById('listings-feed');
                if (feed) {
                    feed.innerHTML = html;
                    const total = feed.querySelector('[data-total]')?.dataset?.total;
                    this.resultCount = total ? parseInt(total, 10) : 0;
                    window.stitchAnimateList?.(feed);
                }

                const headerQ = document.querySelector('header input[name=q]');
                if (headerQ) {
                    headerQ.value = this.q;
                }

                const url = params.toString() ? `/?${params.toString()}` : '/';
                history.replaceState({}, '', url);

                Alpine.store('filterSheet').close();
                feed?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (error) {
                console.error(error);
                window.location.href = `/?${params.toString()}`;
            } finally {
                this.loading = false;
            }
        },
    }));

    Alpine.data('stitchChips', () => ({
        active: 0,
        pick(index) {
            this.active = index;
        },
        chipClass(index) {
            const base = 'stitch-chip flex items-center gap-1 px-4 py-2 rounded-full text-label-md font-label-md whitespace-nowrap';
            if (this.active === index) {
                return `${base} is-active bg-primary-container text-on-primary-container`;
            }

            return `${base} bg-surface-container-high text-on-surface-variant`;
        },
    }));

    Alpine.data('stitchFilterChips', (initial = '') => ({
        active: initial,
        select(value) {
            this.active = value;
            const input = document.querySelector('[name=q]');
            if (input) {
                input.value = value;
            }
        },
        chipClass(value) {
            const base = 'stitch-filter-chip px-4 py-2 rounded-full text-label-md font-label-md';
            if (this.active === value) {
                return `${base} is-active bg-primary-fixed text-on-primary-fixed`;
            }

            return `${base} bg-surface-container-high text-on-surface-variant`;
        },
    }));

    Alpine.data('stitchFab', () => ({
        hidden: false,
        lastScroll: 0,
        init() {
            window.addEventListener('scroll', () => {
                const st = window.pageYOffset || document.documentElement.scrollTop;
                this.hidden = st > this.lastScroll && st > 100;
                this.lastScroll = st <= 0 ? 0 : st;
            }, { passive: true });
        },
    }));
});

document.addEventListener('alpine:initialized', () => {
    document.body.classList.add('stitch-ready');

    window.stitchAnimateList = (container) => Alpine.store('stitch').animateList(container);

    document.querySelectorAll('.stitch-stagger').forEach((el) => animateList(el));

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-filter-open]');
        if (!trigger) {
            return;
        }

        const store = Alpine.store('filterSheet');
        if (store) {
            store.show();
        }
    });
});

document.addEventListener('stitch:refresh-list', (event) => {
    if (event.target) {
        animateList(event.target);
    }
});
