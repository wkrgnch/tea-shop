<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/header.php';
?>
<section class="section">
    <div class="container">
        <h1>О нас</h1>
        <p class="muted">
        TeaCorner — учебный магазин. Здесь показано: верстка, работа с БД через mysqli,
        авторизация, карточки и таблица товаров, отдельная страница товара, список покупок и обратная связь.
        </p>

        <div class="mini-cards">
        <div class="card"><b>База данных</b><p class="muted">Товары и пользователи хранятся в MySQL.</p></div>
        <div class="card"><b>Интерфейс</b><p class="muted">Navbar, якоря, адаптивный дизайн.</p></div>
        <div class="card"><b>Функции</b><p class="muted">Wishlist и форма обратной связи после входа.</p></div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>
