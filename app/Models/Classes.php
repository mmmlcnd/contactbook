<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // StudentTeacher.phpに移動
    // 生徒・教師データ挿入
    public function insertStudentOrTeacher($table, $email, $hashedPassword, $name, $kana, $grade, $className, $permission)
    {
        global $pdo;

        // DBスキーマ (id, name, kana, email, password, grade, class, permission) に合わせる
        // $stmt = $pdo->prepare("INSERT INTO `{$table}` (email, password, name, kana, grade, class, permission) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // class_idをclassカラムへ、permissionをwriteで設定
        // $stmt->execute([$email, $hashedPassword, $name, $kana, $grade, $className, $permission]);

        // 動的にテーブル名を変更する場合はクエリビルダー（DB）使用
        DB::table($table)->insert([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class' => $className,
            'permission' => $permission
        ]);
    }

    // 以下Admin.phpに移動
    // // 管理者データ挿入
    // public function insertAdmin($email, $hashedPassword, $name, $kana)
    // {
    //     global $pdo;

    //     // DBスキーマ (id, name, kana, email, password) に合わせる
    //     // $stmt = $pdo->prepare("INSERT INTO admins (email, password, name, kana) VALUES (?, ?, ?, ?)");
    //     // $stmt->execute([$email, $hashedPassword, $name, $kana]);

    //     Admin::insert([
    //         'email' => $email,
    //         'password' => $hashedPassword,
    //         'name' => $name,
    //         'kana' => $kana
    //     ]);
    // }
}
