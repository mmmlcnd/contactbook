<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Entry;
use App\Models\Student;
use Carbon\Carbon;

class TeacherController extends Controller
{
    protected $teacher = null;

    /**
     * コンストラクタ: セッションを開始
     */
    public function __construct()
    {
        // PHPのセッションがまだ開始されていない場合にのみ開始する
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * 認証された教師のIDをセッションから取得
     * * @return int|null
     */
    private function getTeacherId(): ?int
    {
        return session()->get('teacher_id') ?? null;
    }

    /**
     * 認証チェックと教師データの取得
     * @return Teacher|null 認証済み教師オブジェクト
     */
    private function checkAuthAndGetTeacher(): ?Teacher
    {
        $teacherId = $this->getTeacherId();
        if (!$teacherId) {
            return null;
        }

        // Eloquentを使って教師データを取得
        $teacher = Teacher::find($teacherId);

        return $teacher;
    }

    /**
     * 認証チェックとリダイレクト
     * @return Teacher|null 認証済み教師オブジェクト、未認証ならリダイレクトしnullを返す
     */
    private function checkAuth()
    {
        $teacher = $this->checkAuthAndGetTeacher();

        if (!$teacher) {
            return redirect()->route('login.teacher');
        }
        $this->teacher = $teacher;
        return $teacher;
    }

    /**
     * 教師用ダッシュボードを表示
     */
    public function dashboard()
    {
        return view('teachers.teacher_dashboard');
    }
}
