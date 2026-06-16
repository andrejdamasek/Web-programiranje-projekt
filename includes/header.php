<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | Travnjak Centar' : 'Travnjak Centar'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
    <script defer src="js/main.js"></script>
</head>
<body>
<a class="skip-link" href="#main-content">Preskoči na sadržaj</a>
<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="brand" aria-label="Travnjak Centar početna">
            <img src="assets/logo.png" alt="Travnjak Centar" class="brand-logo" height="48" loading="eager">
        </a>
        <button class="nav-toggle" aria-label="Otvori navigaciju" data-nav-toggle>☰</button>

        <nav class="main-nav" data-nav>
            <a href="index.php">Početna</a>
            <a href="products.php?category=kosilice">Kosilice</a>
            <a href="products.php?category=trimeri">Trimeri</a>
            <a href="products.php?category=sjeme-trave">Sjeme trave</a>
            <a href="literatura.php">Literatura</a>
            <a href="cart.php">Košarica (<?= cartCount(); ?>)</a>

            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="admin.php" class="button button-small">Admin panel</a>
                <?php endif; ?>
                <a href="profile.php">Moj profil</a>
                <a href="logout.php">Odjava</a>
            <?php else: ?>
                <a href="login.php">Prijava</a>
                <a href="register.php" class="button button-small">Registracija</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main id="main-content">