<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
$stmt->execute(['user_id' => currentUserId()]);
$orders = $stmt->fetchAll();

// Dohvati stavke za sve narudžbe odjednom
$orderItems = [];
if ($orders) {
    $orderIds = array_column($orders, 'id');
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $itemStmt = $pdo->prepare(
        "SELECT oi.order_id, oi.quantity, oi.price_at_purchase, p.name, p.image_url
         FROM order_items oi
         JOIN products p ON p.id = oi.product_id
         WHERE oi.order_id IN ($placeholders)"
    );
    $itemStmt->execute($orderIds);
    foreach ($itemStmt->fetchAll() as $item) {
        $orderItems[$item['order_id']][] = $item;
    }
}

$pageTitle = 'Moj profil';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow-container">
        <h1>Moj profil</h1>
        <p>Pozdrav, <?= e($_SESSION['user']['name']); ?> (<?= e($_SESSION['user']['email']); ?>).</p>

        <h2>Moje narudžbe</h2>

        <?php if (!$orders): ?>
            <p>Nemate još nijednu narudžbu.</p>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <span class="order-id">#<?= (int) $order['id']; ?></span>
                            <span class="order-date"><?= e($order['created_at']); ?></span>
                            <span class="order-total"><?= formatPrice((float) $order['total_price']); ?></span>
                        </div>

                        <?php if (!empty($orderItems[$order['id']])): ?>
                            <ul class="order-items-list">
                                <?php foreach ($orderItems[$order['id']] as $item): ?>
                                    <li class="order-item">
                                        <img src="<?= e($item['image_url']); ?>" alt="<?= e($item['name']); ?>" width="52" height="52" loading="lazy">
                                        <div class="order-item-info">
                                            <span class="order-item-name"><?= e($item['name']); ?></span>
                                            <span class="order-item-meta">
                                                <?= (int) $item['quantity']; ?> kom &times; <?= formatPrice((float) $item['price_at_purchase']); ?>
                                            </span>
                                        </div>
                                        <span class="order-item-line">
                                            <?= formatPrice((float) $item['quantity'] * $item['price_at_purchase']); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>