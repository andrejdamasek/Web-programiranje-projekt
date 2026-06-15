<?php
$pageTitle = 'Proizvodi';

// Čitamo GET parametre samo za inicijalni prikaz filtera (selected stanja)
$category       = $_GET['category']        ?? '';
$bladeType      = $_GET['blade_type']      ?? '';
$minWidth       = $_GET['min_width']       ?? '';
$maxWidth       = $_GET['max_width']       ?? '';
$basketCapacity = $_GET['basket_capacity'] ?? '';
$trimmerBlade   = $_GET['trimmer_blade']   ?? '';
$maxWeight      = $_GET['max_weight']      ?? '';
$weightRange    = $_GET['weight_range']    ?? '';
$seedWeight     = $_GET['seed_weight']     ?? '';
$minPrice       = $_GET['min_price']       ?? '';
$maxPrice       = $_GET['max_price']       ?? '';
$powerType      = $_GET['power_type']      ?? '';

$priceDefaults = [
    'kosilice'    => ['min' => 200, 'max' => 2510],
    'trimeri'     => ['min' => 38,  'max' => 220],
    'sjeme-trave' => ['min' => 0,   'max' => 30],
];
$priceMin = isset($priceDefaults[$category]) ? $priceDefaults[$category]['min'] : 0;
$priceMax = isset($priceDefaults[$category]) ? $priceDefaults[$category]['max'] : 9999;
$currentMinPrice = ($minPrice !== '') ? (float)$minPrice : $priceMin;
$currentMaxPrice = ($maxPrice !== '') ? (float)$maxPrice : $priceMax;

$widthMin = 33;
$widthMax = 87;
$currentMinWidth = ($minWidth !== '') ? (int)$minWidth : $widthMin;
$currentMaxWidth = ($maxWidth !== '') ? (int)$maxWidth : $widthMax;

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container catalog-layout">
        <aside class="filters-card">
            <h1>Katalog proizvoda</h1>

            <form method="GET" class="filter-form" id="filter-form">
                <label>Kategorija
                    <select name="category" id="category-select">
                        <option value="">Sve kategorije</option>
                        <option value="kosilice"    <?= $category === 'kosilice'    ? 'selected' : ''; ?>>Kosilice</option>
                        <option value="trimeri"     <?= $category === 'trimeri'     ? 'selected' : ''; ?>>Trimeri</option>
                        <option value="sjeme-trave" <?= $category === 'sjeme-trave' ? 'selected' : ''; ?>>Sjeme trave</option>
                    </select>
                </label>

                <?php /* ===== KOSILICE ===== */ ?>
                <div class="filter-group" data-category="kosilice"
                     data-price-min="200" data-price-max="2510"
                     style="<?= ($category !== '' && $category !== 'kosilice') ? 'display:none' : ''; ?>">
                    <label>Vrsta pogona
                        <select name="power_type">
                            <option value="">Svi tipovi</option>
                            <option value="akumulatorski" <?= $powerType === 'akumulatorski' ? 'selected' : ''; ?>>Akumulatorski</option>
                            <option value="benzinski"     <?= $powerType === 'benzinski'     ? 'selected' : ''; ?>>Benzinski</option>
                        </select>
                    </label>

                    <label>Vrsta oštrice
                        <select name="blade_type">
                            <option value="">Sve vrste</option>
                            <option value="rotary" <?= $bladeType === 'rotary' ? 'selected' : ''; ?>>Rotary (okrugli nož)</option>
                            <option value="reel"   <?= $bladeType === 'reel'   ? 'selected' : ''; ?>>Cilindar (reel)</option>
                        </select>
                    </label>

                    <div class="width-filter-block">
                        <label class="price-filter-label">Širina košnje (cm)</label>
                        <div class="range-grid">
                            <label>
                                <input type="number" name="min_width" id="min-width-input" min="33" max="87"
                                       value="<?= e((string) $currentMinWidth); ?>">
                            </label>
                            <label>
                                <input type="number" name="max_width" id="max-width-input" min="33" max="87"
                                       value="<?= e((string) $currentMaxWidth); ?>">
                            </label>
                        </div>
                        <div class="price-slider-wrap">
                            <div class="price-slider-track" id="width-track">
                                <div class="price-slider-range" id="width-range"></div>
                            </div>
                            <input class="price-thumb price-thumb--left" type="range" id="width-thumb-min"
                                   min="33" max="87" step="1" value="<?= e((string) $currentMinWidth); ?>">
                            <input class="price-thumb price-thumb--right" type="range" id="width-thumb-max"
                                   min="33" max="87" step="1" value="<?= e((string) $currentMaxWidth); ?>">
                        </div>
                        <div class="price-slider-labels">
                            <span id="width-label-min"><?= e((string) $currentMinWidth); ?> cm</span>
                            <span id="width-label-max"><?= e((string) $currentMaxWidth); ?> cm</span>
                        </div>
                    </div>

                    <label>Min. kapacitet košare (L)
                        <select name="basket_capacity">
                            <option value="">Bilo koji</option>
                            <option value="30" <?= $basketCapacity === '30' ? 'selected' : ''; ?>>30 L i više</option>
                            <option value="45" <?= $basketCapacity === '45' ? 'selected' : ''; ?>>45 L i više</option>
                            <option value="60" <?= $basketCapacity === '60' ? 'selected' : ''; ?>>60 L i više</option>
                        </select>
                    </label>
                </div>

                <?php /* ===== TRIMERI ===== */ ?>
                <div class="filter-group" data-category="trimeri"
                     data-price-min="38" data-price-max="220"
                     style="<?= ($category !== 'trimeri') ? 'display:none' : ''; ?>">
                    <label>Vrsta pogona
                        <select name="power_type">
                            <option value="">Svi tipovi</option>
                            <option value="akumulatorski" <?= $powerType === 'akumulatorski' ? 'selected' : ''; ?>>Akumulatorski</option>
                            <option value="benzinski"     <?= $powerType === 'benzinski'     ? 'selected' : ''; ?>>Benzinski</option>
                        </select>
                    </label>

                    <label>Vrsta oštrice
                        <select name="trimmer_blade">
                            <option value="">Sve vrste</option>
                            <option value="nit" <?= $trimmerBlade === 'nit' ? 'selected' : ''; ?>>Nit (najlonska glava)</option>
                            <option value="noz" <?= $trimmerBlade === 'noz' ? 'selected' : ''; ?>>Metalni nož</option>
                        </select>
                    </label>

                    <label>Težina
                        <select name="max_weight" id="trimmer-weight-select">
                            <option value="">Bilo koja</option>
                            <option value="3"      <?= $maxWeight === '3'        ? 'selected' : ''; ?>>Do 3 kg</option>
                            <option value="above6" <?= $weightRange === 'above6' ? 'selected' : ''; ?>>Od 6 kg i više</option>
                        </select>
                    </label>
                    <input type="hidden" name="weight_range" id="weight-range-hidden" value="<?= e($weightRange); ?>">
                </div>

                <?php /* ===== SJEME TRAVE ===== */ ?>
                <div class="filter-group" data-category="sjeme-trave"
                     data-price-min="6" data-price-max="30"
                     style="<?= ($category !== 'sjeme-trave') ? 'display:none' : ''; ?>">
                    <label>Pakiranje (težina)
                        <select name="seed_weight">
                            <option value="">Sve veličine</option>
                            <option value="1" <?= $seedWeight === '1' ? 'selected' : ''; ?>>1 kg</option>
                            <option value="5" <?= $seedWeight === '5' ? 'selected' : ''; ?>>5 kg</option>
                        </select>
                    </label>
                </div>

                <?php /* ===== CIJENA ===== */ ?>
                <div class="price-filter-block">
                    <label class="price-filter-label">Raspon cijene (€)</label>
                    <div class="range-grid">
                        <label>
                            <input type="number" name="min_price" id="min-price-input" min="0"
                                   value="<?= e((string) $currentMinPrice); ?>">
                        </label>
                        <label>
                            <input type="number" name="max_price" id="max-price-input" min="0"
                                   value="<?= e((string) $currentMaxPrice); ?>">
                        </label>
                    </div>

                    <div class="price-slider-wrap">
                        <div class="price-slider-track" id="price-track">
                            <div class="price-slider-range" id="price-range"></div>
                        </div>
                        <input class="price-thumb price-thumb--left" type="range" id="thumb-min"
                               min="1" max="9999" step="1" value="<?= e((string) $currentMinPrice); ?>">
                        <input class="price-thumb price-thumb--right" type="range" id="thumb-max"
                               min="1" max="9999" step="1" value="<?= e((string) $currentMaxPrice); ?>">
                    </div>
                    <div class="price-slider-labels">
                        <span id="slider-label-min"><?= e((string) $currentMinPrice); ?> €</span>
                        <span id="slider-label-max"><?= e((string) $currentMaxPrice); ?> €</span>
                    </div>
                </div>

                <button type="submit" class="button" style="width:100%; margin-top: 0.5rem;">Primijeni filtere</button>
            </form>
        </aside>

        <div class="catalog-main">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Rezultati pretrage</p>
                    <h2 id="results-count">Učitavanje...</h2>
                </div>
                <div>
                    <label for="sort-select" style="font-size: var(--text-sm); font-weight: 600;">Sortiraj po:</label>
                    <select id="sort-select" style="margin-left: 0.5rem; padding: 0.4rem 0.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-surface); color: var(--color-text); font-size: var(--text-sm); cursor: pointer;">
                        <option value="">Zadano</option>
                        <option value="price_asc">Cijena: od manje prema većoj ↑</option>
                        <option value="price_desc">Cijena: od veće prema manjoj ↓</option>
                    </select>
                </div>
            </div>

            <div class="product-grid" id="product-grid">
                <!-- Kartice se dinamički pune JavaScriptom putem AJAX poziva -->
            </div>

            <p id="no-results" style="display:none; color: var(--color-text-muted); margin-top: 2rem;">
                Nema pronađenih proizvoda za odabrane filtere.
            </p>
        </div>
    </div>
</section>

<style>
.price-filter-block { margin-top: 1rem; }
.width-filter-block { margin-top: 1rem; }
.price-filter-label { display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: var(--text-sm, 0.875rem); }

.price-slider-wrap {
    position: relative;
    height: 28px;
    margin: 0.75rem 0 0.25rem;
}
.price-slider-track {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    height: 4px;
    background: var(--color-border, #d4d1ca);
    border-radius: 9999px;
}
.price-slider-range {
    position: absolute;
    height: 100%;
    background: var(--color-primary, #01696f);
    border-radius: 9999px;
}
.price-thumb {
    -webkit-appearance: none;
    appearance: none;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    height: 4px;
    background: transparent;
    pointer-events: none;
    outline: none;
    margin: 0;
    padding: 0;
}
.price-thumb::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--color-primary, #01696f);
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    cursor: pointer;
    pointer-events: all;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.price-thumb::-webkit-slider-thumb:hover,
.price-thumb::-webkit-slider-thumb:active {
    transform: scale(1.2);
    box-shadow: 0 0 0 4px rgba(1,105,111,0.2);
}
.price-thumb::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--color-primary, #01696f);
    border: 2px solid #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    cursor: pointer;
    pointer-events: all;
}
.price-slider-labels {
    display: flex;
    justify-content: space-between;
    font-size: var(--text-xs, 0.75rem);
    color: var(--color-text-muted, #7a7974);
    margin-top: 0.25rem;
}
</style>

<script>
(function () {
    // ── Filter UI logika: kategorije, slider ──────────────────────────────
    const catSelect = document.getElementById('category-select');
    if (!catSelect) return;

    const priceDefaults = {
        'kosilice':    { min: 200,  max: 2510 },
        'trimeri':     { min: 38,   max: 220  },
        'sjeme-trave': { min: 6,    max: 30   },
        '':            { min: 0,    max: 9999 },
    };

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
            if (wThumbMin) { wThumbMin.value = 33; wInputMin.value = 33; }
            if (wThumbMax) { wThumbMax.value = 87; wInputMax.value = 87; }
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

    document.getElementById('sort-select').addEventListener('change', () => {
        loadProducts();
    });

    // Inicijalni load stranice
    loadProducts();

    // Submit forme (gumb "Primijeni filtere")
    document.getElementById('filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        loadProducts();
    });

    // Automatski osvježi pri promjeni select-a
    document.querySelectorAll('#filter-form select').forEach(function (sel) {
        sel.addEventListener('change', loadProducts);
    });

    // Osvježi kad se number input promijeni
    document.querySelectorAll('#filter-form input[type="number"]').forEach(function (inp) {
        inp.addEventListener('change', loadProducts);
    });

    // Osvježi kad se price slider otpusti
    if (thumbMin) thumbMin.addEventListener('change', loadProducts);
    if (thumbMax) thumbMax.addEventListener('change', loadProducts);

})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>