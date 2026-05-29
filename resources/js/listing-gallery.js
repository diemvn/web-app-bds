/**
 * Listing detail gallery: scroll-snap carousel with dots, counter, auto-advance.
 */
function initListingGallery() {
    const track = document.getElementById('gallery-track');
    if (!track) {
        return;
    }

    const slides = track.querySelectorAll('[data-gallery-slide]');
    const slideCount = slides.length;
    const dots = document.querySelectorAll('.gallery-dot');
    const counter = document.getElementById('gallery-counter');
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');

    if (slideCount <= 1) {
        return;
    }

    let currentIdx = 0;
    let autoTimer = null;
    let resumeTimer = null;
    const AUTO_MS = 4000;
    const RESUME_MS = 8000;

    function slideWidth() {
        return track.clientWidth;
    }

    function scrollToIndex(index, smooth = true) {
        const clamped = Math.max(0, Math.min(index, slideCount - 1));
        track.scrollTo({
            left: clamped * slideWidth(),
            behavior: smooth ? 'smooth' : 'auto',
        });
    }

    function updateUi(index) {
        currentIdx = index;
        dots.forEach((dot, i) => {
            const active = i === currentIdx;
            dot.classList.toggle('bg-white', active);
            dot.classList.toggle('w-4', active);
            dot.classList.toggle('bg-white/40', !active);
            dot.classList.toggle('w-2', !active);
        });
        if (counter) {
            counter.textContent = `${currentIdx + 1}/${slideCount}`;
        }
        if (prevBtn) {
            prevBtn.disabled = currentIdx === 0;
            prevBtn.classList.toggle('opacity-40', currentIdx === 0);
        }
        if (nextBtn) {
            nextBtn.disabled = currentIdx === slideCount - 1;
            nextBtn.classList.toggle('opacity-40', currentIdx === slideCount - 1);
        }
    }

    function indexFromScroll() {
        const w = slideWidth();
        if (w <= 0) {
            return 0;
        }
        return Math.round(track.scrollLeft / w);
    }

    function onScroll() {
        const idx = indexFromScroll();
        if (idx !== currentIdx) {
            updateUi(idx);
        }
    }

    function stopAuto() {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    }

    function startAuto() {
        stopAuto();
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }
        autoTimer = setInterval(() => {
            const next = (currentIdx + 1) % slideCount;
            scrollToIndex(next);
        }, AUTO_MS);
    }

    function pauseAuto() {
        stopAuto();
        if (resumeTimer) {
            clearTimeout(resumeTimer);
        }
        resumeTimer = setTimeout(startAuto, RESUME_MS);
    }

    track.addEventListener('scroll', onScroll, { passive: true });
    track.addEventListener('scrollend', onScroll, { passive: true });

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.dataset.index, 10);
            if (!Number.isNaN(idx)) {
                scrollToIndex(idx);
                pauseAuto();
            }
        });
    });

    prevBtn?.addEventListener('click', () => {
        scrollToIndex(currentIdx - 1);
        pauseAuto();
    });

    nextBtn?.addEventListener('click', () => {
        scrollToIndex(currentIdx + 1);
        pauseAuto();
    });

    ['touchstart', 'pointerdown'].forEach((evt) => {
        track.addEventListener(evt, pauseAuto, { passive: true });
    });

    window.addEventListener('resize', () => {
        scrollToIndex(currentIdx, false);
    });

    if (typeof IntersectionObserver !== 'undefined') {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    const idx = Array.from(slides).indexOf(entry.target);
                    if (idx >= 0 && idx !== currentIdx) {
                        updateUi(idx);
                    }
                });
            },
            { root: track, threshold: 0.6 },
        );
        slides.forEach((slide) => observer.observe(slide));
    }

    updateUi(0);
    startAuto();
}

function initDetailHeader() {
    const header = document.getElementById('detail-header');
    if (!header) {
        return;
    }

    header.classList.add('bg-transparent');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            header.classList.add('bg-surface/90', 'backdrop-blur-md', 'shadow-sm');
            header.classList.remove('bg-transparent');
        } else {
            header.classList.remove('bg-surface/90', 'backdrop-blur-md', 'shadow-sm');
            header.classList.add('bg-transparent');
        }
    }, { passive: true });
}

function initFavToggle() {
    const favBtn = document.getElementById('fav-btn');
    const favIcon = document.getElementById('fav-icon');
    if (!favBtn || !favIcon) {
        return;
    }

    let isFav = false;
    favBtn.addEventListener('click', () => {
        isFav = !isFav;
        favIcon.style.fontVariationSettings = isFav ? "'FILL' 1" : "'FILL' 0";
        favIcon.classList.toggle('text-error', isFav);
        favBtn.classList.add('scale-125');
        setTimeout(() => favBtn.classList.remove('scale-125'), 150);
    });
}

function initMiniMap() {
    const miniMap = document.getElementById('mini-map');
    if (!miniMap?.dataset.lat || typeof L === 'undefined') {
        return;
    }

    const lat = +miniMap.dataset.lat;
    const lng = +miniMap.dataset.lng;
    const map = L.map(miniMap, { zoomControl: false, attributionControl: false }).setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([lat, lng]).addTo(map);
}

document.addEventListener('DOMContentLoaded', () => {
    initListingGallery();
    initDetailHeader();
    initFavToggle();
});

window.initListingMiniMap = initMiniMap;
