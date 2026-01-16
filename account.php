<?php
require_once __DIR__ . '/helpers.php';

if (!is_logged_in()) {
    flash_set('Личный кабинет доступен после входа.');
    redirect('index.php');
}

$conn = db();
$uid = current_user_id();

/* Отправка обратной связи */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'feedback') {
    $subject = trim((string)($_POST['subject'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($subject === '' || $message === '') {
        flash_set('Заполните тему и сообщение.');
        redirect('account.php');
    }

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $uid, $subject, $message);
    $stmt->execute();

    flash_set('Сообщение отправлено.');
    redirect('account.php');
}

require_once __DIR__ . '/header.php';

/* Список покупок */
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image_path, w.qty
    FROM wishlist_items w
    JOIN products p ON p.id = w.product_id
    WHERE w.user_id = ?
    ORDER BY w.added_at DESC
");
$stmt->bind_param("i", $uid);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<section class="section">
    <div class="container">
        <h1>Личный кабинет</h1>

        <h2>Список покупок</h2>
        <?php if (!$items): ?>
        <div class="card">
            <p>Список пуст. Добавьте товары в “Магазине”.</p>
            <a class="btn" href="store.php">Перейти в магазин</a>
        </div>
        <?php else: ?>
        <div class="list">
            <?php foreach ($items as $it): ?>
            <div class="list-row">
                <img class="thumb" src="<?= e($it['image_path']) ?>" alt="">
                <div class="grow">
                <div><b><?= e($it['name']) ?></b></div>
                <div class="muted small">руб <?= number_format((float)$it['price'], 2) ?> · кол-во: <?= (int)$it['qty'] ?></div>
                </div>
                <button class="btn js-wish" type="button" data-product-id="<?= (int)$it['id'] ?>" data-in="1">Убрать</button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h2>Обратная связь</h2>
        <form class="form" method="post">
        <input type="hidden" name="form" value="feedback">
        <label class="muted small">Тема</label>
        <input class="in wide" name="subject" maxlength="120" required placeholder="Например: вопрос по доставке">

        <label class="muted small">Сообщение</label>
        <textarea class="in wide" name="message" rows="5" required placeholder="Опишите вопрос"></textarea>

        <button class="btn" type="submit">Отправить</button>
        </form>

    </div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>
