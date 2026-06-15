<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName       = trim($_POST['first_name'] ?? '');
    $lastName        = trim($_POST['last_name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $redirect        = trim($_POST['redirect'] ?? '');

    if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
        $errors[] = 'Sva polja su obavezna.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email adresa nije ispravna.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Lozinka mora imati najmanje 6 znakova.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Lozinke se ne podudaraju.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = :email');
        $check->execute(['email' => $email]);

        if ($check->fetch()) {
            $errors[] = 'Korisnik s ovom email adresom već postoji.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password_hash)
                                   VALUES (:first_name, :last_name, :email, :password_hash)');
            $stmt->execute([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $newId = (int) $pdo->lastInsertId();
            $_SESSION['user'] = [
                'id'       => $newId,
                'name'     => $firstName . ' ' . $lastName,
                'email'    => $email,
                'is_admin' => false,
            ];
            $allowed = ['cart.php', 'profile.php', 'products.php', 'index.php'];
            redirect(($redirect && in_array($redirect, $allowed)) ? $redirect : 'index.php');
        }
    }
}

$pageTitle = 'Registracija';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section narrow-section">
    <div class="container narrow-container auth-card">
        <h1>Registracija korisnika</h1>

        <?php foreach ($errors as $error): ?>
            <p class="form-error"><?= e($error); ?></p>
        <?php endforeach; ?>

        <form method="POST" class="auth-form" id="register-form">
            <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? ''); ?>">
            <label>Ime
                <input type="text" name="first_name" required>
            </label>
            <label>Prezime
                <input type="text" name="last_name" required>
            </label>
            <label>Email
                <input type="email" name="email" required>
            </label>
            <label>Lozinka
                <input type="password" name="password" minlength="6" required>
            </label>
            <label>Potvrda lozinke
                <input type="password" name="confirm_password" minlength="6" required>
            </label>
            <button class="button" type="submit">Registriraj se</button>
        </form>
        <p class="auth-register-hint">
            Već imate račun?
            <a href="login.php?redirect=<?= urlencode($_GET['redirect'] ?? ''); ?>" class="text-link">Povratak na prijavu</a>
        </p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>