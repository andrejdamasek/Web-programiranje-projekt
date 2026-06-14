<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$category = $_GET['category'] ?? '';

$sql = "SELECT p.id, p.name, p.price, p.image_url, p.power_type, p.blade_type, p.cutting_width_cm, c.slug AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

if ($category !== '') {
    $sql .= ' AND c.slug = :category';
    $params['category'] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);