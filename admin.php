<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// isAdmin() provjerava $_SESSION['user']['is_admin'] – samo admini mogu pristupiti
if (!isAdmin()) {
    redirect('login.php');
}

$message = '';

// Brisanje proizvoda – prima POST s imenom "delete_product" i vrijednošću ID-a
if (isset($_POST['delete_product'])) {
    $deleteId = (int) $_POST['delete_product'];
    $pdo->prepare('DELETE FROM products WHERE id = :id')->execute(['id' => $deleteId]);
    $message = 'Proizvod je uspješno uklonjen.';
}

// Dohvat svih proizvoda s imenom kategorije (JOIN), od najnovijeg prema najstarijem
$stmt = $pdo->query("SELECT p.*, c.name AS category_name
                     FROM products p
                     JOIN categories c ON p.category_id = c.id
                     ORDER BY p.id DESC");
$products = $stmt->fetchAll();

$pageTitle = 'Admin panel';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <div class="admin-header">
            <div>
                <p class="eyebrow">Upravljanje sadržajem</p>
                <h1>Admin panel</h1>
            </div>
            <!-- Link na formu za dodavanje novog proizvoda -->
            <a href="admin_product_form.php" class="button">+ Dodaj novi proizvod</a>
        </div>

        <!-- Poruka o uspješnom brisanju -->
        <?php if ($message): ?>
            <p class="form-success"><?= e($message); ?></p>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naziv</th>
                        <th>Kategorija</th>
                        <th>Cijena</th>
                        <th>Zaliha</th>
                        <th>Istaknuto</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>#<?= (int) $p['id']; ?></td>
                            <td>
                                <div class="admin-product-name">
                                    <img src="<?= e($p['image_url']); ?>" alt="" width="40" height="40" loading="lazy">
                                    <span><?= e($p['name']); ?></span>
                                </div>
                            </td>
                            <td><?= e($p['category_name']); ?></td>
                            <td><?= formatPrice((float) $p['price']); ?></td>
                            <td>
                                <!-- Vizualni badge: stock-ok (zeleno), stock-low (≤3, žuto), stock-empty (crveno) -->
                                <span class="stock-badge <?= (int)$p['stock'] === 0 ? 'stock-empty' : ((int)$p['stock'] <= 3 ? 'stock-low' : 'stock-ok'); ?>">
                                    <?= (int) $p['stock']; ?> kom
                                </span>
                            </td>
                            <td><?= $p['featured'] ? '✓' : '—'; ?></td>
                            <td>
                                <div class="admin-actions">
                                    <!-- Link na formu za uređivanje s ID-om u URL-u -->
                                    <a href="admin_product_form.php?id=<?= (int) $p['id']; ?>" class="button button-small button-secondary">Uredi</a>
                                    <!-- confirm() dialog sprječava slučajno brisanje -->
                                    <form method="POST" onsubmit="return confirm('Jeste li sigurni da želite ukloniti ovaj proizvod?');">
                                        <button class="button button-small button-danger" type="submit"
                                                name="delete_product" value="<?= (int) $p['id']; ?>">Ukloni</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>