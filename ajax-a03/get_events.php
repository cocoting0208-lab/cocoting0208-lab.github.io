<?php
// 回傳所有活動與其項目的 JSON API
// 中文註解：此檔案改為 API 回傳 JSON，前端負責渲染
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $events = $pdo->query("SELECT * FROM vote_events ORDER BY id DESC")
                  ->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($events as $e) {
        // 取得該活動的所有項目與票數
        $itemsStmt = $pdo->prepare("SELECT vote_items.*, COUNT(votes.id) AS total FROM vote_items LEFT JOIN votes ON votes.item_id = vote_items.id WHERE vote_items.event_id=? GROUP BY vote_items.id");
        $itemsStmt->execute([$e['id']]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        $e['items'] = $items;
        $out[] = $e;
    }

    echo json_encode(['events' => $out], JSON_UNESCAPED_UNICODE);
} catch (PDOException $ex) {
    http_response_code(500);
    echo json_encode(['error' => $ex->getMessage()]);
}

// 不輸出任何 HTML
