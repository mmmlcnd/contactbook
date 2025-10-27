<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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
     * 連絡帳エントリを作成した生徒を取得
     */
    public function student()
    {
        // student_id が students テーブルの id に対応
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    /**
     * この連絡帳エントリに紐づく全ての既読履歴を取得
     */
    public function readHistories()
    {
        // entries.id が read_histories テーブルの entry_id に対応
        return $this->hasMany(\App\Models\ReadHistory::class);
    }

    /**
     * 生徒の詳細画面表示に必要なリレーションをEager Load
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForStudentDetail(Builder $query): Builder
    {
        return $query->with(['readHistories' => function ($query) {
            // ReadHistoryからTeacherの名前も取得するためTeacherリレーションもEager Load
            $query->with('teacher')->orderBy('stamped_at', 'desc');
        }]);
    }

    /**
     * 指定された月のデータに絞り込むローカルスコープ（記録日ベース）
     */
    public function scopeForMonth($query, $date)
    {
        // $dateはCarbonオブジェクトと仮定
        return $query
            ->whereYear('record_date', $date->year)
            ->whereMonth('record_date', $date->month);
    }

    /**
     * 関連する確認履歴を新しい順にソートして事前読み込みするローカルスコープ
     */
    public function scopeWithReadHistoriesSorted($query)
    {
        return $query->with(['readHistories' => function ($q) {
            $q->orderBy('stamped_at', 'desc');
        }]);
    }

    /**
     * 指定されたエントリーIDと生徒IDで連絡帳を取得
     * @param int $entryId 連絡帳のID
     * @param int $studentId ログイン中の生徒のID
     * @return Entry|null 連絡帳モデル、または見つからない場合はnull
     */
    public static function getForStudentDetail(int $entryId, int $studentId): ?Entry
    {
        return self::forStudentDetail() // Eager Loadスコープを適用
            ->where('id', $entryId)
            ->where('student_id', $studentId) // セキュリティフィルタリング
            ->first();
    }
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
     * 新しい連絡帳エントリを挿入
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
    public static function getPastEntries($studentId, $currentMonth)
    {
        // YYYY-MMを 'YYYY-MM%' のワイルドカードに変換
        $monthFilter = $currentMonth . '%';

        return DB::table('entries')
            ->select(
                'entries.*', // エントリの全フィールド
                'rh.stamped_at', // 既読履歴の日付
                'stamps.name as stamp_name',
                't.name as teacher_name'
            )
            ->leftJoin('read_histories as rh', 'entries.id', '=', 'rh.entry_id')
            ->leftJoin('stamps', 'rh.stamp_id', '=', 'stamps.id')
            ->leftJoin('teachers as t', 'rh.teacher_id', '=', 't.id')
            ->where('entries.student_id', $studentId)
            ->where('entries.record_date', 'like', $monthFilter)
            ->orderBy('entries.record_date', 'desc')
            ->get();
    }
}
