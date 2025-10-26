<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use Exception;

class AdminController extends Controller
{
    // ダッシュボード画面表示
    public function dashboard()
    {
        return view('admins.admin_dashboard');
    }

    public function create(Request $request)
    {
        $title = 'ユーザー管理';
        $userType = $request->input('type', 'admin');

        $classesModel = new Classes();

        try {
            $classes = $classesModel->getAllOrderedClasses();
        } catch (Exception $e) {
            error_log("Failed to fetch classes: " . $e->getMessage());
            $classes = []; // 失敗した場合は空の配列を渡す
            // $_SESSION['error'] = 'クラス一覧の取得に失敗しました。';
            session()->flash('error', 'クラス一覧の取得に失敗しました。');
        }

        // 取得したクラスデータをビューに渡す
        return view('admins.admin_create_user', compact('title', 'userType', 'classes'));
    }

    /**
     * 新しいユーザーをデータベースに登録する。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 認証チェック
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            return redirect()->to('/login/admins')->with('error', '管理者としてログインしてください。');
        }

        // 共通の入力値を取得
        $userType = $request->input('user_type');
        $email = $request->input('email');
        $password = $request->input('password');
        $name = $request->input('name');
        $kana = $request->input('kana');
        $grade = $request->input('grade'); // 学年 (生徒・教師用)
        $classId = $request->input('class_id'); // フォームからはclassIdのみを受け取る

        // 共通バリデーション: email, password, userType, name, kana を全て必須とする
        if (empty($email) || empty($password) || empty($userType) || empty($kana) || empty($name)) {
            return $this->redirectBackWithUserType($userType, 'メールアドレス、パスワード、氏名、氏名（カナ）、ユーザー種別は必須です。');
        }

        // パスワードのハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $classesModel = new Classes();

        try {
            // 教師・生徒登録の場合、クラスIDの検証とクラス情報（学年・クラス名）の取得を行う
            if ($userType === 'student' || $userType === 'teacher') {

                $classData = $classesModel->getGradesAndNames($classId);

                // 取得した学年とクラス名を変数に格納
                $grade = $classData['grade'];
                // classes.name が 'A組' や 'B組' の値
                $className = $classData['name'];
            }
            switch ($userType) {
                case 'student':

                    Student::createStudent($email, $hashedPassword, $name, $kana, $grade, $className, 'write');

                    $message = '生徒ユーザー（' . htmlspecialchars($name) . '）が登録されました。';
                    break;

                case 'teacher':

                    Teacher::createTeacher($email, $hashedPassword, $name, $kana, $grade, $className, 'read');

                    $message = '教師ユーザー（' . htmlspecialchars($name) . '）が登録されました。';
                    break;

                case 'admin':

                    Admin::createAdmin($email, $hashedPassword, $name, $kana);

                    $message = '管理者ユーザー（' . htmlspecialchars($name) . '）が登録されました。';
                    break;

                default:
                    return $this->redirectBackWithUserType($userType, '無効なユーザー種別です。');
            }
        } catch (Exception $e) {

            // エラーメッセージを表示
            return $this->redirectBackWithUserType($userType, 'ユーザー登録中にデータベースエラーが発生しました。エラーコード: ' . $e->getCode() . ' 詳細: ' . $e->getMessage());
        }

        // 成功メッセージをセッションにFlashし、リダイレクト
        $_SESSION['success'] = $message;
        return redirect()->route('admins.create')->with('success', $message);
    }

    /**
     * エラー発生時、ユーザータイプを保持してフォームに戻る
     */
    private function redirectBackWithUserType(string $userType, string $errorMessage)
    {
        // userTypeをセッションに一時保存
        $_SESSION['user_type_temp'] = $userType;

        // フレームワークのリダイレクト機能を使用してエラーメッセージをフラッシュ
        // $_SESSION['error'] = $errorMessage;
        return redirect()->route('admins.create')->with('error', $errorMessage);
    }

    // クラス管理画面表示
    // public function manageClasses()
    // {
    //     return view('admins.admin_manage_classes');
    // }
}
