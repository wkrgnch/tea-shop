<?php
session_start();
date_default_timezone_set('Europe/Amsterdam');

define('DB_HOST', 'sql105.ezyro.com');                
define('DB_NAME', 'ezyro_40918669_teashop');    
define('DB_USER', 'ezyro_40918669');       
define('DB_PASS', '327e8311a7785'); 
define('DB_PORT', 3306);            

function db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_errno) {
        die('Ошибка подключения к БД: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
