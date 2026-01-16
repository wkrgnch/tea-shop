<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { flash_set('Некорректный товар.'); redirect('store.php'); }

$conn = db();
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();

if (!$p) { flash_set('Товар не найден.'); redirect('store.php'); }

$specs = json_decode((string)$p['specs_json'], true);
if (!is_array($specs)) $specs = [];

$wishSet = wishlist_ids_set();
$in = isset($wishSet[(int)$p['id']]);
?>

<section class="section">
    <div class="container">
        <div class="crumb">
        <a href="store.php">Магазин</a> <span>→</span> <span><?= e($p['name']) ?></span>
        </div>

        <div class="product">
            <div class="pimg">
                <img src="<?= e($p['image_path']) ?>" alt="<?= e($p['name']) ?>">
            </div>

            <div class="pinfo">
                <h1><?= e($p['name']) ?></h1>
                <div class="muted"><?= e($p['category']) ?></div>
                <div class="pprice">руб <?= number_format((float)$p['price'], 2) ?></div>
                <div class="muted">В наличии: <b><?= (int)$p['stock'] ?></b> шт.</div>

                <h3>Характеристики</h3>
                <ul class="specs">
                <?php foreach ($specs as $k => $v): ?>
                    <li><b><?= e($k) ?>:</b> <?= e($v) ?></li>
                <?php endforeach; ?>
                </ul>

                <h3>Описание</h3>
                <p class="muted"><?= e($p['full_desc']) ?></p>

                <div class="actions">
                <?php if (is_logged_in()): ?>
                    <button class="btn js-wish"
                            type="button"
                            data-product-id="<?= (int)$p['id'] ?>"
                            data-in="<?= $in ? '1':'0' ?>">
                    <?= $in ? 'Убрать из списка' : 'Добавить в список' ?>
                    </button>
                    <a class="btn ghost" href="account.php">Мой список</a>
                <?php else: ?>
                    <div class="card">
                    <b>Чтобы добавлять товары</b>
                    <p class="muted">Войдите через форму в шапке.</p>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
