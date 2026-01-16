<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/header.php';

$conn = db();
$res = $conn->query("SELECT id,name,category,price,short_desc,image_path,stock FROM products ORDER BY id DESC");
$products = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

$wishSet = wishlist_ids_set();
?>

<section class="section">
    <div class="container">
        <div class="row-between">
            <div>
                <h1>Магазин</h1>
                <p class="muted">Режимы: таблица / карточки. Клик по товару — отдельная страница с характеристиками.</p>
            </div>
            <div class="switch">
                <button class="btn ghost" id="btnTable" type="button">Таблица</button>
                <button class="btn ghost" id="btnCards" type="button">Карточки</button>
            </div>
        </div>

    <!-- Режим Таблица -->
    <div id="viewTable">
        <div class="table-wrap">
            <table class="table">
            <thead>
                <tr>
                <th>Товар</th><th>Цена</th><th>Описание</th><th>Наличие</th><th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <?php $in = isset($wishSet[(int)$p['id']]); ?>
                <tr>
                    <td class="td-prod">
                    <img class="thumb" src="<?= e($p['image_path']) ?>" alt="">
                    <?= e($p['name']) ?>
                    </td>
                    <td>€ <?= number_format((float)$p['price'], 2) ?></td>
                    <td><?= e($p['short_desc']) ?></td>
                    <td><?= (int)$p['stock'] ?> шт.</td>
                    <td class="td-actions">
                    <a class="btn ghost" href="product.php?id=<?= (int)$p['id'] ?>">Подробнее</a>

                    <?php if (is_logged_in()): ?>
                        <button class="btn js-wish"
                                type="button"
                                data-product-id="<?= (int)$p['id'] ?>"
                                data-in="<?= $in ? '1':'0' ?>">
                        <?= $in ? 'Убрать' : 'В список' ?>
                        </button>
                    <?php else: ?>
                        <span class="muted small">Войдите, чтобы добавить</span>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>

    <!-- Режим Карточки -->
    <div id="viewCards" class="hidden">
        <div class="cards">
            <?php foreach ($products as $p): ?>
            <?php $in = isset($wishSet[(int)$p['id']]); ?>
            <div class="card-prod">
                <div class="imgbox">
                <img src="<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>">
                <a class="more" href="product.php?id=<?= (int)$p['id'] ?>">Открыть</a>
                </div>
                <div class="body">
                <div class="title"><?= e($p['name']) ?></div>
                <div class="muted small"><?= e($p['category']) ?> · <?= (int)$p['stock'] ?> шт.</div>
                <div class="muted"><?= e($p['short_desc']) ?></div>

                <div class="bottom">
                    <div class="price">€ <?= number_format((float)$p['price'], 2) ?></div>

                    <?php if (is_logged_in()): ?>
                    <button class="btn js-wish"
                            type="button"
                            data-product-id="<?= (int)$p['id'] ?>"
                            data-in="<?= $in ? '1':'0' ?>">
                        <?= $in ? 'Убрать' : 'В список' ?>
                    </button>
                    <?php else: ?>
                    <span class="muted small">Войдите, чтобы добавить</span>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
