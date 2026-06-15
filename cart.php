<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$cartError = '';
$checkoutError = '';

// Uklanjanje proizvoda iz košarice
if (isset($_POST['remove'])) {
    $removeId = (int) $_POST['remove'];
    $_SESSION['cart'] = array_values(
        array_filter($_SESSION['cart'] ?? [], fn($item) => $item['product_id'] !== $removeId)
    );
}

// Promjena količine (+/-) u košarici
if (isset($_POST['change_qty'])) {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $delta     = (int) ($_POST['change_qty'] ?? 0);

    if ($productId && $delta !== 0 && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => &$item) {
            if ($item['product_id'] === $productId) {
                $currentQty = (int) $item['quantity'];
                $newQty     = $currentQty + $delta;

                // Ako padne na 0 ili manje, ukloni stavku
                if ($newQty <= 0) {
                    unset($_SESSION['cart'][$index]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                } else {
                    // Provjeri stock iz baze
                    $available = getStock($pdo, $productId);
                    if ($available <= 0) {
                        unset($_SESSION['cart'][$index]);
                        $_SESSION['cart'] = array_values($_SESSION['cart']);
                        $cartError = 'Proizvod više nije dostupan na zalihi i uklonjen je iz košarice.';
                    } else {
                        if ($newQty > $available) {
                            $newQty = $available;
                            $cartError = 'Ne može se dodati više od dostupne količine za odabrani proizvod.';
                        }
                        $item['quantity'] = $newQty;
                    }
                }
                unset($item);
                break;
            }
        }
    }
}

$cartItems = $_SESSION['cart'] ?? [];
$products  = [];
$total     = 0;

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
                    'product'    => $row,
                    'quantity'   => $item['quantity'],
                    'line_total' => $lineTotal
                ];
            }
        }
    }
}

// Checkout logika
if (isset($_POST['checkout']) && isLoggedIn() && $products) {

    // Provjeri zalihe za sve stavke prije nego što kreneš s transakcijom
    $stockErrors = [];
    foreach ($products as $item) {
        $availableStock = (int) $item['product']['stock'];
        if ($item['quantity'] > $availableStock) {
            $stockErrors[] = '"' . $item['product']['name'] . '" — traženo: ' . $item['quantity']
                . ' kom, dostupno: ' . $availableStock . ' kom.';
        }
    }

    if ($stockErrors) {
        $checkoutError = 'Narudžba nije moguća zbog nedovoljnih zaliha:<br>' . implode('<br>', $stockErrors);
    } else {
        $pdo->beginTransaction();
        try {
            $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_price, status)
                                        VALUES (:user_id, :total_price, :status)');
            $orderStmt->execute([
                'user_id'     => currentUserId(),
                'total_price' => $total,
                'status'      => 'nova',
            ]);
            $orderId = (int) $pdo->lastInsertId();

            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase)
                                       VALUES (:order_id, :product_id, :quantity, :price_at_purchase)');
            $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty_check');

            foreach ($products as $item) {
                $itemStmt->execute([
                    'order_id'          => $orderId,
                    'product_id'        => $item['product']['id'],
                    'quantity'          => $item['quantity'],
                    'price_at_purchase' => $item['product']['price'],
                ]);
                // Smanji stock — uvjet stock >= qty sprječava negativne vrijednosti
                $stockStmt->execute([
                    'qty'       => $item['quantity'],
                    'id'        => $item['product']['id'],
                    'qty_check' => $item['quantity'],
                ]);
                if ($stockStmt->rowCount() === 0) {
                    // Drugi korisnik je u međuvremenu kupio — rollback
                    throw new \RuntimeException('Nedovoljno zaliha za "' . $item['product']['name'] . '".');
                }
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            redirect('profile.php');

        } catch (\RuntimeException $e) {
            $pdo->rollBack();
            $checkoutError = $e->getMessage();
        }
    }
}

$pageTitle = 'Košarica';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow-container">
        <h1>Košarica</h1>

        <?php if (!empty($cartError)): ?>
            <p class="form-error"><?= e($cartError); ?></p>
        <?php endif; ?>

        <?php if (!$products): ?>
            <p>Košarica je trenutno prazna.</p>
        <?php else: ?>
            <div class="cart-list">
                <?php foreach ($products as $item): ?>
                    <article class="cart-item">
                        <img src="<?= e($item['product']['image_url']); ?>" alt="<?= e($item['product']['name']); ?>">
                        <div>
                            <h2><?= e($item['product']['name']); ?></h2>
                            <p>Ukupno: <?= formatPrice((float) $item['line_total']); ?></p>
                        </div>
                        <form method="POST" class="cart-item-actions">
                            <input type="hidden" name="product_id" value="<?= (int) $item['product']['id']; ?>">

                            <div class="cart-qty-controls">
                                <button class="button button-secondary" type="submit" name="change_qty" value="-1">−</button>
                                <span class="cart-qty-display"><?= (int) $item['quantity']; ?></span>
                                <button class="button button-secondary" type="submit" name="change_qty" value="1">+</button>
                            </div>

                            <button class="button button-secondary" type="submit" name="remove" value="<?= (int) $item['product']['id']; ?>">
                                Ukloni
                            </button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="checkout-box">
                <?php if (!empty($checkoutError)): ?>
                    <p class="form-error"><?= $checkoutError; ?></p>
                <?php endif; ?>

                <p><strong>Ukupno za naplatu:</strong> <?= formatPrice((float) $total); ?></p>

                <?php if (isLoggedIn()): ?>
                    <form method="POST">
                        <button class="button" name="checkout" value="1">Potvrdi narudžbu</button>
                    </form>
                <?php else: ?>
                    <p>Za završetak kupnje potrebno se prijaviti.</p>
                    <a class="button" href="login.php?redirect=cart.php">Idi na prijavu</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>