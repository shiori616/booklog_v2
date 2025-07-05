<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// 共通設定ファイルを読み込み
require_once 'config/database.php';

// DB接続
try {
    $database = new Database();
    $pdo = $database->getConnection();
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DBConnection Error:'.$e->getMessage()]);
    exit;
}

// ステータスでフィルタリング（read または tsundoku）
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;

// デバッグ情報
error_log("get_books.php called with status: " . ($status_filter ?? 'null'));

try {
    if ($status_filter && in_array($status_filter, ['read', 'tsundoku'])) {
        // 特定のステータスの書籍を取得
        $stmt = $pdo->prepare("SELECT * FROM book_clicks WHERE button_type = ? ORDER BY created_at DESC");
        $stmt->execute([$status_filter]);
        error_log("Filtering by status: " . $status_filter);
    } else {
        // すべての書籍を取得
        $stmt = $pdo->prepare("SELECT * FROM book_clicks ORDER BY created_at DESC");
        $stmt->execute();
        error_log("Getting all books");
    }
    
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Found " . count($books) . " books");
    
    // industry_identifiersをJSONデコード
    foreach ($books as &$book) {
        if (isset($book['industry_identifiers'])) {
            $book['industry_identifiers'] = json_decode($book['industry_identifiers'], true);
        }
    }
    
    echo json_encode([
        'success' => true,
        'books' => $books,
        'count' => count($books),
        'filter' => $status_filter,
        'debug' => [
            'sql_executed' => $status_filter ? "SELECT * FROM book_clicks WHERE button_type = '$status_filter' ORDER BY created_at DESC" : "SELECT * FROM book_clicks ORDER BY created_at DESC"
        ]
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    error_log("Database error in get_books.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
