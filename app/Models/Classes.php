<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    // テスト・シーディング用トレイト
    // use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'grade', // 学年（例: 1, 2, 3）
        'name',  // クラス名（例: A組, B組）
    ];

    // 学年・クラスに所属する生徒（1対多）
    public function students()
    {
        return $this->hasMany(StudentTeacher::class, 'class_id');
    }

    // クラスを担当する先生（1対多想定）
    public function teachers()
    {
        return $this->hasMany(StudentTeacher::class, 'class_id');
    }

    // classesテーブルからクラス一覧を取得する
    public function getAllOrderedClasses()
    {
        // global $pdo;

        // $stmt = $pdo->prepare("SELECT id, name, grade FROM classes ORDER BY grade ASC, id ASC");
        // $stmt->execute();
        // return $stmt->fetchAll(PDO::FETCH_OBJ);

        return Classes::select('id', 'name', 'grade')
            ->orderBy('grade', 'asc')
            ->orderBy('id', 'asc')
            ->get(); // 全てのレコードをコレクションとして取得
    }

    // classesテーブルからgradeとnameを取得
    public function getGradesAndNames(int $classId)
    {
        // global $pdo;

        // $stmt = $pdo->prepare("SELECT grade, name FROM classes WHERE id = :classId");
        // $stmt->execute(['classId' => $classId]);
        // return $stmt->fetch(PDO::FETCH_ASSOC);

        return Classes::select('grade', 'name')
            ->where('id', $classId)
            ->first();
    }
}
