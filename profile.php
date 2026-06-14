<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
$stmt->execute(['user_id' => currentUserId()]);
$orders = $stmt->fetchAll();

$pageTitle = 'Moj profil';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow-container">
        <h1>Moj profil</h1>
        <p>Prijavljeni ste kao <?= e($_SESSION['user']['name']); ?> (<?= e($_SESSION['user']['email']); ?>).</p>

        <h2>Moje narudžbe</h2>

        <?php if (!$orders): ?>
            <p>Nemate još nijednu narudžbu.</p>
        <?php else: ?>
            <div class="orders-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Datum</th>
                            <th>Status</th>
                            <th>Iznos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= (int) $order['id']; ?></td>
                                <td><?= e($order['created_at']); ?></td>
                                <td><?= e($order['status']); ?></td>
                                <td><?= formatPrice((float) $order['total_price']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>