<?php
$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=ajax;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);