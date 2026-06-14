<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Proizvodi';
require_once __DIR__ . '/includes/header.php';

$category        = $_GET['category']        ?? '';
$bladeType       = $_GET['blade_type']      ?? '';
$minWidth        = $_GET['min_width']       ?? '';
$maxWidth        = $_GET['max_width']       ?? '';
$basketCapacity  = $_GET['basket_capacity'] ?? '';
$trimmerBlade    = $_GET['trimmer_blade']   ?? '';
$maxWeight       = $_GET['max_weight']      ?? '';
$weightRange     = $_GET['weight_range']    ?? '';
$seedWeight      = $_GET['seed_weight']     ?? '';
$minPrice        = $_GET['min_price']       ?? '';
$maxPrice        = $_GET['max_price']       ?? '';
$powerType       = $_GET['power_type'] ?? '';

$priceDefaults = [
    'kosilice'    => ['min' => 200, 'max' => 740],
    'trimeri'     => ['min' => 38,  'max' => 220],
    'sjeme-trave' => ['min' => 0,   'max' => 500],
];
$priceMin = isset($priceDefaults[$category]) ? $priceDefaults[$category]['min'] : 0;
$priceMax = isset($priceDefaults[$category]) ? $priceDefaults[$category]['max'] : 9999;
$currentMinPrice = ($minPrice !== '') ? (float)$minPrice : $priceMin;
$currentMaxPrice = ($maxPrice !== '') ? (float)$maxPrice : $priceMax;

$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

if ($category !== '') {
    $sql .= " AND c.slug = :category";
    $params['category'] = $category;
}

if ($powerType !== '') {
    $sql .= " AND p.power_type = :power_type";
    $params['power_type'] = $powerType;
}

if ($bladeType !== '') {
    $sql .= " AND p.blade_type = :blade_type";
    $params['blade_type'] = $bladeType;
}
if ($minWidth !== '') {
    $sql .= " AND p.cutting_width_cm >= :min_width";
    $params['min_width'] = (int) $minWidth;
}
if ($maxWidth !== '') {
    $sql .= " AND p.cutting_width_cm <= :max_width";
    $params['max_width'] = (int) $maxWidth;
}
if ($basketCapacity !== '') {
    $sql .= " AND p.basket_capacity_l >= :basket_capacity";
    $params['basket_capacity'] = (float) $basketCapacity;
}
if ($trimmerBlade === 'nit') {
    $sql .= " AND p.blade_type IN ('nit', 'nit/nož')";
} elseif ($trimmerBlade === 'noz') {
    $sql .= " AND p.blade_type IN ('nit/nož', 'nož')";
}
if ($maxWeight !== '') {
    $sql .= " AND p.weight_kg <= :max_weight";
    $params['max_weight'] = (float) $maxWeight;
}
if ($weightRange === 'above6') {
    $sql .= " AND p.weight_kg >= 6";
}
if ($seedWeight !== '') {
    $sql .= " AND p.weight_kg = :seed_weight";
    $params['seed_weight'] = (float) $seedWeight;
}
if ($minPrice !== '') {
    $sql .= " AND p.price >= :min_price";
    $params['min_price'] = (float) $minPrice;
}
if ($maxPrice !== '') {
    $sql .= " AND p.price <= :max_price";
    $params['max_price'] = (float) $maxPrice;
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
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
                     data-price-min="200" data-price-max="740"
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

                    <div class="range-grid">
                        <label>Min. širina košnje (cm)
                            <input type="number" name="min_width" min="33" value="<?= e((string) $minWidth); ?>">
                        </label>
                        <label>Max. širina košnje (cm)
                            <input type="number" name="max_width" min="33" max="51" value="<?= e((string) $maxWidth); ?>">
                        </label>
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
                            <option value="nit"  <?= $trimmerBlade === 'nit'  ? 'selected' : ''; ?>>Nit (najlonska glava)</option>
                            <option value="noz"  <?= $trimmerBlade === 'noz'  ? 'selected' : ''; ?>>Metalni nož</option>
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
                        <input class="price-thumb price-thumb--left"  type="range" id="thumb-min"
                               min="1" max="9999" step="1" value="<?= e((string) $currentMinPrice); ?>">
                        <input class="price-thumb price-thumb--right" type="range" id="thumb-max"
                               min="1" max="9999" step="1" value="<?= e((string) $currentMaxPrice); ?>">
                    </div>
                    <div class="price-slider-labels">
                        <span id="slider-label-min"><?= e((string) $currentMinPrice); ?> €</span>
                        <span id="slider-label-max"><?= e((string) $currentMaxPrice); ?> €</span>
                    </div>
                </div>

                <button class="button" type="submit">Primijeni filtere</button>
            </form>
        </aside>

        <div>
            <div class="section-heading compact">
                <div>
                    <p class="eyebrow">Rezultati pretrage</p>
                    <h2>Pronađeno proizvoda: <?= count($products); ?></h2>
                </div>
            </div>

            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <a href="product.php?id=<?= (int) $product['id']; ?>" class="product-card-img-link">
                            <img src="<?= e($product['image_url']); ?>" alt="<?= e($product['name']); ?>" loading="lazy">
                        </a>
                        <div class="product-card-body">
                            <span class="badge"><?= e($product['category_name']); ?></span>
                            <h3>
                                <a href="product.php?id=<?= (int) $product['id']; ?>" class="product-card-title-link">
                                    <?= e($product['name']); ?>
                                </a>
                            </h3>
                            <p><?= e(mb_strimwidth($product['short_description'], 0, 90, '...')); ?></p>

                            <ul class="spec-list">
                                <?php if ($product['category_slug'] === 'kosilice'): ?>
                                    <?php if ($product['cutting_width_cm']): ?>
                                        <li><span class="spec-label">Radna širina: </span><span><?= e((string) $product['cutting_width_cm']); ?> cm</span></li>
                                    <?php endif; ?>                               
                
                                    <?php if ($product['power_type']): ?>
                                        <li><span class="spec-label">Pogon: </span><span><?= e($product['power_type']); ?></span></li>
                                    <?php endif; ?>

                                <?php elseif ($product['category_slug'] === 'trimeri'): ?>
                                    
                                    
                                    <?php if ($product['cutting_width_cm']): ?>
                                        <li><span class="spec-label">Radna širina: </span><span><?= e((string) $product['cutting_width_cm']); ?> cm</span></li>
                                    <?php endif; ?>
                                    <?php if ($product['power_type']): ?>
                                        <li><span class="spec-label">Pogon: </span><span><?= e($product['power_type']); ?></span></li>
                                    <?php endif; ?>

                                <?php elseif ($product['category_slug'] === 'sjeme-trave'): ?>
                                   
                                <?php endif; ?>
                            </ul>

                            <div class="product-card-footer">
                                <strong><?= formatPrice((float) $product['price']); ?></strong>
                                <a class="text-link" href="product.php?id=<?= (int) $product['id']; ?>">Detalji →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<style>
.price-filter-block { margin-top: 1rem; }
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
    const catSelect = document.getElementById('category-select');
    if (!catSelect) return;

    const priceDefaults = {
        'kosilice':    { min: 200, max: 740 },
        'trimeri':     { min: 38,  max: 220 },
        'sjeme-trave': { min: 6,   max: 30 },
        '':            { min: 0,   max: 9999 }
    };

    const thumbMin = document.getElementById('thumb-min');
    const thumbMax = document.getElementById('thumb-max');
    const inputMin = document.getElementById('min-price-input');
    const inputMax = document.getElementById('max-price-input');
    const rangeEl  = document.getElementById('price-range');
    const labelMin = document.getElementById('slider-label-min');
    const labelMax = document.getElementById('slider-label-max');

    // resetValues=false na page load — ne prepisuj PHP-renderirane vrijednosti
    function updateFilters(cat, resetValues) {
        document.querySelectorAll('.filter-group').forEach(function (group) {
            const groupCat = group.getAttribute('data-category');
            if (cat === '' || cat === groupCat) {
                group.style.display = '';
            } else {
                group.style.display = 'none';
                if (resetValues) {
                    group.querySelectorAll('select').forEach(function (s) { s.value = ''; });
                    group.querySelectorAll('input[type="number"]').forEach(function (i) { i.value = ''; });
                }
            }
        });

        const def = priceDefaults[cat] || priceDefaults[''];
        if (resetValues) {
            // Korisnik je promijenio kategoriju — resetiraj cijenu na category defaultove
            setSliderBounds(def.min, def.max, def.min, def.max);
        } else {
            // Page load — zadrži vrijednosti koje je PHP već postavio iz URL parametara
            var curMin = parseFloat(inputMin.value);
            var curMax = parseFloat(inputMax.value);
            if (isNaN(curMin)) curMin = def.min;
            if (isNaN(curMax)) curMax = def.max;
            setSliderBounds(def.min, def.max, curMin, curMax);
        }
                    
        

    }

    catSelect.addEventListener('change', function () {
        updateFilters(this.value, true);
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

    // Init — false = ne resetiraj vrijednosti na page load
    updateFilters(catSelect.value, false);
    updateTrack();
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>