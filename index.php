<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Početna';
require_once __DIR__ . '/includes/header.php';

$featuredStmt = $pdo->query("SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE featured = 1 ORDER BY p.id DESC LIMIT 6");
$featuredProducts = $featuredStmt->fetchAll();
?>
<section class="hero section">
    <div class="container hero-grid">
        <div>
            <p class="eyebrow">Web trgovina za održavanje travnjaka</p>
            <h1>Oprema za uredan i zdrav travnjak na jednom mjestu.</h1>
            <p class="hero-text">Pregledaj kosilice, trimere i odabrane vrste sjemena trave. Aplikacija sadrži katalog, filtriranje proizvoda, registraciju korisnika, košaricu i pregled narudžbi.</p>
            <div class="button-row">
                <a class="button" href="products.php">Pregled proizvoda</a>
                <a class="button button-secondary" href="register.php">Izradi račun</a>
            </div>
        </div>

        <div class="hero-card">
            <h2>Izdvojene kategorije</h2>
            <div class="category-pills">
                <a href="products.php?category=kosilice">Kosilice</a>
                <a href="products.php?category=trimeri">Trimeri</a>
                <a href="products.php?category=sjeme-trave">Sjeme trave</a>
            </div>
            <ul class="feature-list">
                <li>Filtriranje po pogonu i radnoj širini</li>
                <li>Prijava i registracija korisnika</li>
                <li>Košarica i pregled ranijih narudžbi</li>
            </ul>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Odabrani artikli</p>
                <h2>Istaknuti proizvodi</h2>
            </div>
        </div>

        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <article class="product-card">
                    <a class="product-card-img-link" href="product.php?id=<?= (int) $product['id']; ?>" tabindex="-1" aria-hidden="true">
                        <img src="<?= e($product['image_url']); ?>" alt="<?= e($product['name']); ?>" loading="lazy">
                    </a>
                    <div class="product-card-body">
                        <span class="badge"><?= e($product['category_name']); ?></span>
                        <h3><a class="product-card-title-link" href="product.php?id=<?= (int) $product['id']; ?>"><?= e($product['name']); ?></a></h3>
                        <p><?= e(mb_strimwidth($product['short_description'], 0, 90, '...')); ?></p>
                        <div class="product-card-footer">
                            <strong><?= formatPrice((float) $product['price']); ?></strong>
                            <a class="text-link" href="product.php?id=<?= (int) $product['id']; ?>">Detalji</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>