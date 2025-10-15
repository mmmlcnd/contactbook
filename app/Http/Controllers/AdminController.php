<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Entry;
use PDO;
use Exception;

class AdminController extends Controller
{
    // ダッシュボード画面表示
    public function dashboard()
    {
        return view('admins.admin_dashboard');
    }

    // クラス管理画面表示
    // public function createUser1()
    // {
    //     return view('admins.admin_create_user');
    // }

    public function showUserManagement(Request $request)
    {
        global $pdo;

        $title = 'ユーザー管理';
        $userType = $request->input('type', 'student'); // デフォルトは生徒

        return view('admins.admin_create_user', compact('title', 'userType'));
    }

    /**
     * 新しいユーザーをデータベースに登録する。
     *
     * @param Request $request
     * @return string
     */
    public function createUser(Request $request)
    {
        global $pdo;

        // 共通の入力値を取得
        $userType = $request->input('user_type');
        $email = $request->input('email');
        $password = $request->input('password');
        $name = $request->input('name'); // 教師・生徒用
        $grade = $request->input('grade'); // 生徒用: gakunenからgradeに変更
        $classId = $request->input('class_id'); // 生徒用

        // バリデーション
        if (empty($email) || empty($password)) {
            return redirect()->to('/admins/users')->with('error', 'メールアドレスとパスワードは必須です。');
        }

        // パスワードのハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            switch ($userType) {
                case 'student':
                    // 生徒登録
                    // バリデーション: $gakunenから$gradeに変更
                    if (empty($name) || empty($grade) || empty($classId)) {
                        return redirect()->to('/admins/users')->with('error', '生徒の氏名、学年 (Grade)、クラスIDは必須です。');
                    }

                    // SQLクエリ: gakunenカラムをgradeカラムに変更
                    $stmt = $pdo->prepare("INSERT INTO students (email, password, name, grade, class_id) VALUES (?, ?, ?, ?, ?)");
                    // 実行パラメータ: $gakunenを$gradeに変更
                    $stmt->execute([$email, $hashedPassword, $name, $grade, $classId]);
                    $message = '生徒ユーザー（' . htmlspecialchars($name) . '）が登録されました。';
                    break;

                case 'teacher':
                    // 教師登録
                    if (empty($name)) {
                        return redirect()->to('/admins/users')->with('error', '教師の氏名は必須です。');
                    }

                    $stmt = $pdo->prepare("INSERT INTO teachers (email, password, name) VALUES (?, ?, ?)");
                    $stmt->execute([$email, $hashedPassword, $name]);
                    $message = '教師ユーザー（' . htmlspecialchars($name) . '）が登録されました。';
                    break;

                case 'admin':
                    // 管理者登録 (nameはオプション)
                    $stmt = $pdo->prepare("INSERT INTO admins (email, password, name) VALUES (?, ?, ?)");
                    $stmt->execute([$email, $hashedPassword, $name]);
                    $message = '管理者ユーザー（' . htmlspecialchars($email) . '）が登録されました。';
                    break;

                default:
                    return redirect()->to('/admins/users')->with('error', '無効なユーザー種別です。');
            }
        } catch (Exception $e) {
            // エラーロギングとユーザーフレンドリーなメッセージ
            error_log("Database Error in createUser: " . $e->getMessage());
            return redirect()->to('/admins/users')->with('error', 'ユーザー登録中にデータベースエラーが発生しました。');
        }

        return redirect()->to('/admins/users')->with('success', $message);
    }

    // クラス管理画面表示
    public function manageClasses()
    {
        return view('admins.admin_manage_classes');
    }

    // テスト
    // public function nameClass()
    // {
    //     $adminname = "s";
    //     return view('admins.admin_create_user', compact('adminname'));
    // }
}
