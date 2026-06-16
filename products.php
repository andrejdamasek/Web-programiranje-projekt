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

// Dinamički dohvat raspona cijena i širine iz baze
require_once __DIR__ . '/config/database.php';

$rangeData = [];
$rangeStmt = $pdo->query("
    SELECT c.slug, MIN(p.price) AS min_price, MAX(p.price) AS max_price
    FROM products p JOIN categories c ON p.category_id = c.id
    GROUP BY c.slug
");
foreach ($rangeStmt->fetchAll() as $row) {
    $rangeData['price'][$row['slug']] = [
        'min' => (float) $row['min_price'],
        'max' => (float) $row['max_price'],
    ];
}
$widthStmt = $pdo->query("
    SELECT MIN(p.cutting_width_cm) AS min_w, MAX(p.cutting_width_cm) AS max_w
    FROM products p JOIN categories c ON p.category_id = c.id
    WHERE c.slug = 'kosilice' AND p.cutting_width_cm IS NOT NULL
");
$widthRow = $widthStmt->fetch();

$priceMin = $rangeData['price'][$category]['min'] ?? 0;
$priceMax = $rangeData['price'][$category]['max'] ?? 9999;
$currentMinPrice = ($minPrice !== '') ? (float)$minPrice : $priceMin;
$currentMaxPrice = ($maxPrice !== '') ? (float)$maxPrice : $priceMax;

$widthMin = (int) ($widthRow['min_w'] ?? 33);
$widthMax = (int) ($widthRow['max_w'] ?? 87);
$currentMinWidth = ($minWidth !== '') ? (int)$minWidth : $widthMin;
$currentMaxWidth = ($maxWidth !== '') ? (int)$maxWidth : $widthMax;

$rangeJson = json_encode($rangeData['price'] ?? [], JSON_UNESCAPED_UNICODE);

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
                     data-price-min="<?= (float)($rangeData['price']['kosilice']['min'] ?? 200); ?>" data-price-max="<?= (float)($rangeData['price']['kosilice']['max'] ?? 2510); ?>"
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
                                <input type="number" name="min_width" id="min-width-input" min="<?= $widthMin; ?>" max="<?= $widthMax; ?>"
                                       value="<?= e((string) $currentMinWidth); ?>">
                            </label>
                            <label>
                                <input type="number" name="max_width" id="max-width-input" min="<?= $widthMin; ?>" max="<?= $widthMax; ?>"
                                       value="<?= e((string) $currentMaxWidth); ?>">
                            </label>
                        </div>
                        <div class="price-slider-wrap">
                            <div class="price-slider-track" id="width-track">
                                <div class="price-slider-range" id="width-range"></div>
                            </div>
                            <input class="price-thumb price-thumb--left" type="range" id="width-thumb-min"
                                   min="<?= $widthMin; ?>" max="<?= $widthMax; ?>" step="1" value="<?= e((string) $currentMinWidth); ?>">
                            <input class="price-thumb price-thumb--right" type="range" id="width-thumb-max"
                                   min="<?= $widthMin; ?>" max="<?= $widthMax; ?>" step="1" value="<?= e((string) $currentMaxWidth); ?>">
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
                     data-price-min="<?= (float)($rangeData['price']['trimeri']['min'] ?? 38); ?>" data-price-max="<?= (float)($rangeData['price']['trimeri']['max'] ?? 220); ?>"
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
                     data-price-min="<?= (float)($rangeData['price']['sjeme-trave']['min'] ?? 0); ?>" data-price-max="<?= (float)($rangeData['price']['sjeme-trave']['max'] ?? 30); ?>"
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
                    <select id="sort-select" style="padding: 0.4rem 0.75rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); background-color: #ffffff; color: var(--color-text); font-size: var(--text-sm); cursor: pointer;">
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


<script>
window.PRODUCTS_CONFIG = {
    rangeData: <?= $rangeJson; ?>,
    widthMin:  <?= $widthMin; ?>,
    widthMax:  <?= $widthMax; ?>
};
</script>
<script src="js/products.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>