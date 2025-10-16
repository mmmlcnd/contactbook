<?php

namespace App\Http\Controllers;

use PDO;
use PDOException;

// データベース接続情報 (環境に合わせて変更してください)
// ※この環境では、DB接続情報をハードコードしています。
define('DB_HOST', 'localhost');
define('DB_NAME', 'contactbook_db');
define('DB_USER', 'root');
define('DB_PASS', '');

class Controller
{
    /**
     * @var PDO|null データベース接続インスタンス
     */
    protected static ?PDO $pdoInstance = null;

    /**
     * ビューファイルを描画する
     * @param string $viewName ビューファイル名 (例: 'students/login')
     * @param array $data ビューに渡すデータ
     */
    protected function view(string $viewName, array $data = []): string
    {
        extract($data);
        ob_start();
        $filePath = __DIR__ . "/../../../resources/views/{$viewName}.blade.php";

        // ファイルが存在しない場合はエラーを出す
        if (!file_exists($filePath)) {
            throw new \Exception("View file not found: {$filePath}");
        }

        // テンプレートのインクルード
        include $filePath;

        return ob_get_clean();
    }

    /**
     * データベース接続 (PDO) インスタンスを取得する
     * シングルトンパターンを使用して、接続は一度だけ行う
     * @return PDO
     * @throws PDOException 接続に失敗した場合
     */
    protected function getPdo(): PDO
    {
        if (self::$pdoInstance === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
                self::$pdoInstance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                error_log("DB Connection Failed: " . $e->getMessage());
                // ユーザーには一般的なエラーメッセージを表示
                throw new PDOException("データベース接続に失敗しました。", (int)$e->getCode());
            }
        }
        return self::$pdoInstance;
    }
}
