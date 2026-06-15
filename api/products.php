<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$category       = $_GET['category']        ?? '';
$powerType      = $_GET['power_type']       ?? '';
$bladeType      = $_GET['blade_type']       ?? '';
$minWidth       = $_GET['min_width']        ?? '';
$maxWidth       = $_GET['max_width']        ?? '';
$basketCapacity = $_GET['basket_capacity']  ?? '';
$trimmerBlade   = $_GET['trimmer_blade']    ?? '';
$maxWeight      = $_GET['max_weight']       ?? '';
$weightRange    = $_GET['weight_range']     ?? '';
$seedWeight     = $_GET['seed_weight']      ?? '';
$minPrice       = $_GET['min_price']        ?? '';
$maxPrice       = $_GET['max_price']        ?? '';

$sql = "SELECT p.id, p.name, p.brand, p.short_description, p.price,
               p.image_url, p.power_type, p.blade_type, p.cutting_width_cm,
               p.basket_capacity_l, p.weight_kg,
               c.name AS category_name, c.slug AS category_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

if ($category !== '') {
    $sql .= ' AND c.slug = :category';
    $params['category'] = $category;
}
if ($powerType !== '') {
    $sql .= ' AND p.power_type = :power_type';
    $params['power_type'] = $powerType;
}
if ($bladeType !== '') {
    $sql .= ' AND p.blade_type = :blade_type';
    $params['blade_type'] = $bladeType;
}
if ($minWidth !== '') {
    $sql .= ' AND p.cutting_width_cm >= :min_width';
    $params['min_width'] = (int) $minWidth;
}
if ($maxWidth !== '') {
    $sql .= ' AND p.cutting_width_cm <= :max_width';
    $params['max_width'] = (int) $maxWidth;
}
if ($basketCapacity !== '') {
    $sql .= ' AND p.basket_capacity_l >= :basket_capacity';
    $params['basket_capacity'] = (float) $basketCapacity;
}
if ($trimmerBlade === 'nit') {
    $sql .= " AND p.blade_type IN ('nit', 'nit/nož')";
} elseif ($trimmerBlade === 'noz') {
    $sql .= " AND p.blade_type IN ('nit/nož', 'nož')";
}
if ($maxWeight !== '' && $weightRange !== 'above6') {
    $sql .= ' AND p.weight_kg <= :max_weight';
    $params['max_weight'] = (float) $maxWeight;
}
if ($weightRange === 'above6') {
    $sql .= ' AND p.weight_kg >= 6';
}
if ($seedWeight !== '') {
    $sql .= ' AND p.weight_kg = :seed_weight';
    $params['seed_weight'] = (float) $seedWeight;
}
if ($minPrice !== '') {
    $sql .= ' AND p.price >= :min_price';
    $params['min_price'] = (float) $minPrice;
}
if ($maxPrice !== '') {
    $sql .= ' AND p.price <= :max_price';
    $params['max_price'] = (float) $maxPrice;
}

$sql .= ' ORDER BY p.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);