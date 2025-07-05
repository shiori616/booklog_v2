<?php
//エラー表示
ini_set("display_errors", 1);

// 共通設定ファイルを読み込み
require_once 'config/database.php';

// JSONレスポンス用のヘッダー設定
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // データベース接続
    $database = new Database();
    $pdo = $database->getConnection();
    
    $actions = [];
    
    // commentカラムが存在するかチェック
    $stmt = $pdo->prepare("SHOW COLUMNS FROM book_clicks LIKE 'comment'");
    $stmt->execute();
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        // commentカラムを追加
        $alterTable = "ALTER TABLE book_clicks ADD COLUMN comment TEXT AFTER click_datetime";
        $pdo->exec($alterTable);
        $actions[] = 'commentカラムを追加しました';
    } else {
        $actions[] = 'commentカラムは既に存在します';
    }
    
    // テーブル構造を再確認
    $stmt = $pdo->prepare("DESCRIBE book_clicks");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // データ件数を確認
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM book_clicks");
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // button_type別の件数を確認
    $stmt = $pdo->prepare("SELECT button_type, COUNT(*) as count FROM book_clicks GROUP BY button_type");
    $stmt->execute();
    $buttonTypeCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'actions' => $actions,
        'table_structure' => $columns,
        'total_count' => $count['count'],
        'button_type_counts' => $buttonTypeCounts,
        'comment_column_exists' => in_array('comment', array_column($columns, 'Field'))
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
