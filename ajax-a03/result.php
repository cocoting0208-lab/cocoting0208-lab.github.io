<?php
// result.php 改為回傳 JSON，用於前端繪圖與顯示
// 中文註解：前端會依照 labels / totals 來產生 chart.js 圖表
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

$eventId = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
if ($eventId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '請提供有效的 event_id']);
    exit;
}

$event = $pdo->prepare("SELECT * FROM vote_events WHERE id = ?");
$event->execute([$eventId]);
$event = $event->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '找不到此活動']);
    exit;
}

$items = $pdo->prepare("SELECT vote_items.*, COUNT(votes.id) AS total FROM vote_items LEFT JOIN votes ON votes.item_id = vote_items.id WHERE vote_items.event_id=? GROUP BY vote_items.id");
$items->execute([$event['id']]);
$data = $items->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$totals = [];
foreach ($data as $d) {
    $labels[] = $d['name'];
    $totals[] = (int)$d['total'];
}

echo json_encode([
    'success' => true,
    'event' => $event,
    'items' => $data,
    'labels' => $labels,
    'totals' => $totals
], JSON_UNESCAPED_UNICODE);

