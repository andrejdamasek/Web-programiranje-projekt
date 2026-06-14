<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
        ];
        redirect('profile.php');
    } else {
        $error = 'Pogrešan email ili lozinka.';
    }
}

$pageTitle = 'Prijava';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section narrow-section">
    <div class="container narrow-container auth-card">
        <h1>Prijava korisnika</h1>

        <?php if ($error): ?>
            <p class="form-error"><?= e($error); ?></p>
        <?php endif; ?>

        <form method="POST" class="auth-form">
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
            <a href="register.php" class="text-link">Registrirajte se</a>
        </p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>