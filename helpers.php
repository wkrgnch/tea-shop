<?php

require_once __DIR__ . '/config.php';

function e($s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_logged_in(): bool {
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function current_user_id(): ?int {
    return is_logged_in() ? (int)$_SESSION['user']['id'] : null;
}

function current_user_name(): ?string {
    return is_logged_in() ? (string)$_SESSION['user']['name'] : null;
}

function redirect(string $to): void {
    header('Location: ' . $to);
    exit;
}

function flash_set(string $msg): void {
    $_SESSION['flash'] = $msg;
}

function flash_get(): ?string {
    if (!isset($_SESSION['flash'])) return null;
    $m = (string)$_SESSION['flash'];
    unset($_SESSION['flash']);
    return $m;
}

function wishlist_count(): int {
    if (!is_logged_in()) return 0;
    $conn = db();
    $uid = current_user_id();

    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM wishlist_items WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return (int)($row['c'] ?? 0);
}

function wishlist_ids_set(): array {
    if (!is_logged_in()) return [];
    $conn = db();
    $uid = current_user_id();

    $stmt = $conn->prepare("SELECT product_id FROM wishlist_items WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();

    $set = [];
    while ($r = $res->fetch_assoc()) {
        $set[(int)$r['product_id']] = true;
    }
    return $set;
}
