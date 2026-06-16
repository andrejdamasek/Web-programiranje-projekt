<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';

// Obrada POST zahtjeva – pokušaj prijave
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = $_POST['redirect'] ?? ''; // Stranica na koju se vraćamo nakon prijave

    // Tražimo korisnika po emailu (prepared statement – zaštita od SQL injekcije)
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // password_verify() uspoređuje unesenu lozinku s bcrypt hash-om iz baze
    if ($user && password_verify($password, $user['password_hash'])) {
        // Prijava uspješna – spremamo podatke u sesiju
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'name'     => $user['first_name'] . ' ' . $user['last_name'],
            'email'    => $user['email'],
            'is_admin' => (bool) $user['is_admin'],
        ];
        // Whitelist dozvoljenih redirect stranica (zaštita od open redirect napada)
        $allowed = ['cart.php', 'profile.php', 'products.php', 'index.php'];
        if ($redirect && in_array($redirect, $allowed)) {
            redirect($redirect);
        } else {
            // Admin ide na admin panel, korisnik na početnu
            redirect($user['is_admin'] ? 'admin.php' : 'index.php');
        }
    }
     else {
        $error = 'Pogrešan email ili lozinka.';
    }
}

$pageTitle = 'Prijava';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section narrow-section">
    <div class="container narrow-container auth-card">
        <h1>Prijava korisnika</h1>

        <!-- Prikaz poruke o grešci pri neuspješnoj prijavi -->
        <?php if ($error): ?>
            <p class="form-error"><?= e($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <!-- Hidden polje prenosi redirect parametar kroz POST zahtjev -->
            <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? ''); ?>">
            <label>Email
                <input type="email" name="email" required>
            </label>
            <label>Lozinka
                <input type="password" name="password" required>
            </label>
            <button class="button" type="submit">Prijavi se</button>
        </form>
        <p class="auth-register-hint">
            Još niste registrirani?
            <!-- Redirect parametar prosljeđujemo i na stranicu za registraciju -->
            <a href="register.php?redirect=<?= urlencode($_GET['redirect'] ?? ''); ?>" class="text-link">Registrirajte se</a>
        </p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>