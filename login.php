<?php
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$redirectTo = (string)($_POST['redirect_to'] ?? 'index.php');

if ($email === '' || $password === '') {
    flash_set('Заполните email и пароль.');
    redirect($redirectTo);
}

$conn = db();
$stmt = $conn->prepare("SELECT id,email,name,password_hash FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password_hash'])) {
    flash_set('Неверный email или пароль.');
    redirect($redirectTo);
}

$_SESSION['user'] = [
    'id' => (int)$user['id'],
    'email' => (string)$user['email'],
    'name' => (string)$user['name'],
];

flash_set('Вход выполнен.');
redirect($redirectTo);
