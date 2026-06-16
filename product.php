<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Dohvaćamo ID iz URL-a (?id=X), castamo na int radi sigurnosti
$id = (int) ($_GET['id'] ?? 0);

// Dohvat proizvoda iz baze s imenom kategorije (JOIN)
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name
                       FROM products p
                       JOIN categories c ON p.category_id = c.id
                       WHERE p.id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

// Proizvod ne postoji – vraćamo 404
if (!$product) {
    http_response_code(404);
    exit('Proizvod nije pronađen.');
}

$addedToCart = false;
$stockError  = '';

// Obrada POST zahtjeva – dodavanje u košaricu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $available = (int) $product['stock'];

    // Provjera koliko korisnik već ima ovog proizvoda u košarici (iz sesije)
    $inCart = 0;
    foreach ($_SESSION['cart'] ?? [] as $cartItem) {
        if ($cartItem['product_id'] === $id) {
            $inCart = $cartItem['quantity'];
            break;
        }
    }

    // Ako bi ukupna količina prešla zalihu, prikazujemo grešku
    if ($quantity + $inCart > $available) {
        $stockError = 'Nije moguće dodati ' . $quantity . ' kom. Dostupno: ' . max(0, $available - $inCart) . ' kom.';
    } else {
        // Dodajemo u košaricu (sesija) putem funkcije iz functions.php
        addToCart($id, $quantity);
        $addedToCart = true;
    }
}

$pageTitle = $product['name'];
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <!-- Back gumb – href se postavlja dinamički u main.js putem sessionStorage referrer -->
        <a href="#" id="back-btn" class="back-btn" aria-label="Povratak na listu proizvoda">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Povratak na proizvode
        </a>
    </div>

    <div class="container product-detail-grid">
        <!-- Lijeva kolona: slika proizvoda -->
        <div class="product-image-panel">
            <img src="<?= e($product['image_url']); ?>" alt="<?= e($product['name']); ?>" loading="lazy">
        </div>

        <!-- Desna kolona: naziv, cijena, opis, specifikacije, forma -->
        <div class="product-info-panel">
            <span class="badge"><?= e($product['category_name']); ?></span>
            <h1><?= e($product['name']); ?></h1>
            <p class="price-lg"><?= formatPrice((float) $product['price']); ?></p>
            <p><?= e($product['description']); ?></p>

            <!-- Tehničke specifikacije – null vrijednosti prikazujemo kao "Nije primjenjivo" -->
            <ul class="detail-list">
                <li><strong>Pogon:</strong> <?= e($product['power_type'] ?: 'Nije primjenjivo'); ?></li>
                <li><strong>Vrsta rezanja:</strong> <?= e($product['blade_type'] ?: 'Nije primjenjivo'); ?></li>
                <li><strong>Radna širina:</strong> <?= e((string) $product['cutting_width_cm']); ?> cm</li>
                <li><strong>Težina:</strong> <?= e((string) $product['weight_kg']); ?> kg</li>
                <li><strong>Dostupno:</strong> <?= e((string) $product['stock']); ?> kom</li>
            </ul>

            <!-- Poruka o grešci zalihe (prikazuje se samo ako korisnik pokuša dodati previše) -->
            <?php if ($stockError): ?>
                <p class="form-error"><?= e($stockError); ?></p>
            <?php endif; ?>

            <!-- Forma za kupnju – max količina ograničena na dostupnu zalihu -->
            <form method="POST" class="buy-box">
                <label>Količina
                    <input type="number" name="quantity" min="1" max="<?= (int) $product['stock']; ?>" value="1" required>
                </label>
                <button class="button" type="submit">Dodaj u košaricu</button>
            </form>
        </div>
    </div>
</section>

<!-- Cart modal – renderira se samo ako je $addedToCart = true -->
<!-- Logika zatvaranja (ESC, klik van, gumb) nalazi se u main.js -->
<?php if ($addedToCart): ?>
<div class="cart-modal-overlay" id="cartModal" role="dialog" aria-modal="true" aria-labelledby="cartModalTitle">
    <div class="cart-modal">
        <div class="cart-modal-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="32" height="32">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
        </div>
        <h2 id="cartModalTitle">Dodano u košaricu!</h2>
        <p><strong><?= e($product['name']); ?></strong> uspješno je dodan u vašu košaricu.</p>
        <div class="cart-modal-actions">
            <a href="cart.php" class="button">Nastavi u košaricu</a>
            <!-- "Nastavi kupovinu" – main.js ga zatvara i vraća na prethodnu stranicu -->
            <button class="button button-secondary" id="continueShoppingBtn">Nastavi kupovinu</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>