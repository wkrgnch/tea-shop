<?php
require_once __DIR__ . '/helpers.php';

if (is_logged_in()) {
    flash_set('Вы уже вошли в аккаунт.');
    redirect('account.php');
}

$conn = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $pass1 = (string)($_POST['password'] ?? '');
    $pass2 = (string)($_POST['password2'] ?? '');

    if ($name === '' || $email === '' || $pass1 === '' || $pass2 === '') {
        flash_set('Заполните все поля.');
        redirect('register.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash_set('Некорректный email.');
        redirect('register.php');
    }
    if (mb_strlen($pass1) < 6) {
        flash_set('Пароль должен быть минимум 6 символов.');
        redirect('register.php');
    }
    if ($pass1 !== $pass2) {
        flash_set('Пароли не совпадают.');
        redirect('register.php');
    }

    // проверяем уникальность email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();

    if ($exists) {
        flash_set('Этот email уже зарегистрирован.');
        redirect('register.php');
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);

    $ins = $conn->prepare("INSERT INTO users (email, name, password_hash) VALUES (?, ?, ?)");
    $ins->bind_param("sss", $email, $name, $hash);
    $ins->execute();

    flash_set('Регистрация успешна. Теперь войдите.');
    redirect('index.php');
}

require_once __DIR__ . '/header.php';
?>

<section class="section">
    <div class="container">
        <h1>Регистрация</h1>
        <p class="muted">Создайте аккаунт, чтобы пользоваться списком покупок и обратной связью.</p>

        <form class="form" method="post">
        <label class="muted small">Имя</label>
        <input class="in wide" name="name" required maxlength="80" placeholder="Например: Иван">

        <label class="muted small">Email</label>
        <input class="in wide" name="email" type="email" required maxlength="190" placeholder="name@example.com">

        <label class="muted small">Пароль</label>
        <input class="in wide" name="password" type="password" required minlength="6" placeholder="Минимум 6 символов">

        <label class="muted small">Повтор пароля</label>
        <input class="in wide" name="password2" type="password" required minlength="6">

        <button class="btn" type="submit">Зарегистрироваться</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
