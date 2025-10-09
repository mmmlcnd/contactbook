<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ----------------------
    // ログインフォーム表示
    // ----------------------

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

    // -----------------------------
    // 生徒ログイン
    // -----------------------------

    public function studentLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // role が student のレコードだけ認証
        if (Auth::guard('student')->attempt(array_merge($credentials, ['role' => 'student']))) {
            $request->session()->regenerate();
            return redirect()->route('students.dashboard');
        }

        return back()->withErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);
    }

    // -----------------------------
    // 教師ログイン
    // -----------------------------

    public function teacherLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // role が teacher のレコードだけ認証
        if (Auth::guard('teacher')->attempt(array_merge($credentials, ['role' => 'teacher']))) {
            $request->session()->regenerate();
            return redirect()->route('teachers.dashboard');
        }

        return back()->withErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);
    }

    // -----------------------------
    // 管理者ログイン
    // -----------------------------

    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // role が admin のレコードだけ認証
        if (Auth::guard('admin')->attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admins.dashboard');
        }

        return back()->withErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);
    }

    // -----------------------------
    // ログアウト
    // -----------------------------
    public function logout(Request $request, $role)
    {
        Auth::guard($role)->logout();

        // セッションを再生成してセキュリティ対策
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ロールに応じたログインページにリダイレクト
        return redirect()->route('login.' . $role);
    }
}
