<?php

namespace App\Http\Controllers;

// データベース接続（$pdo）はグローバル変数として利用可能であることを前提とする

class AuthController
{
    // 認証ログの出力先設定
    private const LOG_FILE = '/debug_auth.log';

    // 認証ロジックをまとめたプライベートメソッド（PDO, SQL使用）
    protected function attemptLogin(string $table, ?string $email, ?string $password): ?\stdClass
    {
        global $pdo;

        // ログファイルのパスを動的に計算 (プロジェクトルート/debug_auth.log)
        // ログタイプ 3 は、指定されたファイルにメッセージを追加します。
        $logPath = dirname(dirname(dirname(__DIR__))) . self::LOG_FILE;

        if (!$pdo) {
            error_log("DB接続がありません。", 3, $logPath);
            return null;
        }

        // --- ログイン試行のデバッグログ ---
        error_log("\n--- " . date('Y-m-d H:i:s') . " ログイン試行 ---", 3, $logPath);
        error_log("テーブル: " . $table, 3, $logPath);
        error_log("入力されたメール: " . $email, 3, $logPath);
        error_log("入力されたパスワード: " . $password, 3, $logPath);

        if (empty($email) || empty($password)) {
            return null;
        }

        try {
            // メールアドレスでユーザーレコードを取得
            $stmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_OBJ);

            if ($user) {
                error_log("DB取得ユーザーID: " . $user->id, 3, $logPath);
                error_log("DBハッシュ値: " . $user->password, 3, $logPath);

                // パスワードの検証
                if (password_verify($password, $user->password)) {
                    error_log("認証成功！", 3, $logPath);
                    return $user;
                } else {
                    error_log("認証失敗: パスワード不一致。", 3, $logPath);
                }
            } else {
                error_log("認証失敗: ユーザーが見つかりません。", 3, $logPath);
            }
        } catch (\PDOException $e) {
            error_log("データベースエラー: " . $e->getMessage(), 3, $logPath);
        }

        return null;
    }

    // ログインフォーム表示用のラッパーメソッド
    public function studentLoginForm()
    {
        return $this->studentLogin();
    }

    public function teacherLoginForm()
    {
        return $this->teacherLogin();
    }

    public function adminLoginForm()
    {
        return $this->adminLogin();
    }

    // Admin ログイン処理 (フォーム表示とPOST処理)
    public function adminLogin(): string | null
    {
        global $pdo;
        $error = null;

        // 【一時的なデバッグコード開始】
        // 1. パスワード不一致問題を回避するため、強制的に 'admin@test.com' のパスワードを 'password' のハッシュ値に更新する
        // 削除する

        // 【一時的なデバッグコード終了】

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if ($admin = $this->attemptLogin('admins', $email, $password)) {

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $admin->id;
                $_SESSION['user_type'] = 'admin';

                header("Location: /admins/dashboard");
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        // --- view() 関数を使用 ---
        // $error 変数を compact でビューに渡す
        return view('auth.admin_login', compact('error'));
    }

    // Teacher ログイン処理
    public function teacherLogin(): string | null
    {
        global $pdo;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if ($teacher = $this->attemptLogin('teachers', $email, $password)) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $teacher->id;
                $_SESSION['user_type'] = 'teacher';
                header("Location: /teachers/dashboard");
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        // --- view() 関数を使用 ---
        return view('auth.teacher_login', compact('error'));
    }

    // Student ログイン処理
    public function studentLogin(): string | null
    {
        global $pdo;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if ($student = $this->attemptLogin('students', $email, $password)) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $student->id;
                $_SESSION['user_type'] = 'student';
                header("Location: /students/dashboard");
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        // --- view() 関数を使用 ---
        return view('auth.student_login', compact('error'));
    }

    // ログアウト処理
    public function logout(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header("Location: /");
        exit;
    }
}
