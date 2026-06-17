<?php
// 投票 API，改為回傳 JSON，並處理重複投票的錯誤回應
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "INSERT INTO votes(event_id,item_id,voter_name) VALUES(?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['event_id'] ?? 0,
        $_POST['item_id'] ?? 0,
        $_POST['voter_name'] ?? ''
    ]);

    echo json_encode(['success' => true, 'message' => '投票成功'], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // 若為唯一鍵（重複投票）錯誤，回傳409 Conflict
    $code = $e->getCode();
    if ($code === '23000' || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => '同活動不可重複投票'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}