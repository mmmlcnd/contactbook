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
        // インスタンス化
        $loginModel = new Login();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // サーバーへのPOSTリクエストが送られたら
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Postされたデータを取得
            $user = $loginModel->attemptLogin($table, $email, $password);

            if ($user) { //認証成功
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;

                if ($table === 'students') {
                    $_SESSION['user_type'] = 'student';
                } else if ($table === 'teachers') {
                    $_SESSION['user_type'] = 'teacher';
                } else {
                    $_SESSION['user_type'] = 'admin';
                }

                // ★ リダイレクト先を /{$table}/dashboard に戻す
                return redirect()->route($table . '.dashboard');
            } else {
                $error =  'メールアドレスまたはパスワードが間違っています。';

                if ($table === 'students') {
                    return view('auth.student_login', compact('error'));
                } else if ($table === 'teachers') {
                    return view('auth.teacher_login', compact('error'));
                } else {
                    return view('auth.admin_login', compact('error'));
                }
            }
        }
    }

    // 各ユーザー種別でのログイン
    public function studentLogin()
    {
        return $this->userLogin('students');
    }

    public function teacherLogin()
    {
        return $this->userLogin('teachers');
    }

    public function adminLogin()
    {
        return $this->userLogin('admins');
    }
}
