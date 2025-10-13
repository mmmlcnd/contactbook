<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;

class AuthController
{
    // ルーティングが adminLoginForm() を探している場合に備えたラッパーメソッド
    public function adminLoginForm()
    {
        return $this->adminLogin();
    }

    // Admin ログイン処理 (フォーム表示とPOST処理)
    public function adminLogin()
    {
        global $pdo;
        $error = null; // エラーメッセージを初期化

        // データベース接続の存在チェック
        if ($pdo === null) {
            throw new \Exception("Database connection (PDO) is not available.");
        }

        $adminModel = new Admin($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');

            // 自作モデルによる認証ロジックを直接実行 (フレームワークのガードを回避)
            if ($admin = $adminModel->attemptLogin($email, $password)) {

                // 認証成功 (セッション処理)
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $admin->id;
                $_SESSION['user_type'] = 'admin';

                header("Location: /admins/dashboard");
                exit;
            } else {
                // 認証失敗
                $error = 'メールアドレスまたはパスワードが正しくありません。';
            }
        }

        // ビューのレンダリング (view() 関数が Blade を処理)
        return view('auth.admin_login', compact('error'));
    }

    // Teacher ログイン処理
    public function teacherLogin()
    {
        global $pdo;
        $error = null;
        if ($pdo === null) {
            throw new \Exception("Database connection (PDO) is not available.");
        }
        $teacherModel = new Teacher($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');

            if ($teacher = $teacherModel->attemptLogin($email, $password)) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $teacher->id;
                $_SESSION['user_type'] = 'teacher';
                header("Location: /teachers/dashboard");
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが正しくありません。';
            }
        }
        return view('auth.teacher_login', compact('error'));
    }

    // Student ログイン処理
    public function studentLogin()
    {
        global $pdo;
        $error = null;
        if ($pdo === null) {
            throw new \Exception("Database connection (PDO) is not available.");
        }
        $studentModel = new Student($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');

            if ($student = $studentModel->attemptLogin($email, $password)) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $student->id;
                $_SESSION['user_type'] = 'student';
                header("Location: /students/dashboard");
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが正しくありません。';
            }
        }
        return view('auth.student_login', compact('error'));
    }

    // ログアウト処理
    public function logout()
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
