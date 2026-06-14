<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name
                       FROM products p
                       JOIN categories c ON p.category_id = c.id
                       WHERE p.id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    exit('Proizvod nije pronađen.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    addToCart($id, $quantity);
    redirect('cart.php');
}

$pageTitle = $product['name'];
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container product-detail-grid">
        <div class="product-image-panel">
            <img src="<?= e($product['image_url']); ?>" alt="<?= e($product['name']); ?>" loading="lazy">
        </div>

        <div class="product-info-panel">
            <span class="badge"><?= e($product['category_name']); ?></span>
            <h1><?= e($product['name']); ?></h1>
            <p class="price-lg"><?= formatPrice((float) $product['price']); ?></p>
            <p><?= e($product['description']); ?></p>

            <ul class="detail-list">
                <li><strong>Pogon:</strong> <?= e($product['power_type'] ?: 'Nije primjenjivo'); ?></li>
                <li><strong>Vrsta rezanja:</strong> <?= e($product['blade_type'] ?: 'Nije primjenjivo'); ?></li>
                <li><strong>Radna širina:</strong> <?= e((string) $product['cutting_width_cm']); ?> cm</li>
                <li><strong>Težina:</strong> <?= e((string) $product['weight_kg']); ?> kg</li>
                <li><strong>Dostupno:</strong> <?= e((string) $product['stock']); ?> kom</li>
            </ul>

            <form method="POST" class="buy-box">
                <label>Količina
                    <input type="number" name="quantity" min="1" max="10" value="1" required>
                </label>
                <button class="button" type="submit">Dodaj u košaricu</button>
            </form>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>