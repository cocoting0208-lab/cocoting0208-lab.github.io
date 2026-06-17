<?php
// 新增投票項目（含上傳），回傳 JSON 給前端
require 'db.php';

header('Content-Type: application/json; charset=utf-8');

$image = '';

// 簡單的檔名安全處理與目錄建立
if (!empty($_FILES['image']['name'])) {
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // 只保留副檔名，使用隨機檔名以降低衝突與注入風險
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $safeName = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
    $image = 'uploads/' . $safeName;

    move_uploaded_file($_FILES['image']['tmp_name'], $image);
}

try {
    $sql = "INSERT INTO vote_items(event_id,name,description,image) VALUES(?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['event_id'] ?? 0,
        $_POST['name'] ?? '',
        $_POST['description'] ?? '',
        $image
    ]);

    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'image' => $image], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}