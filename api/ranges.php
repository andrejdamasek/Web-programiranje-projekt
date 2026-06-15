<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$result = [];

// Cijena po kategoriji
$stmt = $pdo->query("
    SELECT c.slug, MIN(p.price) AS min_price, MAX(p.price) AS max_price
    FROM products p
    JOIN categories c ON p.category_id = c.id
    GROUP BY c.slug
");
foreach ($stmt->fetchAll() as $row) {
    $result['price'][$row['slug']] = [
        'min' => (float) $row['min_price'],
        'max' => (float) $row['max_price'],
    ];
}

// Širina košnje (samo kosilice)
$stmt = $pdo->query("
    SELECT MIN(p.cutting_width_cm) AS min_width, MAX(p.cutting_width_cm) AS max_width
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE c.slug = 'kosilice' AND p.cutting_width_cm IS NOT NULL
");
$row = $stmt->fetch();
$result['width'] = [
    'min' => (int) $row['min_width'],
    'max' => (int) $row['max_width'],
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);