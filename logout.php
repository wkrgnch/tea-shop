<?php
require_once __DIR__ . '/helpers.php';
unset($_SESSION['user']);
flash_set('Вы вышли из аккаунта.');
redirect('index.php');
