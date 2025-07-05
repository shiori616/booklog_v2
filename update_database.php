<?php
//エラー表示
ini_set("display_errors", 1);

// JSONレスポンス用のヘッダー設定
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    // DB接続
    $pdo = new PDO('mysql:dbname=gs_booklog;charset=utf8;host=localhost','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // commentカラムが存在するかチェック
    $stmt = $pdo->prepare("SHOW COLUMNS FROM book_clicks LIKE 'comment'");
    $stmt->execute();
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        // commentカラムを追加
        $alterTable = "ALTER TABLE book_clicks ADD COLUMN comment TEXT AFTER click_datetime";
        $pdo->exec($alterTable);
        
        echo json_encode([
            'success' => true,
            'message' => 'commentカラムを追加しました',
            'action' => 'column_added'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'commentカラムは既に存在します',
            'action' => 'column_exists'
        ]);
    }
    
    // テーブル構造を確認
    $stmt = $pdo->prepare("DESCRIBE book_clicks");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'データベース更新完了',
        'columns' => $columns
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error: ' . $e->getMessage()
    ]);
}
?>
