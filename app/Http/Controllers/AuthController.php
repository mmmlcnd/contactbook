<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;

class AuthController
{
    // ------------------------------
    // ログイン画面表示
    // ------------------------------
    public function studentLoginForm()
    {
        require __DIR__ . '/../resources/views/auth/student_login.php';
    }

    public function teacherLoginForm()
    {
        require __DIR__ . '/../resources/views/auth/teacher_login.php';
    }

    public function adminLoginForm()
    {
        require __DIR__ . '/../resources/views/auth/admin_login.php';
    }

    // ------------------------------
    // ログイン処理
    // ------------------------------
    public function studentLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $studentModel = new Student();
        $student = $studentModel->attemptLogin($email, $password);

        if ($student) {
            session_start();
            $_SESSION['student_id'] = $student->id;
            header('Location: /students/dashboard');
            exit;
        } else {
            $error = "メールアドレスまたはパスワードが違います。";
            require __DIR__ . '/../resources/views/auth/student_login.php';
        }
    }

    public function teacherLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $teacherModel = new Teacher();
        $teacher = $teacherModel->attemptLogin($email, $password);

        if ($teacher) {
            session_start();
            $_SESSION['teacher_id'] = $teacher->id;
            header('Location: /teachers/dashboard');
            exit;
        } else {
            $error = "メールアドレスまたはパスワードが違います。";
            require __DIR__ . '/../resources/views/auth/teacher_login.php';
        }
    }

    public function adminLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $adminModel = new Admin();
        $admin = $adminModel->attemptLogin($email, $password);

        if ($admin) {
            session_start();
            $_SESSION['admin_id'] = $admin->id;
            header('Location: /admins/dashboard');
            exit;
        } else {
            $error = "メールアドレスまたはパスワードが違います。";
            require __DIR__ . '/../resources/views/auth/admin_login.php';
        }
    }

    // ------------------------------
    // ログアウト処理
    // ------------------------------
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        // URL でどのログイン画面に戻すか判定
        $role = $_GET['role'] ?? 'student';
        switch ($role) {
            case 'teacher':
                header('Location: /login/teacher');
                break;
            case 'admin':
                header('Location: /login/admin');
                break;
            default:
                header('Location: /login/student');
        }
        exit;
    }
}
