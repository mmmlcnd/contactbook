<?php

namespace App\Http\Controllers;

use App\Models\Login;

class LoginController extends Controller
{
    // ログインフォーム表示
    public function studentLoginForm()
    {
        return view('auth.student_login');
    }

    public function teacherLoginForm()
    {
        return view('auth.teacher_login');
    }

    public function adminLoginForm()
    {
        return view('auth.admin_login');
    }

    // ログイン認証
    public function userLogin($table)
    {
        // Authモデルのインスタンス化
        $loginModel = new Login();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // サーバーへのPOSTリクエストが送られたら
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Postされたデータを取得
            $isLoginAttemptSuccessful = $loginModel->attemptLogin($table, $email, $password);
            if ($isLoginAttemptSuccessful == true) {
                // ★ リダイレクト先を /{$table}/dashboard に戻す
                return redirect()->route($table . '.dashboard');
                // exit;
            } else {
                $error =  'メールアドレスまたはパスワードが間違っています。';
            }
        }
        if ($table === 'students') {
            return view('auth.student_login', compact('error'));
        } else if ($table === 'teachers') {
            return view('auth.teacher_login', compact('error'));
        } else {
            return view('auth.admin_login', compact('error'));
        }
    }

    // 各ユーザー種別でのログイン
    public function studentLogin()
    {
        return $this->userLogin('students');
    }

    public function teacheruserLogin()
    {
        return $this->userLogin('teachers');
    }

    public function adminuserLogin()
    {
        return $this->userLogin('admins');
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
