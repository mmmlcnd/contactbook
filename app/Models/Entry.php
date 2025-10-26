<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_date',
        'condition_physical',
        'condition_mental',
        'content',
        'is_read'
    ];

    /**
     * この連絡帳エントリを作成した生徒を取得
     */
    public function student()
    {
        // student_id が students テーブルの id に対応
        return $this->belongsTo(\App\Models\StudentTeacher::class, 'student_id');
    }

    /**
     * この連絡帳エントリに紐づく全ての既読履歴を取得
     */
    // public function readHistories()
    // {
    //     // entries.id が read_histories テーブルの entry_id に対応
    //     return $this->hasMany(\App\Models\ReadHistory::class);
    // }

    public static function countEntriesForStudentAndDate($studentId, $recordDate)
    {
        // 既に本日の提出があるかチェック
        // $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM entries WHERE student_id = ? AND record_date = ?");
        // $stmtCheck->execute([$studentId, $recordDate]);

        return Entry::where('student_id', $studentId)
            ->where('record_date', $recordDate)
            ->count();
    }

    public static function insertEntry($studentId, $recordDate, $physical, $mental, $content)
    {
        //         $sql = "INSERT INTO entries (student_id, record_date, condition_physical, condition_mental, content, is_read)
        //         VALUES (?, ?, ?, ?, ?, 0)";
        // $stmt = $pdo->prepare($sql);

        // $stmt->execute([
        //     $studentId,
        //     $recordDate,
        //     $physical,
        //     $mental,
        //     $content
        // ]);

        return Entry::create([
            'student_id' => $studentId,
            'record_date' => $recordDate,
            'condition_physical' => $physical,
            'condition_mental' => $mental,
            'content' => $content,
            'is_read' => 0
        ]);
    }

    public static function showPastEntries($studentId)
    {
        // やりたいこと
        // ログインしている生徒の照合→コントローラーでやる？
        // その生徒の過去のンエントリーを月ごとに表示する

        // $pdo = $this->getPdo();

        // // 自身の全提出履歴を、最新の日付順に取得する
        // $sql = "
        //     SELECT
        //         e.*,
        //         rh.stamped_at,
        //         t.name AS teacher_name,
        //         s.name AS stamp_name
        //     FROM entries e
        //     LEFT JOIN read_histories rh ON e.id = rh.entry_id
        //     LEFT JOIN teachers t ON rh.teacher_id = t.id
        //     LEFT JOIN stamps s ON rh.stamp_id = s.id
        //     WHERE e.student_id = ?
        //     ORDER BY e.record_date DESC, e.id DESC
        // ";

        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([$studentId]);
        // $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        return Entry::select()
            ->where('student_id', $studentId)
            ->orderBy('record_date', 'desc')
            ->get();
    }
}
