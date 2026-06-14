<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Proizvodi';
require_once __DIR__ . '/includes/header.php';

$category = $_GET['category'] ?? '';
$powerType = $_GET['power_type'] ?? '';
$bladeType = $_GET['blade_type'] ?? '';
$minWidth = $_GET['min_width'] ?? '';
$maxWidth = $_GET['max_width'] ?? '';

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

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<section class="section">
    <div class="container catalog-layout">
        <aside class="filters-card">
            <h1>Katalog proizvoda</h1>

            <form method="GET" class="filter-form">
                <label>Kategorija
                    <select name="category">
                        <option value="">Sve kategorije</option>
                        <option value="kosilice" <?= $category === 'kosilice' ? 'selected' : ''; ?>>Kosilice</option>
                        <option value="trimeri" <?= $category === 'trimeri' ? 'selected' : ''; ?>>Trimeri</option>
                        <option value="sjeme-trave" <?= $category === 'sjeme-trave' ? 'selected' : ''; ?>>Sjeme trave</option>
                    </select>
                </label>

                <label>Pogon
                    <select name="power_type">
                        <option value="">Svi tipovi</option>
                        <option value="akumulatorski" <?= $powerType === 'akumulatorski' ? 'selected' : ''; ?>>Akumulatorski</option>
                        <option value="benzinski" <?= $powerType === 'benzinski' ? 'selected' : ''; ?>>Benzinski</option>
                    </select>
                </label>

                <label>Vrsta noža / rezanja
                    <select name="blade_type">
                        <option value="">Sve vrste</option>
                        <option value="rotary" <?= $bladeType === 'rotary' ? 'selected' : ''; ?>>Standardni okrugli</option>
                        <option value="reel" <?= $bladeType === 'reel' ? 'selected' : ''; ?>>Reel / Cylinder</option>
                        <option value="line-head" <?= $bladeType === 'line-head' ? 'selected' : ''; ?>>Najlonska glava</option>
                        <option value="metal-blade" <?= $bladeType === 'metal-blade' ? 'selected' : ''; ?>>Metalni nož</option>
                    </select>
                </label>

                <div class="range-grid">
                    <label>Min. širina (cm)
                        <input type="number" name="min_width" min="0" value="<?= e((string) $minWidth); ?>">
                    </label>
                    <label>Max. širina (cm)
                        <input type="number" name="max_width" min="0" value="<?= e((string) $maxWidth); ?>">
                    </label>
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
                        <img src="<?= e($product['image_url']); ?>" alt="<?= e($product['name']); ?>" loading="lazy">
                        <div class="product-card-body">
                            <span class="badge"><?= e($product['category_name']); ?></span>
                            <h3><?= e($product['name']); ?></h3>
                            <p><?= e(mb_strimwidth($product['short_description'], 0, 90, '...')); ?></p>
                            <div class="spec-row">
                                <span><?= e($product['power_type'] ?: 'Nije primjenjivo'); ?></span>
                                <span><?= e((string) $product['cutting_width_cm']); ?> cm</span>
                            </div>
                            <div class="product-card-footer">
                                <strong><?= formatPrice((float) $product['price']); ?></strong>
                                <a class="text-link" href="product.php?id=<?= (int) $product['id']; ?>">Detalji</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>