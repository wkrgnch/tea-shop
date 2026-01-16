<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/header.php';

$conn = db();
$res = $conn->query("SELECT id,name,price,short_desc,image_path FROM products ORDER BY is_featured DESC, id DESC LIMIT 4");
$items = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>

<section class="hero">
    <div class="container hero-grid">
        <div>
        <h1>TeaCorner — магазин чая</h1>
        <p class="muted" id="about">
            Учебный проект магазина: каталог из базы данных, авторизация, список покупок и обратная связь.
        </p>
        <div class="actions">
            <a class="btn" href="store.php">Открыть магазин</a>
            <a class="btn ghost" href="#overview">Смотреть обзор</a>
        </div>
        </div>

        <div class="slider">
        <div class="slides" id="slides">
            <div class="slide">Зелёные чаи — лёгкий вкус и свежий аромат</div>
            <div class="slide">Чёрные чаи — насыщенный настой для бодрого утра</div>
            <div class="slide">Травяные сборы — без кофеина для спокойного вечера</div>
        </div>
        <div class="dots" id="dots"></div>
        </div>
    </div>
    </section>

    <section class="section" id="overview">
    <div class="container">
        <h2>Обзор продукции</h2>
        <p class="muted">Примеры товаров из базы данных (полный список — в “Магазин”).</p>

        <div class="grid">
        <?php foreach ($items as $p): ?>
            <a class="tile" href="product.php?id=<?= (int)$p['id'] ?>">
            <img src="<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>">
            <div class="tile-body">
                <div class="tile-title"><?= e($p['name']) ?></div>
                <div class="muted"><?= e($p['short_desc']) ?></div>
                <div class="price">€ <?= number_format((float)$p['price'], 2) ?></div>
            </div>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
