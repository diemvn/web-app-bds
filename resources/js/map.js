function formatPriceShort(price) {
    if (price >= 1000000) {
        return `${(price / 1000000).toFixed(1).replace('.0', '')}tr`;
    }
    return `${Math.round(price / 1000)}k`;
}

function initMap(containerId, primary) {
    const mapEl = document.getElementById(containerId);
    if (!mapEl) return null;

    const map = L.map(containerId, { zoomControl: false, attributionControl: true }).setView([10.7769, 106.7009], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

    L.polygon([[17.1, 111.1], [17.1, 112.9], [15.7, 112.9], [15.7, 111.1]], {
        color: '#c0392b', fillOpacity: 0.08, dashArray: '5,5',
    }).bindPopup('<b>🇻🇳 Hoàng Sa</b>').addTo(map);
    L.polygon([[12.0, 113.5], [12.0, 117.5], [7.0, 117.5], [7.0, 113.5]], {
        color: '#c0392b', fillOpacity: 0.08, dashArray: '5,5',
    }).bindPopup('<b>🇻🇳 Trường Sa</b>').addTo(map);

    const cluster = L.markerClusterGroup().addTo(map);

    return { map, cluster, primary };
}

function renderStitchCard(item, appName) {
    const img = item.image
        ? `<img alt="" class="stitch-card-image w-full h-full object-cover" src="${item.image}">`
        : '<div class="w-full h-full flex items-center justify-center bg-surface-container text-4xl">🏠</div>';
    const priceM = item.price >= 1000000
        ? `${(item.price / 1000000).toFixed(1).replace('.0', '')}tr`
        : formatPriceShort(item.price);
    const area = item.area ? `<span class="text-outline">|</span><span>${item.area} m²</span>` : '';
    const showcase = item.showcase
        ? '<div class="absolute bottom-2 left-2 bg-primary text-white text-[10px] px-2 py-0.5 rounded font-bold uppercase">Showcase</div>'
        : '';

    return `
        <a href="/phong/${item.slug}" class="stitch-card stitch-stagger-item group border border-outline-variant rounded-lg overflow-hidden bg-surface-container-lowest cursor-pointer block">
            <div class="relative aspect-video overflow-hidden">${img}
                <button type="button" class="absolute top-2 right-2 text-white hover:text-red-500 drop-shadow-md" onclick="event.preventDefault(); event.stopPropagation();">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                </button>
                ${showcase}
            </div>
            <div class="p-sm flex flex-col gap-1">
                <div class="flex items-center justify-between">
                    <span class="text-xl font-bold">${priceM} <span class="text-sm font-normal">/tháng</span></span>
                    <span class="material-symbols-outlined text-outline text-lg">more_horiz</span>
                </div>
                <div class="text-sm font-medium flex gap-2">
                    <span>1 p.ngủ</span><span class="text-outline">|</span><span>1 p.tắm</span>${area}
                </div>
                <div class="text-xs text-on-surface-variant truncate">${item.location || ''}</div>
                <div class="text-[10px] text-outline mt-1 uppercase font-bold tracking-tight">Niêm yết bởi: ${appName}</div>
            </div>
        </a>`;
}

document.addEventListener('DOMContentLoaded', () => {
    const primary = '#1a56db';
    const appName = document.querySelector('header .text-primary.tracking-tight')?.textContent?.trim() || 'Modern Living';

    const desktop = initMap('map', primary);
    const mobile = initMap('map-mobile', primary);
    const instances = [desktop, mobile].filter(Boolean);
    if (!instances.length) return;

    const panel = document.getElementById('listing-panel') || document.getElementById('listing-panel-mobile');
    const countEl = document.getElementById('map-result-count');
    const refMap = desktop?.map || mobile?.map;

    document.getElementById('map-zoom-in')?.addEventListener('click', () => refMap?.zoomIn());
    document.getElementById('map-zoom-out')?.addEventListener('click', () => refMap?.zoomOut());
    document.getElementById('map-my-location')?.addEventListener('click', () => {
        refMap?.locate({ setView: true, maxZoom: 14 });
    });

    const priceSelect = document.getElementById('map-price');
    const priceBtn = document.getElementById('map-price-btn');
    priceBtn?.addEventListener('click', () => {
        if (!priceSelect) return;
        const options = [...priceSelect.options];
        const idx = options.findIndex((o) => o.selected);
        const next = options[(idx + 1) % options.length];
        next.selected = true;
        window.loadListings?.();
    });
    priceSelect?.addEventListener('change', () => window.loadListings?.());

    function renderList(list) {
        if (!panel) return;
        if (!list?.length) {
            panel.innerHTML = '<p class="text-on-surface-variant text-sm text-center py-8 col-span-full">Không có phòng trong khu vực này.</p>';
            return;
        }
        const gridClass = panel.id === 'listing-panel'
            ? 'grid grid-cols-1 @xl:grid-cols-2 gap-md'
            : 'flex flex-col gap-md';
        panel.innerHTML = `<div class="${gridClass}">${list.map((item, i) => renderStitchCard({ ...item, showcase: i === 0 || i === 2 }, appName)).join('')}</div>`;
        if (typeof window.stitchAnimateList === 'function') {
            window.stitchAnimateList(panel);
        } else {
            panel.dispatchEvent(new CustomEvent('stitch:refresh-list', { bubbles: false }));
        }
    }

    function priceIcon(price, highlight) {
        const bg = highlight ? '#fd761a' : primary;
        return L.divIcon({
            className: '',
            html: `<button type="button" class="text-on-primary px-3 py-1 rounded shadow-md font-bold text-xs transition-transform duration-200 hover:scale-110" style="background:${bg};border:none;cursor:pointer">${formatPriceShort(price)}</button>`,
            iconSize: [56, 28],
            iconAnchor: [28, 14],
        });
    }

    function loadMarkers() {
        const ref = instances[0].map;
        const b = ref.getBounds();
        const params = new URLSearchParams({
            sw_lat: b.getSouth(),
            sw_lng: b.getWest(),
            ne_lat: b.getNorth(),
            ne_lng: b.getEast(),
        });
        const priceMax = document.getElementById('map-price')?.value;
        if (priceMax) params.set('price_max', priceMax);

        fetch(`/api/listings/map?${params}`)
            .then((r) => r.json())
            .then((data) => {
                instances.forEach(({ cluster }) => cluster.clearLayers());
                (data.features || []).forEach((f, i) => {
                    const [lng, lat] = f.geometry.coordinates;
                    if (!lat || !lng) return;
                    const highlight = (f.properties.price || 0) >= 10000000;
                    const m = L.marker([lat, lng], { icon: priceIcon(f.properties.price || 0, highlight) });
                    m.bindPopup(`<b>${f.properties.title}</b><br>${f.properties.price_display}`);
                    m.on('click', () => { window.location.href = `/phong/${f.properties.slug}`; });
                    instances.forEach(({ cluster }) => cluster.addLayer(m));
                });
                renderList(data.list || []);
                if (countEl) countEl.textContent = `${(data.list || []).length} kết quả`;
            });
    }

    window.loadListings = loadMarkers;
    instances.forEach(({ map }) => map.on('moveend', loadMarkers));
    loadMarkers();
    setTimeout(() => instances.forEach(({ map }) => map.invalidateSize()), 400);
});
