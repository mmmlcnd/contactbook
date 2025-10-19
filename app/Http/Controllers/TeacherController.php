<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            // 書き方直す
            header("Location: /login/teacher");
            exit;
        }

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
            // 書き方直す
            header("Location: /login/teacher");
            exit;
        }

        // 教師データに担当クラス情報がない場合はエラー処理
        if (empty($teacher->class)) {
            return "エラー: 担当クラスが設定されていません。";
        }

        // ★★★ 提出状況の確認対象日を設定 (2025-10-15の確認対象は2025-10-14) ★★★
        $targetRecordDate = '2025-10-14';

        // --- 1. 担当クラスの生徒を取得 ---

        $students = [];
        try {
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
                // record_date によるフィルタリングで、確認対象日のエントリーのみを取得
                $stmt = $pdo->prepare("
                    SELECT
                        t1.id, t1.record_date, t1.content, t1.is_read, t2.stamped_at, t3.name AS stamp_name
                    FROM
                        entries t1
                    LEFT JOIN
                        read_histories t2 ON t1.id = t2.entry_id AND t2.teacher_id = :teacher_id
                    LEFT JOIN
                        stamps t3 ON t2.stamp_id = t3.id
                    WHERE
                        t1.student_id = :student_id AND t1.record_date = :target_date
                    ORDER BY
                        t1.id DESC
                    LIMIT 1
                ");
                $stmt->execute([
                    ':student_id' => $student->id,
                    ':target_date' => $targetRecordDate,
                    ':teacher_id' => $loggedInUserId,
                ]);
                $latestEntry = $stmt->fetch(\PDO::FETCH_OBJ);

                if ($latestEntry) {
                    $latestEntry->entry_date = $latestEntry->record_date;
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

    /**
     * 特定のエントリーの詳細を表示し、既読・スタンプ処理のためのデータを提供する
     * @param int $id entry_id
     */
    public function readEntry(int $id)
    {
        global $pdo;
        $loggedInUserId = $_SESSION['user_id'] ?? null;
        $teacher = $this->getTeacherData($loggedInUserId);

        if (!$teacher) {
            // 書き方直す
            header("Location: /login/teacher");
            exit;
        }

        try {
            // 1. エントリーの詳細を取得
            $stmt = $pdo->prepare("
                SELECT
                    e.*, s.name as student_name, s.class as student_class
                FROM
                    entries e
                JOIN
                    students s ON e.student_id = s.id
                WHERE
                    e.id = :entry_id
            ");
            $stmt->execute([':entry_id' => $id]);
            $entry = $stmt->fetch(\PDO::FETCH_OBJ);

            if (!$entry) {
                return "エラー: 該当するエントリーが見つかりません。";
            }

            // 2. 既読履歴（スタンプ情報）を取得
            $stmt = $pdo->prepare("
                SELECT
                    rh.stamped_at, t.name as teacher_name, st.name as stamp_name
                FROM
                    read_histories rh
                JOIN
                    teachers t ON rh.teacher_id = t.id
                JOIN
                    stamps st ON rh.stamp_id = st.id
                WHERE
                    rh.entry_id = :entry_id
            ");
            $stmt->execute([':entry_id' => $id]);
            $readHistory = $stmt->fetchAll(\PDO::FETCH_OBJ);

            // 3. 利用可能なスタンプ一覧を取得
            $stmt = $pdo->query("SELECT id, name FROM stamps ORDER BY id ASC");
            $stamps = $stmt->fetchAll(\PDO::FETCH_OBJ);

            // 4. 教師自身が既にスタンプを押しているかチェック
            $currentTeacherStamped = false;
            foreach ($readHistory as $history) {
                if ($history->teacher_name == $teacher->name) {
                    $currentTeacherStamped = true;
                    break;
                }
            }

            return view('teachers.teacher_read_entry', compact('teacher', 'entry', 'readHistory', 'stamps', 'currentTeacherStamped'));
        } catch (\PDOException $e) {
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
        global $pdo;
        $loggedInUserId = $_SESSION['user_id'] ?? null;

        if (!$this->getTeacherData($loggedInUserId)) {
            // 書き方直す
            header("Location: /login/teacher");
            exit;
        }

        $stampId = $request->get('stamp_id');

        if (!is_numeric($stampId) || $stampId <= 0) {
            // エラー処理（スタンプIDが無効）
            $_SESSION['error_message'] = "無効なスタンプが選択されました。";
            // 書き方直す
            header("Location: /teachers/read/{$id}");
            exit;
        }

        try {
            // トランザクション開始
            $pdo->beginTransaction();

            // 1. read_histories に新しいスタンプ記録を挿入
            // (entry_id, teacher_id, stamp_id) の複合ユニークキーにより、同じ教師が同じエントリーに同じスタンプを二重に押すのを防止
            $stmt = $pdo->prepare("
                INSERT IGNORE INTO read_histories (entry_id, teacher_id, stamp_id, stamped_at)
                VALUES (:entry_id, :teacher_id, :stamp_id, NOW())
            ");
            $stmt->execute([
                ':entry_id' => $id,
                ':teacher_id' => $loggedInUserId,
                ':stamp_id' => $stampId,
            ]);

            // 2. entries テーブルの is_read フラグを 1 に更新
            $stmt = $pdo->prepare("UPDATE entries SET is_read = 1 WHERE id = :id");
            $stmt->execute([':id' => $id]);

            // コミット
            $pdo->commit();

            $_SESSION['success_message'] = "スタンプが正常に押印されました。";
        } catch (\PDOException $e) {
            $pdo->rollBack();
            error_log("stampEntry DB Error: " . $e->getMessage());
            $_SESSION['error_message'] = "スタンプ押印中にエラーが発生しました。";
        }

        // 詳細画面に戻る
        // 書き方直す
        header("Location: /teachers/read/{$id}");
        exit;
    }

    public function past()
    { /* ... */
    }
}
