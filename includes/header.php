<?php
// Učitava pomoćne funkcije (isLoggedIn, isAdmin, cartCount, e, itd.)
// i automatski pokreće sesiju ako nije pokrenuta
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Svaka stranica može postaviti $pageTitle; inače se prikazuje samo "Travnjak Centar" -->
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | Travnjak Centar' : 'Travnjak Centar'; ?></title>

    <!-- Google Fonts: Inter tipografija (preconnect ubrzava učitavanje) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Globalni stilovi (boje, tipografija, grid, kartice...) -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Stilovi specifični za stranicu proizvoda (price slider, width slider, filter grupe) -->
    <link rel="stylesheet" href="css/products.css">

    <!-- Globalni JS – defer znači da se izvodi nakon što je cijeli HTML parsiran -->
    <script defer src="js/main.js"></script>
</head>
<body>

<!-- Skip link za pristupačnost – omogućuje prelazak na sadržaj bez miša -->
<a class="skip-link" href="#main-content">Preskoči na sadržaj</a>

<header class="site-header">
    <div class="container header-inner">
        <!-- Logo / brand link na početnu stranicu -->
        <a href="index.php" aria-label="Travnjak Centar početna">
            <img src="assets/logo.png" alt="Travnjak Centar" class="brand-logo" height="48" loading="eager">
        </a>

        <!-- Hamburger gumb – vidljiv na mobilnim uređajima; toggle se radi u main.js -->
        <button class="nav-toggle" aria-label="Otvori navigaciju" data-nav-toggle>☰</button>

        <nav class="main-nav" data-nav>
            <a href="index.php">Početna</a>
            <a href="products.php?category=kosilice">Kosilice</a>
            <a href="products.php?category=trimeri">Trimeri</a>
            <a href="products.php?category=sjeme-trave">Sjeme trave</a>
            <a href="literatura.php">Literatura</a>
            <!-- cartCount() vraća ukupan broj artikala u košarici iz sesije -->
            <a href="cart.php">Košarica (<?= cartCount(); ?>)</a>

            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <!-- Admin panel link – prikazuje se samo administratorima -->
                    <a href="admin.php" class="button button-small">Admin panel</a>
                <?php endif; ?>
                <a href="profile.php">Moj profil</a>
                <a href="logout.php">Odjava</a>
            <?php else: ?>
                <!-- Gosti (neprijavljeni korisnici) vide prijavu i registraciju -->
                <a href="login.php">Prijava</a>
                <a href="register.php" class="button button-small">Registracija</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Sve stranice postavljaju sadržaj unutar ovog <main> taga -->
<main id="main-content">