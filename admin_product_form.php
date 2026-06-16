<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Samo admini mogu pristupiti formi za upravljanje proizvodima
if (!isAdmin()) {
    redirect('login.php');
}

// Ako postoji ?id=X u URL-u, radi se o uređivanju; inače dodajemo novi proizvod
$id = (int) ($_GET['id'] ?? 0);
$product = null;
$errors = [];

// Dohvaćamo sve kategorije za <select> dropdown u formi
$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();

// Edit mode: dohvat podataka o proizvodu koji uređujemo
if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
    if (!$product) redirect('admin.php'); // Proizvod ne postoji – vraćamo na admin panel
}

// ── Obrada POST zahtjeva ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dohvaćamo i sanitiziramo sve vrijednosti iz forme
    // Opcionalna polja postaju null ako su prazna
    $fields = [
        'category_id'       => (int) ($_POST['category_id'] ?? 0),
        'name'              => trim($_POST['name'] ?? ''),
        'brand'             => trim($_POST['brand'] ?? ''),
        'short_description' => trim($_POST['short_description'] ?? ''),
        'description'       => trim($_POST['description'] ?? ''),
        'price'             => (float) str_replace(',', '.', $_POST['price'] ?? '0'), // podrška za zarez kao decimalni znak
        'stock'             => (int) ($_POST['stock'] ?? 0),
        'power_type'        => trim($_POST['power_type'] ?? '') ?: null,
        'blade_type'        => trim($_POST['blade_type'] ?? '') ?: null,
        'cutting_width_cm'  => ($_POST['cutting_width_cm'] ?? '') !== '' ? (float) $_POST['cutting_width_cm'] : null,
        'basket_capacity_l' => ($_POST['basket_capacity_l'] ?? '') !== '' ? (float) $_POST['basket_capacity_l'] : null,
        'weight_kg'         => ($_POST['weight_kg'] ?? '') !== '' ? (float) $_POST['weight_kg'] : null,
        'image_url'         => trim($_POST['image_url'] ?? ''),
        'featured'          => isset($_POST['featured']) ? 1 : 0,
    ];

    // Server-side validacija obaveznih polja
    if (!$fields['category_id'])            $errors[] = 'Kategorija je obavezna.';
    if ($fields['name'] === '')             $errors[] = 'Naziv je obavezan.';
    if ($fields['brand'] === '')            $errors[] = 'Brand je obavezan.';
    if ($fields['short_description'] === '') $errors[] = 'Kratki opis je obavezan.';
    if ($fields['description'] === '')      $errors[] = 'Opis je obavezan.';
    if ($fields['price'] <= 0)              $errors[] = 'Cijena mora biti veća od 0.';
    if ($fields['stock'] < 0)              $errors[] = 'Zaliha ne može biti negativna.';
    if ($fields['image_url'] === '')        $errors[] = 'URL slike je obavezan.';

    if (!$errors) {
        if ($id > 0) {
            // Uređivanje: UPDATE postojećeg zapisa
            $fields['id'] = $id;
            $pdo->prepare('UPDATE products SET
                category_id=:category_id, name=:name, brand=:brand,
                short_description=:short_description, description=:description,
                price=:price, stock=:stock, power_type=:power_type,
                blade_type=:blade_type, cutting_width_cm=:cutting_width_cm,
                basket_capacity_l=:basket_capacity_l, weight_kg=:weight_kg,
                image_url=:image_url, featured=:featured
                WHERE id=:id')->execute($fields);
        } else {
            // Dodavanje: INSERT novog zapisa
            $pdo->prepare('INSERT INTO products
                (category_id,name,brand,short_description,description,price,stock,
                 power_type,blade_type,cutting_width_cm,basket_capacity_l,weight_kg,image_url,featured)
                VALUES
                (:category_id,:name,:brand,:short_description,:description,:price,:stock,
                 :power_type,:blade_type,:cutting_width_cm,:basket_capacity_l,:weight_kg,:image_url,:featured)')
                ->execute($fields);
        }
        redirect('admin.php');
    }

    // Ako ima grešaka, čuvamo unesene podatke za ponovni prikaz forme
    $product = $fields;
    $product['id'] = $id;
}

$isEdit = $id > 0;
$pageTitle = $isEdit ? 'Uredi proizvod' : 'Dodaj proizvod';

// Helper lambda za čitanje vrijednosti polja – vraća $product[$key] ili $default
// Koristi se u HTML-u za popunjavanje forme s postojećim/unesenim podacima
$v = fn(string $key, mixed $default = '') => $product[$key] ?? $default;

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container narrow-container">
        <div class="admin-header">
            <div>
                <p class="eyebrow">Admin panel</p>
                <h1><?= $isEdit ? 'Uredi proizvod' : 'Dodaj novi proizvod'; ?></h1>
            </div>
            <a href="admin.php" class="button button-secondary">← Natrag</a>
        </div>

        <!-- Prikaz grešaka validacije -->
        <?php foreach ($errors as $err): ?>
            <p class="form-error"><?= e($err); ?></p>
        <?php endforeach; ?>

        <form method="POST" class="admin-form">

            <!-- Sekcija: osnovna polja -->
            <div class="form-section">
                <h2 class="form-section-title">Osnovno</h2>
                <label>Kategorija *
                    <select name="category_id" required>
                        <option value="">— odaberi —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int) $cat['id']; ?>"
                                <?= (int)$v('category_id') === (int)$cat['id'] ? 'selected' : ''; ?>>
                                <?= e($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Naziv *
                    <input type="text" name="name" value="<?= e((string)$v('name')); ?>" required>
                </label>
                <label>Brand *
                    <input type="text" name="brand" value="<?= e((string)$v('brand')); ?>" required>
                </label>
                <label>Kratki opis *
                    <input type="text" name="short_description" maxlength="255"
                           value="<?= e((string)$v('short_description')); ?>" required>
                </label>
                <label>Opis *
                    <textarea name="description" rows="5" required><?= e((string)$v('description')); ?></textarea>
                </label>
            </div>

            <!-- Sekcija: cijena i zaliha -->
            <div class="form-section">
                <h2 class="form-section-title">Cijena i zaliha</h2>
                <div class="range-grid">
                    <label>Cijena (€) *
                        <input type="number" name="price" min="0.01" step="0.01"
                               value="<?= e((string)$v('price', '')); ?>" required>
                    </label>
                    <label>Zaliha (kom) *
                        <input type="number" name="stock" min="0"
                               value="<?= e((string)$v('stock', 0)); ?>" required>
                    </label>
                </div>
            </div>

            <!-- Sekcija: tehnički podaci (opcionalna polja) -->
            <div class="form-section">
                <h2 class="form-section-title">Tehnički podaci <span class="form-optional">(opcionalno)</span></h2>
                <div class="range-grid">
                    <label>Vrsta pogona
                        <input type="text" name="power_type" placeholder="akumulatorski ili benzinski"
                               value="<?= e((string)$v('power_type', '')); ?>">
                    </label>
                    <label>Vrsta oštrice
                        <input type="text" name="blade_type" placeholder="rotary ili reel"
                               value="<?= e((string)$v('blade_type', '')); ?>">
                    </label>
                    <label>Radna širina (cm)
                        <input type="number" name="cutting_width_cm" min="0" step="0.1"
                               value="<?= e((string)$v('cutting_width_cm', '')); ?>">
                    </label>
                    <label>Kapacitet košare (L)
                        <input type="number" name="basket_capacity_l" min="0" step="0.1"
                               value="<?= e((string)$v('basket_capacity_l', '')); ?>">
                    </label>
                    <label>Težina (kg)
                        <input type="number" name="weight_kg" min="0" step="0.01"
                               value="<?= e((string)$v('weight_kg', '')); ?>">
                    </label>
                </div>
            </div>

            <!-- Sekcija: slika i istaknuto -->
            <div class="form-section">
                <h2 class="form-section-title">Slika i prikaz</h2>
                <!-- id="image-url-input" – main.js sluša input event i prikazuje live preview -->
                <label>URL slike *
                    <input type="url" name="image_url" id="image-url-input"
                           value="<?= e((string)$v('image_url', '')); ?>" required>
                </label>
                <!-- id="img-preview-wrap" i id="img-preview" – koristi main.js za live preview -->
                <div class="admin-img-preview" id="img-preview-wrap"
                     style="<?= $v('image_url') ? '' : 'display:none'; ?>">
                    <img src="<?= e((string)$v('image_url', '')); ?>" alt="Pregled slike" id="img-preview">
                </div>
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" value="1"
                           <?= $v('featured') ? 'checked' : ''; ?>>
                    Istaknuti proizvod (prikazuje se na početnoj stranici)
                </label>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">
                    <?= $isEdit ? 'Spremi izmjene' : 'Dodaj proizvod'; ?>
                </button>
                <a href="admin.php" class="button button-secondary">Odustani</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>