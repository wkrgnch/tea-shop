<?php
require_once __DIR__ . '/../helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (!is_logged_in()) {
    echo json_encode(['ok'=>false,'error'=>'auth_required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok'=>false,'error'=>'bad_method']);
    exit;
}

$productId = (int)($_POST['product_id'] ?? 0);
if ($productId <= 0) {
    echo json_encode(['ok'=>false,'error'=>'bad_product']);
    exit;
}

$conn = db();
$uid = current_user_id();

$check = $conn->prepare("SELECT 1 FROM wishlist_items WHERE user_id = ? AND product_id = ? LIMIT 1");
$check->bind_param("ii", $uid, $productId);
$check->execute();
$exists = (bool)$check->get_result()->fetch_assoc();

if ($exists) {
    $del = $conn->prepare("DELETE FROM wishlist_items WHERE user_id = ? AND product_id = ?");
    $del->bind_param("ii", $uid, $productId);
    $del->execute();
    $inList = false;
} else {
    $ins = $conn->prepare("INSERT INTO wishlist_items (user_id, product_id, qty) VALUES (?, ?, 1)");
    $ins->bind_param("ii", $uid, $productId);
    $ins->execute();
    $inList = true;
}

echo json_encode([
    'ok' => true,
    'inList' => $inList,
    'count' => wishlist_count()
]);
