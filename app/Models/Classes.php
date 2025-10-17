<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exception;
use PDO;

class Classes extends Model
{
    use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'grade', // 学年（例: 1, 2, 3）
        'name',  // クラス名（例: A組, B組）
    ];

    // 学年・クラスに所属する生徒（1対多）
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    // クラスを担当する先生（1対多想定）
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'class_id');
    }

    // クラス一覧を取得する
    public function getAllOrderedClasses()
    {
        global $pdo;

        $stmt = $pdo->prepare("SELECT id, name, grade FROM classes ORDER BY grade ASC, id ASC");
        $stmt->execute();
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $classes;
    }
}
