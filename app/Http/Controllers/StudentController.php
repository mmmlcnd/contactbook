<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\Student;
use Carbon\Carbon;

class StudentController extends Controller
{
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
     * 現在認証されている生徒のIDを取得
     * @return int|null 生徒ID
     */
    private function getStudentId(): ?int
    {
        // セッションからユーザータイプとIDを取得
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
    public function create()
    {
        if (!$this->checkAuth()) return;

        return view('students.student_create_entry', ['title' => '連絡帳提出']);
    }

    /**
     * 連絡帳をデータベースに保存する
     */
    public function store(Request $request)
    {
        if (!$this->checkAuth()) return;

        $studentId = $this->getStudentId();

        // IDが取得できなければエラーとしてリダイレクト
        if (!$studentId) {
            $_SESSION['error_message'] = 'ログイン中のユーザー情報が取得できませんでした。';
            return redirect()->route('login.student');
        }

        // フォームデータ取得
        $physical = $request->input('condition_physical');
        $mental = $request->input('condition_mental');
        $content = trim($request->input('content')); //不要な余白削除
        $recordDate = date('Y-m-d'); // 提出時の日付

        // 記録日を前登校日にする設定
        $submitTime = Carbon::now(); //現在時刻
        $recordDate = $submitTime->copy(); //記録日操作用
        $dayOfWeek = $submitTime->dayOfWeek; // 曜日を数値で取得：0 (日) - 6 (土)

        // 提出日を元に記録日確定
        if ($dayOfWeek === Carbon::MONDAY) { // 月曜提出(-3日)
            $recordDate->subDays(3);
        } elseif ($dayOfWeek === Carbon::SUNDAY) { // 日曜提出(-2日)
            $recordDate->subDays(2);
        } else { // 火・水・木・金・土曜提出(-1日)
            $recordDate->subDay();
        }

        // 記録日の文字列化
        $recordDateString = $recordDate->format('Y-m-d');

        // バリデーション
        // if ($physical === false || $mental === false || $physical < 1 || $physical > 5 || $mental < 1 || $mental > 5 || empty($content)) {
        //     $_SESSION['error_message'] = '入力内容に誤りがあります。体調評価(1-5)と連絡内容が必須です。';

        //     return redirect()->route('students.entries.create');
        // }

        try {
            // 静的メソッドの呼び出し
            $count = Entry::countEntriesForStudentAndDate($studentId, $recordDate);

            if ($count > 0) {
                $_SESSION['error_message'] = '本日は既に連絡帳を提出済みです。';

                return redirect()->route('students.entries.create');
            }

            Entry::createEntry($studentId, $recordDate, $physical, $mental, $content);

            $_SESSION['success_message'] = '連絡帳が正常に提出されました。先生の確認をお待ちください。';
            return redirect()->route('students.entries.create');
        } catch (\PDOException $e) {

            error_log("Database error in createEntry: " . $e->getMessage());
            $_SESSION['error_message'] = 'データの保存中にエラーが発生しました。時間を置いて再度お試しください。';
        }

        return redirect()->route('students.entries.create');
    }

    /**
     * 過去提出履歴を表示
     */
    public function showPastEntries(Request $request)
    {
        // 認証済み生徒ID取得
        $studentId = $this->getStudentId();
        if (!$studentId) {
            redirect()->route('login.student');
            exit;
        }

        // Studentモデルから生徒を取得
        $student = Student::find($studentId);
        if (!$student) {
            // エラーメッセージを設定してリダイレクト
            $_SESSION['error_message'] = '生徒情報が見つかりませんでした。';
            return redirect()->route('login.student');
        }

        // 表示月（URLクエリパラメータを優先、なければ今月）
        $selectedMonth = $request->input('month');

        // 比較の基準となる今月の1日を確定
        $currentMonthBase = Carbon::today()->startOfMonth();

        $date = $currentMonthBase->copy();

        // ユーザーが指定した月が存在し、形式が正しいかチェック
        if ($selectedMonth && preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            try {
                $timezone = 'Asia/Tokyo';

                $monthWithDay = $selectedMonth . '-01';
                $parsedDate = Carbon::createFromFormat('Y-m-d', $monthWithDay, $timezone);

                // パースが成功し、Carbonインスタンスであることを確認
                if ($parsedDate instanceof Carbon) {
                    $date = $parsedDate->startOfMonth();
                } else {
                    // 失敗した場合は例外を投げてcatchさせる
                    throw new \Exception("Carbon parsing failed for month: {$selectedMonth}");
                }
            } catch (\Exception $e) {
                // パースエラーの場合、エラーログを出力し、デフォルト（今月）を使用
                error_log("showPastEntries: Failed to parse month parameter '" . $selectedMonth . "': " . $e->getMessage());
                // $date はデフォルトのまま（現在の月）
            }
        }

        // 2. 月ナビゲーション用の計算
        $currentMonth = $date->format('Y-m');
        $previousMonth = $date->copy()->subMonth()->format('Y-m');
        $nextMonth = $date->copy()->addMonth()->format('Y-m');

        // 3. 次月ボタンの無効化チェック
        // 次月を示すCarbonオブジェクトを取得
        $timezone = 'Asia/Tokyo';
        $nextMonthObj = Carbon::createFromFormat('Y-m', $nextMonth)->startOfMonth();

        $isFutureMonth = $nextMonthObj->greaterThan($currentMonthBase);

        // Studentモデルのリレーション (entries()) を通じてEntryを取得
        $entries = $student->entries()
            // Entryモデルのスコープ呼び出し
            ->forMonth($date)
            // Entryモデルのスコープ呼び出し
            ->withReadHistoriesSorted()
            ->get();

        $pastEntries = [];

        foreach ($entries as $entry) {
            // $entry->readHistories で確認履歴が既にロードされている
            $pastEntries[] = [
                'entry' => $entry,
                // リレーションから確認履歴を取得
                'read_history' => $entry->readHistories,
            ];
        }

        // 日付の降順に並び替え
        usort($pastEntries, function ($a, $b) {
            // bの日付とaの日付を比較して、降順 (新しい順) にする
            return strcmp($b['entry']->record_date, $a['entry']->record_date);
        });

        return view('students/student_past_entries', [
            'pastEntries' => $pastEntries,
            'currentMonth' => $currentMonth,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
            'isFutureMonth' => $isFutureMonth,
            'displayMonth' => $date->format('Y年n月'),
            'title' => '提出履歴',
        ]);
    }

    /**
     * 連絡帳詳細画面を表示
     * @param int $id Entry ID
     */
    public function showEntryDetail(int $id)
    {
        if (!$this->checkAuth()) return;

        $studentId = $this->getStudentId();

        try {
            $entry = Entry::forStudentDetail() // カスタムスコープで必要なリレーションをEager Load
                ->where('id', $id)
                ->where('student_id', $studentId)
                ->first();

            // 連絡帳が見つからない、または他の生徒のエントリーであればエラーログを出力
            if (!$entry) {
                error_log("Entry not found or unauthorized access: Entry ID={$id}, Student ID={$studentId}");
                $_SESSION['error_message'] = '指定された連絡帳が見つかりませんでした。';
                return redirect()->route('students.entries.past');
            }

            // ビューに$entryと$readHistoryを渡す
            return view('students.student_entry_detail', [
                'entry' => $entry,
                'readHistory' => $entry->readHistories, // モデルのスコープでロード済み
                'title' => '連絡帳詳細',
            ]);
        } catch (\Exception $e) {
            // データベースエラーやその他の予期せぬエラーが発生した場合
            error_log("500 Server Error in showEntryDetail: Entry ID={$id}, Error: " . $e->getMessage());
            $_SESSION['error_message'] = 'データの取得中に致命的なエラーが発生しました。';
            return redirect()->route('students.entries.past');
        }
    }

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
