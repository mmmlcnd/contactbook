<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentTeacher;
use App\Models\Entry;
use App\Models\User;
use PDO;


class StudentController extends Controller
{
    /**
     * コンストラクタ: セッションを開始し、$_SESSIONを使用できるようにする
     */
    public function __construct()
    {
        // PHPのセッションがまだ開始されていない場合にのみ開始する
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * 現在認証されている生徒のIDを取得する
     * @return int|null 生徒ID
     */
    private function getStudentId(): ?int
    {
        // F-1.3に基づいてセッションからユーザータイプとIDを取得
        if (($_SESSION['user_type'] ?? null) !== 'student') {
            return null;
        }
        return (int)($_SESSION['user_id'] ?? 0);
    }

    /**
     * 認証チェックとリダイレクト
     * @return bool 認証済みならtrue、未認証ならリダイレクトしfalseを返す
     */
    private function checkAuth(): bool
    {
        if (!$this->getStudentId()) {
            // 未認証またはロールが異なる場合はログインページへリダイレクト
            redirect()->route('login.student');
            exit;
            return false;
        }
        return true;
    }

    /**
     * 生徒用ダッシュボードを表示
     */
    public function dashboard()
    {
        return view('students.student_dashboard');
    }

    /**
     * 連絡帳提出フォームを表示する
     */
    public function showCreateEntryForm()
    {
        if (!$this->checkAuth()) return;

        return view('students.student_create_entry', ['title' => '連絡帳提出']);
    }

    /**
     * 連絡帳をデータベースに保存する
     */
    public function createEntry()
    {
        if (!$this->checkAuth()) return;

        $studentId = $this->getStudentId();

        // フォームデータの取得とサニタイズ
        $physical = filter_input(INPUT_POST, 'condition_physical', FILTER_VALIDATE_INT);
        $mental = filter_input(INPUT_POST, 'condition_mental', FILTER_VALIDATE_INT);
        $content = trim(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS));
        $recordDate = date('Y-m-d'); // 提出時の日付

        // バリデーション
        if ($physical === false || $mental === false || $physical < 1 || $physical > 5 || $mental < 1 || $mental > 5 || empty($content)) {
            $_SESSION['error_message'] = '入力内容に誤りがあります。体調評価(1-5)と連絡内容が必須です。';

            return redirect()->route('students.entries.create');
        }

        try {
            // 静的メソッドの呼び出し
            $count = Entry::countEntriesForStudentAndDate($studentId, $recordDate);

            if ($count > 0) {
                $_SESSION['error_message'] = '本日は既に連絡帳を提出済みです。';

                return redirect()->route('students.entries.create');
            }

            Entry::insertEntry($studentId, $recordDate, $physical, $mental, $content);

            $_SESSION['success_message'] = '連絡帳が正常に提出されました。先生の確認をお待ちください。';
            return redirect()->route('students.entries.create');
        } catch (\PDOException $e) {

            error_log("Database error in createEntry: " . $e->getMessage());
            $_SESSION['error_message'] = 'データの保存中にエラーが発生しました。時間を置いて再度お試しください。';
        }

        return redirect()->route('students.entries.create');
    }

    /**
     * 過去提出履歴を表示 (F-4.2)
     */
    // public function showPastEntries()
    // {
    //     if (!$this->checkAuth()) return;

    //     $studentId = $this->getStudentId();
    //     $pastEntries = [];

    //     try {
    //         $pdo = $this->getPdo();

    //         // 自身の全提出履歴を、最新の日付順に取得する
    //         $sql = "
    //             SELECT
    //                 e.*,
    //                 rh.stamped_at,
    //                 t.name AS teacher_name,
    //                 s.name AS stamp_name
    //             FROM entries e
    //             LEFT JOIN read_histories rh ON e.id = rh.entry_id
    //             LEFT JOIN teachers t ON rh.teacher_id = t.id
    //             LEFT JOIN stamps s ON rh.stamp_id = s.id
    //             WHERE e.student_id = ?
    //             ORDER BY e.record_date DESC, e.id DESC
    //         ";

    //         $stmt = $pdo->prepare($sql);
    //         $stmt->execute([$studentId]);
    //         $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    //         // 日付ごとにエントリと履歴をグループ化
    //         $groupedEntries = [];
    //         foreach ($results as $row) {
    //             $date = $row->record_date;
    //             if (!isset($groupedEntries[$date])) {
    //                 $groupedEntries[$date] = (object)[
    //                     'entry' => (object)[
    //                         'id' => $row->id,
    //                         'record_date' => $row->record_date,
    //                         'content' => $row->content,
    //                         'condition_physical' => $row->condition_physical,
    //                         'condition_mental' => $row->condition_mental,
    //                         'is_read' => $row->is_read,
    //                     ],
    //                     'read_history' => []
    //                 ];
    //             }

    //             // read_historyがある場合のみ追加
    //             if ($row->stamped_at) {
    //                 $groupedEntries[$date]->read_history[] = (object)[
    //                     'teacher_name' => $row->teacher_name,
    //                     'stamp_name' => $row->stamp_name,
    //                     'stamped_at' => $row->stamped_at,
    //                 ];
    //             }
    //         }

    //         // 連想配列を数値インデックスの配列に戻す
    //         $pastEntries = array_values($groupedEntries);
    //     } catch (\PDOException $e) {
    //         error_log("Database error in showPastEntries: " . $e->getMessage());
    //         $_SESSION['error_message'] = '提出履歴の取得中にエラーが発生しました。';
    //     }

    //     // F-4.2: 過去提出履歴を表示
    //     return view('students/student_past_entries', [
    //         'title' => '提出履歴',
    //         'pastEntries' => $pastEntries,
    //     ]);
    // }

    /**
     * ログアウト処理 (F-1.5)
     */
    // public function logout()
    // {
    //     // ログアウト処理（セッション破棄）
    //     session_start();
    //     session_unset();
    //     session_destroy();

    //     // ログイン画面へリダイレクト
    //     redirect()->route('login.student');

    //     exit;
    // }
}
