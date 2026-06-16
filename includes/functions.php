<?php
// Pokrećemo sesiju samo ako već nije pokrenuta (izbjegavamo duplu sesiju)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vraća true ako je korisnik prijavljen (provjera $_SESSION['user'])
function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

// Vraća true ako je prijavljeni korisnik administrator
function isAdmin(): bool {
    return isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] === true;
}

// Vraća ID prijavljenog korisnika ili null ako nije prijavljen
function currentUserId(): ?int {
    return $_SESSION['user']['id'] ?? null;
}

// Preusmjerava korisnika na zadanu URL adresu i zaustavlja izvođenje
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

// Escape-a HTML posebne znakove za sigurni ispis u HTML-u (zaštita od XSS napada)
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Formatira cijenu u HR lokalizaciji s oznakom valute (npr. 1.299,99 €)
function formatPrice(float $price): string {
    return number_format($price, 2, ',', '.') . ' €';
}

// Vraća ukupan broj artikala u košarici (zbroj svih količina)
function cartCount(): int {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

// Dodaje proizvod u košaricu koja se čuva u sesiji
// Ako proizvod već postoji u košarici, samo povećava količinu
function addToCart(int $productId, int $quantity = 1): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $productId) {
            $item['quantity'] += $quantity;
            return;
        }
    }

    // Proizvod nije u košarici – dodajemo novu stavku
    $_SESSION['cart'][] = [
        'product_id' => $productId,
        'quantity' => $quantity,
    ];
}

// Dohvaća trenutnu zalihu (stock) proizvoda iz baze prema ID-u
function getStock(PDO $pdo, int $productId): int {
    $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = :id');
    $stmt->execute(['id' => $productId]);
    $row = $stmt->fetch();
    return $row ? (int) $row['stock'] : 0;
}