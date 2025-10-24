<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// DynamicTableManagerのような名前に変更を推奨します
class StudentTeacher extends Model
{
    // Eloquentの自動タイムスタンプ機能を無効にする（このModelではクエリビルダを使うため）
    public $timestamps = false;

    /**
     * 動的なテーブルからIDでレコードを検索する。
     * @return object|null
     */
    public function findByTableAndId(string $table, int $id)
    {
        return DB::table($table)
            ->where('id', $id)
            ->first();
    }

    /**
     * 動的なテーブルに生徒または教師を挿入する（クエリビルダを使用）
     * @return bool
     */
    public function insertStudentOrTeacher(string $table, string $email, string $hashedPassword, string $name, string $kana, string $grade, string $className, string $permission): bool
    {
        // クエリビルダのためinsert() メソッドを使用
        return DB::table($table)->insert([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class' => $className,
            'permission' => $permission,
            'created_at' => now(), // 必須: タイムスタンプを手動で設定
            'updated_at' => now(), // 必須: タイムスタンプを手動で設定
        ]);
    }
}
