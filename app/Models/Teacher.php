<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'name',
        'kana',
        'grade',
        'class_name',
        'permission'
    ];

    public static function createTeacher(string $email, string $hashedPassword, string $name, string $kana, string $grade, string $className, string $permission)
    {
        return Teacher::create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class_name' => $className,
            'permission' => $permission
        ]);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_name', 'class_name')
            // grade も一致することを保証
            ->where('grade', $this->grade);
    }

    /**
     * 担当クラスの生徒のIDと名前のみを取得
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStudentsIdAndName()
    {
        return Student::select('id', 'name')
            ->where('grade', $this->grade)
            ->where('class_name', $this->class_name)
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * 担当クラスの生徒と、指定日の最新の連絡帳状況をまとめて取得
     * @param string $targetRecordDate 確認対象日 (Y-m-d形式)
     * @param int $teacherId ログイン中の教師ID
     * @return array 生徒と最新の提出状況の配列
     */
    public function getStudentsWithLatestEntryStatus(string $targetRecordDate, int $teacherId, int $targetGrade, string $targetClassName): array
    {
        // 1. 担当生徒を取得
        $students = $this->getStudentsIdAndName($targetGrade, $targetClassName);
        $studentStatuses = [];

        foreach ($students as $student) {
            // 2. 各生徒のエントリーとスタンプ情報を結合して取得
            $latestEntry = Entry::where('entries.student_id', $student->id)
                ->where('entries.record_date', $targetRecordDate)
                ->leftJoin('read_histories', function ($join) use ($teacherId) {
                    $join->on('entries.id', '=', 'read_histories.entry_id')
                        ->where('read_histories.teacher_id', $teacherId);
                })
                ->leftJoin('stamps', 'read_histories.stamp_id', '=', 'stamps.id')
                ->select(
                    'entries.id',
                    'entries.record_date',
                    'entries.content',
                    'entries.is_read',
                    'read_histories.stamped_at',
                    'stamps.name as stamp_name'
                )
                ->orderByDesc('entries.id')
                ->first();

            $status = (object)[
                'id' => $student->id,
                'name' => $student->name,
                'latest_entry' => $latestEntry ? (object)array_merge($latestEntry->toArray(), ['entry_date' => $latestEntry->record_date]) : null,
            ];

            $studentStatuses[] = $status;
        }

        return $studentStatuses;
    }

    /**
     * 特定のエントリーの詳細、既読履歴、スタンプ一覧を取得
     * @param int $entryId エントリーID
     * @return array|null 必要なデータを含む配列
     */
    public static function getEntryDetailsForRead(int $entryId)
    {
        // 1. エントリーの詳細を取得
        $entry = Entry::where('entries.id', $entryId)
            ->join('students as s', 'entries.student_id', '=', 's.id')
            ->select('entries.*', 's.name as student_name', 's.class as student_class')
            ->first();

        if (!$entry) {
            return null;
        }

        // 2. 既読履歴（スタンプ情報）を取得 (ReadHistory, Teacher, Stampモデルを仮定)
        $readHistory = DB::table('read_histories')
            ->where('read_histories.entry_id', $entryId)
            ->join('teachers as t', 'read_histories.teacher_id', '=', 't.id')
            ->join('stamps as st', 'read_histories.stamp_id', '=', 'st.id')
            ->select('read_histories.stamped_at', 't.name as teacher_name', 'st.name as stamp_name')
            ->get();

        // 3. 利用可能なスタンプ一覧を取得
        $stamps = DB::table('stamps')->select('id', 'name')->orderBy('id', 'asc')->get();

        return [
            'entry' => $entry,
            'readHistory' => $readHistory,
            'stamps' => $stamps,
        ];
    }

    /**
     * エントリーにスタンプを押し、既読履歴として保存する (DBファサード/Eloquent使用)
     * @param int $entryId エントリーID
     * @param int $teacherId ログイン中の教師ID
     * @param int $stampId スタンプID
     * @return bool 成功したらtrue
     */
    public static function stampEntry(int $entryId, int $teacherId, int $stampId): bool
    {
        try {
            DB::transaction(function () use ($entryId, $teacherId, $stampId) {
                // 1. read_histories に新しいスタンプ記録を挿入
                DB::table('read_histories')->insertOrIgnore([
                    'entry_id' => $entryId,
                    'teacher_id' => $teacherId,
                    'stamp_id' => $stampId,
                    'stamped_at' => Carbon::now(),
                ]);

                // 2. entries テーブルの is_read フラグを 1 に更新
                // Entryモデルが定義されていることを前提とする
                Entry::where('id', $entryId)->update(['is_read' => 1]);
            });
            return true;
        } catch (\Exception $e) {
            \Log::error("stampEntry DB Error: " . $e->getMessage());
            return false;
        }
    }
}
