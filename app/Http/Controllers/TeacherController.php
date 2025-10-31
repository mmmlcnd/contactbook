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

    /**
     * 担当クラスの生徒と最新の提出状況を一覧表示する
     */
    public function showSubmissionList()
    {
        $teacher = $this->checkAuth();
        if (!$teacher) return; // checkAuth内でリダイレクト済み

        // デバッグ用：取得された値を確認
        dd("Grade:", $teacher->grade, "Class Name:", $teacher->class_name);

        // 教師データに担当クラス情報がない場合はエラー処理
        if (empty($teacher->grade) || empty($teacher->class_name)) {
            return "エラー: 担当する学年または組が設定されていません。管理者にご確認ください。";
        }

        // 提出状況の確認対象日を設定
        $today = Carbon::today();
        $targetRecordDate = '';

        if ($today->isMonday()) {
            // 今日が月曜日なら、確認対象日は3日前（先週の金曜日）
            $targetRecordDate = $today->subDays(3)->format('Y-m-d');
        } else {
            $targetRecordDate = $today->yesterday()->format('Y-m-d');
        }

        $loggedInUserId = $teacher->id;
        $targetGrade = $teacher->grade;
        $targetClassName = $teacher->class_name;

        try {
            $studentStatuses = $teacher->getStudentsWithLatestEntryStatus($targetRecordDate, $loggedInUserId, $targetGrade, $targetClassName);
        } catch (\Exception $e) {
            error_log("Submission list fetch error: " . $e->getMessage());
            return "データベースエラーが発生しました。生徒の提出状況が取得できませんでした。";
        }

        $teacherName = $teacher->name;
        $students = $studentStatuses;

        $viewName = 'teachers.teacher_status';
        return view($viewName, compact('teacherName', 'students'));
    }

    /**
     * 特定のエントリーの詳細を表示し、既読・スタンプ処理のためのデータを提供する
     * @param int $id entry_id
     */
    public function readEntry(int $id)
    {
        $teacher = $this->checkAuth();
        if (!$teacher) return; // checkAuth内でリダイレクト済み

        $loggedInUserId = $teacher->id; // 不要だが、元のコードの慣習に合わせて残す

        try {
            // モデルに処理を委譲: Eloquent/DBファサードを使用してデータを取得
            $data = Teacher::getEntryDetailsForRead($id);

            if (!$data) {
                return "エラー: 該当するエントリーが見つかりません。";
            }

            $entry = $data['entry'];
            $readHistory = $data['readHistory'];
            $stamps = $data['stamps'];

            // 4. 教師自身が既にスタンプを押しているかチェック (コレクション操作でチェック)
            $currentTeacherStamped = false;
            foreach ($readHistory as $history) {
                if ($history->teacher_name == $teacher->name) {
                    $currentTeacherStamped = true;
                    break;
                }
            }

            return view('teachers.teacher_read_entry', compact('teacher', 'entry', 'readHistory', 'stamps', 'currentTeacherStamped'));
        } catch (\Exception $e) {
            error_log("readEntry DB Error: " . $e->getMessage());
            return "データベースエラーが発生しました。";
        }
    }

    /**
     * エントリーにスタンプを押し、既読履歴として保存する
     * @param int $id entry_id
     * @param Illuminate\Http\Request $request POSTリクエスト
     */
    public function stampEntry(Request $request, int $id)
    {
        $teacher = $this->checkAuth();
        if (!$teacher) return; // checkAuth内でリダイレクト済み

        $loggedInUserId = $teacher->id;
        $stampId = $request->get('stamp_id');

        $redirectRoute = 'teachers.read'; // ルート名が teachers/read/{id}★要確認

        if (!is_numeric($stampId) || (int)$stampId <= 0) {
            // エラー処理（スタンプIDが無効）
            $_SESSION['error_message'] = "無効なスタンプが選択されました。";
            // 元のコードに合わせて、生リダイレクトとexitを使用
            return redirect()->route($redirectRoute, ['id' => $id])->with('error_message', "無効なスタンプが選択されました。");
            exit;
        }

        //トランザクション処理を含むDB操作
        if (Teacher::stampEntry($id, $loggedInUserId, (int)$stampId)) {
            return redirect()->route($redirectRoute, ['id' => $id])->with('success_message', "スタンプが正常に押印されました。");
        } else {
            return redirect()->route($redirectRoute, ['id' => $id])->with('error_message', "スタンプ押印中にエラーが発生しました。");
        }
    }
}
