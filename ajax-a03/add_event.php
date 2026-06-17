<?php
// 新增投票活動，改為回傳 JSON，前端會解析回應
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "INSERT INTO vote_events(title,creator,description) VALUES(?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['title'] ?? '',
        $_POST['creator'] ?? '',
        $_POST['description'] ?? ''
    ]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}