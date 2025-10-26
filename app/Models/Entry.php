<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * 特定の生徒と特定の日付のエントリ数をカウント
     *
     * @param int $studentId
     * @param string $recordDate
     * @return int
     */
    public static function countEntriesForStudentAndDate($studentId, $recordDate)
    {
        return self::where('student_id', $studentId)
            ->where('record_date', $recordDate)
            ->count();
    }

    /**
     * 新しい連絡帳エントリを挿入。
     *
     * @param int $studentId
     * @param string $recordDate
     * @param string $physical
     * @param string $mental
     * @param string $content
     * @return \App\Models\Entry
     */
    public static function createEntry($studentId, $recordDate, $physical, $mental, $content)
    {
        return Entry::create([
            'student_id' => $studentId,
            'record_date' => $recordDate,
            'condition_physical' => $physical,
            'condition_mental' => $mental,
            'content' => $content,
            'is_read' => 0
        ]);
    }

    /**
     * 特定の生徒の過去のエントリをすべて取得し、日付の降順でソート
     *
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function showPastEntries($studentId)
    {
        return self::where('student_id', $studentId)
            ->orderBy('record_date', 'desc')
            ->get();
    }
}
