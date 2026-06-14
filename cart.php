<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (isset($_POST['remove'])) {
    $removeId = (int) $_POST['remove'];
    $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'] ?? [], fn($item) => $item['product_id'] !== $removeId));
}

$cartItems = $_SESSION['cart'] ?? [];
$products = [];
$total = 0;

if ($cartItems) {
    $ids = array_column($cartItems, 'product_id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productRows = $stmt->fetchAll();

    foreach ($productRows as $row) {
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $row['id']) {
                $lineTotal = $row['price'] * $item['quantity'];
                $total += $lineTotal;
                $products[] = [
                    'product' => $row,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal
                ];
            }
        }
    }
}

if (isset($_POST['checkout']) && isLoggedIn() && $products) {
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_price, status)
                                VALUES (:user_id, :total_price, :status)');
    $orderStmt->execute([
        'user_id' => currentUserId(),
        'total_price' => $total,
        'status' => 'nova',
    ]);

    $orderId = (int) $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase)
                               VALUES (:order_id, :product_id, :quantity, :price_at_purchase)');
    foreach ($products as $item) {
        $itemStmt->execute([
            'order_id' => $orderId,
            'product_id' => $item['product']['id'],
            'quantity' => $item['quantity'],
            'price_at_purchase' => $item['product']['price'],
        ]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    redirect('profile.php');
}

$pageTitle = 'Košarica';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow-container">
        <h1>Košarica</h1>

        <?php if (!$products): ?>
            <p>Košarica je trenutno prazna.</p>
        <?php else: ?>
            <div class="cart-list">
                <?php foreach ($products as $item): ?>
                    <article class="cart-item">
                        <img src="<?= e($item['product']['image_url']); ?>" alt="<?= e($item['product']['name']); ?>">
                        <div>
                            <h2><?= e($item['product']['name']); ?></h2>
                            <p>Količina: <?= (int) $item['quantity']; ?></p>
                            <p>Ukupno: <?= formatPrice((float) $item['line_total']); ?></p>
                        </div>
                        <form method="POST">
                            <button class="button button-secondary" type="submit" name="remove" value="<?= (int) $item['product']['id']; ?>">Ukloni</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="checkout-box">
                <p><strong>Ukupno za naplatu:</strong> <?= formatPrice((float) $total); ?></p>

                <?php if (isLoggedIn()): ?>
                    <form method="POST">
                        <button class="button" name="checkout" value="1">Potvrdi narudžbu</button>
                    </form>
                <?php else: ?>
                    <p>Za završetak kupnje potrebno se prijaviti.</p>
                    <a class="button" href="login.php">Idi na prijavu</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>