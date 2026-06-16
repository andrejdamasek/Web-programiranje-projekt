<?php
// API endpoint – vraća JSON listu proizvoda prema filterima iz GET parametara
// Koristi ga products.js putem fetch() za AJAX dohvat bez reload-a stranice
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json; charset=utf-8');

// ── Dohvat GET parametara filtera ────────────────────────────────────────
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

// ── Dinamička izgradnja SQL upita ────────────────────────────────────────
// "WHERE 1=1" omogućuje dodavanje AND uvjeta bez provjere je li WHERE već napisan
$sql = "SELECT p.id, p.name, p.brand, p.short_description, p.price,
               p.image_url, p.power_type, p.blade_type, p.cutting_width_cm,
               p.basket_capacity_l, p.weight_kg,
               c.name AS category_name, c.slug AS category_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE 1=1";
$params = [];

// Filtriranje po kategoriji (URL slug: kosilice / trimeri / sjeme-trave)
if ($category !== '') {
    $sql .= ' AND c.slug = :category';
    $params['category'] = $category;
}
// Filtriranje po vrsti pogona (akumulatorski / benzinski)
if ($powerType !== '') {
    $sql .= ' AND p.power_type = :power_type';
    $params['power_type'] = $powerType;
}
// Filtriranje po vrsti oštrice (za kosilice: rotary / reel)
if ($bladeType !== '') {
    $sql .= ' AND p.blade_type = :blade_type';
    $params['blade_type'] = $bladeType;
}
// Raspon širine košnje (samo za kosilice)
if ($category === 'kosilice') {
    if ($minWidth !== '') {
        $sql .= ' AND p.cutting_width_cm >= :min_width';
        $params['min_width'] = (int) $minWidth;
    }
    if ($maxWidth !== '') {
        $sql .= ' AND p.cutting_width_cm <= :max_width';
        $params['max_width'] = (int) $maxWidth;
    }
}
// Minimalni kapacitet košare (samo za kosilice, u litrama)
if ($basketCapacity !== '') {
    $sql .= ' AND p.basket_capacity_l >= :basket_capacity';
    $params['basket_capacity'] = (float) $basketCapacity;
}
// Vrsta oštrice trimera – posebna logika jer "nit/nož" spada u obje kategorije
if ($trimmerBlade === 'nit') {
    $sql .= " AND p.blade_type IN ('nit', 'nit/nož')";
} elseif ($trimmerBlade === 'noz') {
    $sql .= " AND p.blade_type IN ('nit/nož', 'nož')";
}
// Maksimalna težina trimera – isključivo ako nije odabrano "above6"
if ($maxWeight !== '' && $weightRange !== 'above6') {
    $sql .= ' AND p.weight_kg <= :max_weight';
    $params['max_weight'] = (float) $maxWeight;
}
// Poseban slučaj: "Od 6 kg i više" – dolazi kao weight_range=above6
if ($weightRange === 'above6') {
    $sql .= ' AND p.weight_kg >= 6';
}
// Filtriranje sjemena po težini pakiranja (1 kg / 5 kg)
if ($seedWeight !== '') {
    $sql .= ' AND p.weight_kg = :seed_weight';
    $params['seed_weight'] = (float) $seedWeight;
}
// Raspon cijene (vrijedi za sve kategorije)
if ($minPrice !== '') {
    $sql .= ' AND p.price >= :min_price';
    $params['min_price'] = (float) $minPrice;
}
if ($maxPrice !== '') {
    $sql .= ' AND p.price <= :max_price';
    $params['max_price'] = (float) $maxPrice;
}

// ── Sortiranje rezultata ─────────────────────────────────────────────────
// Zadano: od najnovijeg; opcije: cijena uzlazno/silazno
$sort = $_GET['sort'] ?? '';
if ($sort === 'price_asc') {
    $sql .= ' ORDER BY p.price ASC';
} elseif ($sort === 'price_desc') {
    $sql .= ' ORDER BY p.price DESC';
} else {
    $sql .= ' ORDER BY p.created_at DESC';
}

// Izvršavamo prepared statement i vraćamo JSON niz proizvoda
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);