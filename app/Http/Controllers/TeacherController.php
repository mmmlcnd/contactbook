<?php

namespace App\Http\Controllers;

class TeacherController extends Controller
{
    /**
     * セッションIDに基づいて教師情報をデータベースから取得する
     * @param ?int $userId セッションに保存されているユーザーID (null許容)
     * @return object|null 教師データ（stdClassオブジェクト）
     */
    protected function getTeacherData(?int $userId): ?\stdClass
    {
        global $pdo;

        if (!$pdo || $userId === null) {
            return null;
        }

        try {
            // teachersテーブルからIDでユーザーレコードを取得
            $stmt = $pdo->prepare("SELECT * FROM `teachers` WHERE id = :id");
            $stmt->execute([':id' => $userId]);
            $teacher = $stmt->fetch(\PDO::FETCH_OBJ);

            return $teacher ?: null;
        } catch (\PDOException $e) {
            error_log("TeacherController DB Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 教師のダッシュボード画面を表示する
     */
    public function dashboard()
    {
        $loggedInUserId = $_SESSION['user_id'] ?? null;
        $teacher = $this->getTeacherData($loggedInUserId);

        if (!$teacher) {
            // 認証されていない場合はログインへリダイレクト
            header("Location: /teachers/login");
            exit;
        }

        // シンプルにteacher_dashboardビューを返す
        return view('teachers.teacher_dashboard', compact('teacher'));
    }

    /**
     * 担当クラスの生徒と最新の提出状況を一覧表示する
     */
    public function showSubmissionList()
    {
        global $pdo;
        $loggedInUserId = $_SESSION['user_id'] ?? null;
        $teacher = $this->getTeacherData($loggedInUserId);

        // 認証チェック
        if (!$teacher) {
            header("Location: /teachers/login");
            exit;
        }

        // 教師データに担当クラス情報がない場合はエラー処理（teachersテーブルの'class'カラムを参照）
        if (empty($teacher->class)) {
            return "エラー: 担当クラスが設定されていません。";
        }

        // --- 1. 担当クラスの生徒を取得 ---
        $students = [];
        try {
            // studentsテーブルの'class'カラムと教師データの'class'カラムでフィルタリング
            $stmt = $pdo->prepare("SELECT id, name FROM students WHERE class = :class_name ORDER BY id ASC");
            $stmt->execute([':class_name' => $teacher->class]);
            $students = $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            error_log("Students fetch error: " . $e->getMessage());
            $students = [];
        }

        // --- 2. 各生徒の最新の連絡帳 (Entry) 情報を取得しマージ ---
        $studentStatuses = [];
        foreach ($students as $student) {
            $status = new \stdClass();
            $status->id = $student->id;
            $status->name = $student->name;
            $status->latest_entry = null;

            try {
                // 生徒ごとの最新のエントリを取得
                $stmt = $pdo->prepare("
                    SELECT
                        id, entry_date, content, is_read
                    FROM
                        entries
                    WHERE
                        student_id = :student_id
                    ORDER BY
                        entry_date DESC, id DESC
                    LIMIT 1
                ");
                $stmt->execute([':student_id' => $student->id]);
                $latestEntry = $stmt->fetch(\PDO::FETCH_OBJ);

                if ($latestEntry) {
                    $status->latest_entry = $latestEntry;
                }
            } catch (\PDOException $e) {
                error_log("Latest entry fetch error for student {$student->id}: " . $e->getMessage());
            }

            $studentStatuses[] = $status;
        }

        $teacherName = $teacher->name;
        $students = $studentStatuses;

        // ビューを読み込み
        $viewName = 'teachers.teacher_status';
        return view($viewName, compact('teacherName', 'students'));
    }

    public function readEntry($id)
    {
        global $pdo;
        // 既読処理
        try {
            $stmt = $pdo->prepare("UPDATE entries SET is_read = 1 WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Read entry update error: " . $e->getMessage());
        }

        // 処理後、前のページに戻る
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/teachers/status';
        header("Location: " . $redirectUrl);
        exit;
    }

    public function past()
    {
        return "教師: 過去の連絡帳履歴表示は未実装です。";
    }
}
