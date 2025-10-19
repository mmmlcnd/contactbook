<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use App\Models\AuthModel;

class AuthController extends Controller
{

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
    public function adminLogin()
    {
        // global $pdo;
        $error = null;

        // Authモデルのインスタンス化
        $authModel = new Auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // サーバーへのPOSTリクエストが送られたら
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Postされたデータを取得
            // 変数名変える
            $attemptLogin = $authModel->attemptLogin('admins', $email, $password);

            if ($attemptLogin == true) {
                // ★ リダイレクト先を /admins/dashboard に戻す
                return redirect()->route('admins.dashboard');
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
    public function teacherLogin()
    {
        $error = null;

        // Authモデルのインスタンス化
        $authModel = new Auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // 変数変える
            $attemptLogin = $authModel->attemptLogin('teachers', $email, $password);

            if ($attemptLogin == true) {
                // ★ リダイレクト先を /teachers/dashboard に戻す
                return redirect()->route('teachers.dashboard');
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        // --- view() 関数を使用 ---
        return view('auth.teacher_login', compact('error'));
    }

    // Student ログイン処理
    public function studentLogin()
    {
        $error = null;

        // Authモデルのインスタンス化
        $authModel = new Auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            $isLoginAttemptSuccessful = $authModel->attemptLogin('students', $email, $password);

            if ($isLoginAttemptSuccessful == true) {
                // ★ リダイレクト先を /students/dashboard に戻す
                return redirect()->route('students.dashboard');
                exit;
            } else {
                $error = 'メールアドレスまたはパスワードが間違っています。';
            }
        }

        // --- view() 関数を使用 ---
        return view('auth.student_login', compact('error'));
    }

    // ログアウト処理
    // public function logout(): void
    // {
    //     if (session_status() == PHP_SESSION_NONE) {
    //         session_start();
    //     }
    //     $_SESSION = array();
    //     if (ini_get("session.use_cookies")) {
    //         $params = session_get_cookie_params();
    //         setcookie(
    //             session_name(),
    //             '',
    //             time() - 42000,
    //             $params["path"],
    //             $params["domain"],
    //             $params["secure"],
    //             $params["httponly"]
    //         );
    //     }
    //     session_destroy();
    //     header("Location: /");
    //     exit;
    // }
}
