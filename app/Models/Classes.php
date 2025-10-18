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
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // classesテーブルからgradeとnameを取得
    public function getGradesAndNames(int $classId)
    {
        global $pdo;

        $stmt = $pdo->prepare("SELECT grade, name FROM classes WHERE id = :classId");
        $stmt->execute(['classId' => $classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertStudent($email, $hashedPassword, $name, $kana, $grade, $className)
    {
        global $pdo;

        // DBスキーマ (id, name, kana, email, password, grade, class, permission) に合わせる
        $stmt = $pdo->prepare("INSERT INTO students (email, password, name, kana, grade, class, permission) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // class_idをclassカラムへ、permissionをwriteで設定
        $stmt->execute([$email, $hashedPassword, $name, $kana, $grade, $className, 'write']);
    }

    public function insertTeacher($email, $hashedPassword, $name, $kana, $grade, $className)
    {
        global $pdo;

        // DBスキーマ (id, name, kana, email, password, grade, class, permission) に合わせる
        $stmt = $pdo->prepare("INSERT INTO teachers (email, password, name, kana, grade, class, permission) VALUES (?, ?, ?, ?, ?, ?)");
        // class_idをclassカラムへ、permissionをreadで設定
        $stmt->execute([$email, $hashedPassword, $name, $kana, $grade, $className, 'read']);
    }

    public function insertAdmin($email, $hashedPassword, $name, $kana)
    {
        global $pdo;

        // DBスキーマ (id, name, kana, email, password) に合わせる
        $stmt = $pdo->prepare("INSERT INTO admins (email, password, name, kana) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $name, $kana]);
    }
}
