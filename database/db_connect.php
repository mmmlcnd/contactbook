<?php
// DB接続情報
$host = 'localhost';           // サーバー名
$dbname = 'contactbook_db';       // データベース名
$user = 'root';                // ユーザー名（XAMPP/MAMPは通常 root）
$pass = '';                    // パスワード（XAMPPは空、MAMPは "root"）

try {
    // PDO接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // エラーを例外として投げる設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続失敗時
    echo "データベース接続に失敗しました: " . $e->getMessage();
    exit;
}
