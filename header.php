<?php
require_once __DIR__ . '/helpers.php';

$BASE = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
if ($BASE === '/') $BASE = '';

$uri = $_SERVER['REQUEST_URI'] ?? 'index.php';
$msg = flash_get();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TeaCorner — магазин чая</title>

    <base href="<?= e($BASE) ?>/">

    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/app.js"></script>
</head>
<body>

<header class="header">
    <div class="container head-row">

        <a class="logo" href="index.php">
        <img src="assets/img/logo.svg" alt="Логотип" width="26" height="26">
        <span>TeaCorner</span>
        </a>

        <nav class="nav">
        <a href="index.php" class="<?= strpos($uri, 'index.php') !== false ? 'active' : '' ?>">Главная</a>
        <a href="store.php" class="<?= strpos($uri, 'store.php') !== false ? 'active' : '' ?>">Магазин</a>
        <a href="about.php" class="<?= strpos($uri, 'about.php') !== false ? 'active' : '' ?>">О нас</a>

        <a href="index.php#about">О магазине</a>
        <a href="index.php#overview">Обзор</a>
        <a href="index.php#contacts">Контакты</a>

        <?php if (!is_logged_in()): ?>
            
            <a href="register.php" class="<?= strpos($uri, 'register.php') !== false ? 'active' : '' ?>">Регистрация</a>
        <?php else: ?>
            <a href="account.php" class="<?= strpos($uri, 'account.php') !== false ? 'active' : '' ?>">ЛК</a>
        <?php endif; ?>
        </nav>

        
        <div class="auth">
        <?php if (!is_logged_in()): ?>
            <form class="login login-inline" action="login.php" method="post">
            <input type="hidden" name="redirect_to" value="<?= e($uri) ?>">
            <input class="in" name="email" type="email" placeholder="email" required>
            <input class="in" name="password" type="password" placeholder="пароль" required>
            <button class="btn" type="submit">Войти</button>
            </form>
        <?php else: ?>
            <div class="userbox">
            <span class="muted">Привет, <?= e(current_user_name()) ?></span>
            <a class="btn ghost" href="account.php">
                Список <span id="wishCount" class="pill"><?= wishlist_count() ?></span>
            </a>
            <a class="btn" href="logout.php">Выйти</a>
            </div>
        <?php endif; ?>
        </div>

    </div>

    <?php if ($msg): ?>
        <div class="container">
        <div class="flash"><?= e($msg) ?></div>
        </div>
    <?php endif; ?>
</header>

<main class="main">
