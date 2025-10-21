<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentTeacher extends Model
{
    public function find($table, $id)
    {
        // $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        // $stmt->execute([$id]);
        // return $stmt->fetch(PDO::FETCH_OBJ);

        return DB::table($table)
            ->where('id', $id) // IDで検索
            ->first();         // 最初の1件を取得
    }

    public function insertStudentOrTeacher($table, $email, $hashedPassword, $name, $kana, $grade, $className, $permission)
    {
        // 動的にテーブル名を変更する場合はクエリビルダー（DB）使用
        DB::table($table)->create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class' => $className,
            'permission' => $permission
        ]);
    }
}
