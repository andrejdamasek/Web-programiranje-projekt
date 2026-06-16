(function () {
    const cfg = window.PRODUCTS_CONFIG;
    if (!cfg) return;

    const rangeData = cfg.rangeData;
    const widthMin  = cfg.widthMin;
    const widthMax  = cfg.widthMax;

    // ── Filter UI logika: kategorije, slider ──────────────────────────────
    const catSelect = document.getElementById('category-select');
    if (!catSelect) return;

    const priceDefaults = {};
    for (const slug in rangeData) {
        priceDefaults[slug] = { min: rangeData[slug].min, max: rangeData[slug].max };
    }
    priceDefaults[''] = { min: 0, max: 9999 };

    const thumbMin = document.getElementById('thumb-min');
    const thumbMax = document.getElementById('thumb-max');
    const inputMin = document.getElementById('min-price-input');
    const inputMax = document.getElementById('max-price-input');
    const rangeEl  = document.getElementById('price-range');
    const labelMin = document.getElementById('slider-label-min');
    const labelMax = document.getElementById('slider-label-max');

    function updateFilters(cat, resetValues) {
        document.querySelectorAll('.filter-group').forEach(function (group) {
            const groupCat = group.getAttribute('data-category');
            const visible  = !cat || cat === groupCat;
            group.style.display = visible ? '' : 'none';
            group.querySelectorAll('select, input').forEach(function (el) {
                el.disabled = !visible;
            });
            if (!visible && resetValues) {
                group.querySelectorAll('select').forEach(function (s) { s.value = ''; });
                group.querySelectorAll('input[type=number]').forEach(function (i) { i.value = ''; });
            }
        });

        const def = priceDefaults[cat] || priceDefaults[''];
        if (resetValues) {
            setSliderBounds(def.min, def.max, def.min, def.max);
        } else {
            var curMin = parseFloat(inputMin.value);
            var curMax = parseFloat(inputMax.value);
            if (isNaN(curMin)) curMin = def.min;
            if (isNaN(curMax)) curMax = def.max;
            setSliderBounds(def.min, def.max, curMin, curMax);
        }
    }

    catSelect.addEventListener('change', function () {
        updateFilters(this.value, true);
        if (this.value !== 'kosilice') {
            if (wThumbMin) { wThumbMin.value = widthMin; wInputMin.value = widthMin; }
            if (wThumbMax) { wThumbMax.value = widthMax; wInputMax.value = widthMax; }
            updateWidthTrack();
        }
        loadProducts();
    });

    // Trimmer weight special handling
    const weightSel    = document.getElementById('trimmer-weight-select');
    const weightHidden = document.getElementById('weight-range-hidden');
    if (weightSel && weightHidden) {
        function syncWeight() {
            if (weightSel.value === 'above6') {
                weightSel.name     = '';
                weightHidden.value = 'above6';
            } else {
                weightSel.name     = 'max_weight';
                weightHidden.value = '';
            }
        }
        weightSel.addEventListener('change', syncWeight);
        syncWeight();
    }

    function setSliderBounds(absMin, absMax, valMin, valMax) {
        thumbMin.min = absMin; thumbMin.max = absMax;
        thumbMax.min = absMin; thumbMax.max = absMax;
        thumbMin.value = valMin;
        thumbMax.value = valMax;
        inputMin.value = valMin;
        inputMax.value = valMax;
        updateTrack();
    }

    function updateTrack() {
        var lo   = parseFloat(thumbMin.value);
        var hi   = parseFloat(thumbMax.value);
        var mn   = parseFloat(thumbMin.min);
        var mx   = parseFloat(thumbMin.max);
        var span = mx - mn || 1;
        rangeEl.style.left  = ((lo - mn) / span * 100) + '%';
        rangeEl.style.right = ((mx - hi) / span * 100) + '%';
        if (labelMin) labelMin.textContent = lo + ' €';
        if (labelMax) labelMax.textContent = hi + ' €';
    }

    thumbMin.addEventListener('input', function () {
        if (parseFloat(thumbMin.value) > parseFloat(thumbMax.value)) thumbMin.value = thumbMax.value;
        inputMin.value = thumbMin.value;
        updateTrack();
    });
    thumbMax.addEventListener('input', function () {
        if (parseFloat(thumbMax.value) < parseFloat(thumbMin.value)) thumbMax.value = thumbMin.value;
        inputMax.value = thumbMax.value;
        updateTrack();
    });
    inputMin.addEventListener('input', function () {
        thumbMin.value = this.value;
        updateTrack();
    });
    inputMax.addEventListener('input', function () {
        thumbMax.value = this.value;
        updateTrack();
    });

    // ── Width slider ──────────────────────────────────────────────────────
    const wThumbMin  = document.getElementById('width-thumb-min');
    const wThumbMax  = document.getElementById('width-thumb-max');
    const wInputMin  = document.getElementById('min-width-input');
    const wInputMax  = document.getElementById('max-width-input');
    const wRangeEl   = document.getElementById('width-range');
    const wLabelMin  = document.getElementById('width-label-min');
    const wLabelMax  = document.getElementById('width-label-max');

    function updateWidthTrack() {
        if (!wThumbMin) return;
        var lo   = parseFloat(wThumbMin.value);
        var hi   = parseFloat(wThumbMax.value);
        var mn   = parseFloat(wThumbMin.min);
        var mx   = parseFloat(wThumbMin.max);
        var span = mx - mn || 1;
        wRangeEl.style.left  = ((lo - mn) / span * 100) + '%';
        wRangeEl.style.right = ((mx - hi) / span * 100) + '%';
        if (wLabelMin) wLabelMin.textContent = lo + ' cm';
        if (wLabelMax) wLabelMax.textContent = hi + ' cm';
    }

    if (wThumbMin && wThumbMax) {
        wThumbMin.addEventListener('input', function () {
            if (parseFloat(wThumbMin.value) > parseFloat(wThumbMax.value)) wThumbMin.value = wThumbMax.value;
            wInputMin.value = wThumbMin.value;
            updateWidthTrack();
        });
        wThumbMax.addEventListener('input', function () {
            if (parseFloat(wThumbMax.value) < parseFloat(wThumbMin.value)) wThumbMax.value = wThumbMin.value;
            wInputMax.value = wThumbMax.value;
            updateWidthTrack();
        });
        wInputMin.addEventListener('input', function () {
            wThumbMin.value = this.value;
            updateWidthTrack();
        });
        wInputMax.addEventListener('input', function () {
            wThumbMax.value = this.value;
            updateWidthTrack();
        });
        wThumbMin.addEventListener('change', loadProducts);
        wThumbMax.addEventListener('change', loadProducts);
    }

    // Init
    updateFilters(catSelect.value, false);
    updateTrack();
    updateWidthTrack();

    // ── AJAX dohvat i renderiranje proizvoda ───────────────────────────────

    function formatPrice(price) {
        return parseFloat(price).toLocaleString('hr-HR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' €';
    }

    function buildProductCard(p) {
        let specs = '';
        if (p.category_slug === 'kosilice' || p.category_slug === 'trimeri') {
            if (p.cutting_width_cm) {
                specs += `<li><span class="spec-label">Radna širina: </span><span>${p.cutting_width_cm} cm</span></li>`;
            }
            if (p.power_type) {
                specs += `<li><span class="spec-label">Pogon: </span><span>${p.power_type}</span></li>`;
            }
        }

        const shortDesc = p.short_description && p.short_description.length > 90
            ? p.short_description.substring(0, 90) + '...'
            : (p.short_description || '');

        return `
            <article class="product-card">
                <a href="product.php?id=${p.id}" class="product-card-img-link">
                    <img src="${p.image_url}" alt="${p.name}" loading="lazy">
                </a>
                <div class="product-card-body">
                    <span class="badge">${p.category_name}</span>
                    <h3>
                        <a href="product.php?id=${p.id}" class="product-card-title-link">${p.name}</a>
                    </h3>
                    <p>${shortDesc}</p>
                    <ul class="spec-list">${specs}</ul>
                    <div class="product-card-footer">
                        <strong>${formatPrice(p.price)}</strong>
                        <a class="text-link" href="product.php?id=${p.id}">Detalji →</a>
                    </div>
                </div>
            </article>
        `;
    }

    function loadProducts() {
        const form      = document.getElementById('filter-form');
        const grid      = document.getElementById('product-grid');
        const countEl   = document.getElementById('results-count');
        const noResults = document.getElementById('no-results');

        if (!form || !grid) return;

        countEl.textContent = 'Učitavanje...';

        const params = new URLSearchParams(new FormData(form));
        const sortVal = document.getElementById('sort-select').value;
        if (sortVal) params.set('sort', sortVal);

        history.replaceState(null, '', 'products.php?' + params.toString());

        fetch('api/products.php?' + params.toString())
            .then(function (response) { return response.json(); })
            .then(function (products) {
                grid.innerHTML = '';
                if (products.length === 0) {
                    noResults.style.display = '';
                    countEl.textContent = 'Pronađeno proizvoda: 0';
                } else {
                    noResults.style.display = 'none';
                    countEl.textContent = 'Pronađeno proizvoda: ' + products.length;
                    grid.innerHTML = products.map(buildProductCard).join('');
                }
            })
            .catch(function (err) {
                console.error('Greška pri dohvatu proizvoda:', err);
                countEl.textContent = 'Greška pri učitavanju.';
            });
    }

    document.getElementById('sort-select').addEventListener('change', function () {
        loadProducts();
    });

    loadProducts();

    document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        loadProducts();
    });

    document.querySelectorAll('#filter-form select').forEach(function (sel) {
        sel.addEventListener('change', loadProducts);
    });

    document.querySelectorAll('#filter-form input[type="number"]').forEach(function (inp) {
        inp.addEventListener('change', loadProducts);
    });

    if (thumbMin) thumbMin.addEventListener('change', loadProducts);
    if (thumbMax) thumbMax.addEventListener('change', loadProducts);

})();