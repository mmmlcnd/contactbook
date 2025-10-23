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
}
