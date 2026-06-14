<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function currentUserId(): ?int {
    return $_SESSION['user']['id'] ?? null;
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string {
    return number_format($price, 2, ',', '.') . ' €';
}

function cartCount(): int {
    if (!isset($_SESSION['cart'])) {
        return 0;
    }
    return array_sum(array_column($_SESSION['cart'], 'quantity'));
}

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

    $_SESSION['cart'][] = [
        'product_id' => $productId,
        'quantity' => $quantity,
    ];
}

function getStock(PDO $pdo, int $productId): int {
    $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = :id');
    $stmt->execute(['id' => $productId]);
    $row = $stmt->fetch();
    return $row ? (int) $row['stock'] : 0;
}