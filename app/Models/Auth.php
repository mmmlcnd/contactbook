<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $fillable = [
        'email',
        'password'
    ];

    // 認証ログの出力先設定
    private const LOG_FILE = '/debug_auth.log';

    public function attemptLogin(string $table, ?string $email, ?string $password)
    {
        global $pdo;

        // ログファイルのパスを動的に計算 (プロジェクトルート/debug_auth.log)
        // ログタイプ 3 は、指定されたファイルにメッセージを追加します。
        $logPath = dirname(dirname(dirname(__DIR__))) . self::LOG_FILE;

        if (!$pdo) {
            error_log("DB接続がありません。", 3, $logPath);
            return null;
        }

        if (empty($email) || empty($password)) {
            return null;
        }

        try {
            // メールアドレスでユーザーレコードを取得
            $stmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_OBJ);

            // ユーザーが見つからない場合は失敗
            if (!$user) {
                return false;
            }

            if ($user) {
                error_log("DB取得ユーザーID: " . $user->id, 3, $logPath);
                error_log("DBハッシュ値: " . $user->password, 3, $logPath);

                // パスワードの検証
                if (password_verify($password, $user->password)) {
                    error_log("認証成功！", 3, $logPath);
                    return $user;
                } else {
                    error_log("認証失敗: パスワード不一致。", 3, $logPath);
                    return false;
                }
            } else {
                error_log("認証失敗: ユーザーが見つかりません。", 3, $logPath);
                return false;
            }
        } catch (\PDOException $e) {
            // データベースエラーが発生した場合
            // デバッグログではなく、システムの標準エラーログに出力
            error_log("データベースエラー: " . $e->getMessage(), 3, $logPath);
        }

        return null; // 認証失敗
    }
}
