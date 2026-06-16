<?php
// Podaci za spajanje na MySQL bazu podataka
$host = 'localhost';
$dbname = 'travnjak_centar';
$username = 'root';
$password = '';

try {
    // ERRMODE_EXCEPTION – svaka greška baca iznimku (lakše debugiranje)
    // FETCH_ASSOC       – fetchAll() vraća asocijativne nizove (ime stupca => vrijednost)
    // EMULATE_PREPARES  – false znači prave prepared statements (zaštita od SQL injekcije) 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // Ako spajanje ne uspije, vraćamo HTTP 500 i zaustavljamo izvođenje
    http_response_code(500);
    exit('Greška pri spajanju na bazu: ' . $e->getMessage());
}