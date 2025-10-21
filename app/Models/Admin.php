<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use PDO;

class Admin extends Model
{

    protected $fillable = [
        'name',
        'kana',
        'email',
        'password'
    ];

    // 管理者をIDで取得
    public function find($id) // 指定されたIDに対応するレコードを取得
    {
        // $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE id = ?");
        // $stmt->execute([$id]);
        // return $stmt->fetch(PDO::FETCH_OBJ);

        return Admin::where('id', $id) //select('*')は省略可
            ->first();
    }

    // 管理者データ挿入
    public function insertAdmin($email, $hashedPassword, $name, $kana)
    {
        // global $pdo;

        // DBスキーマ (id, name, kana, email, password) に合わせる
        // $stmt = $pdo->prepare("INSERT INTO admins (email, password, name, kana) VALUES (?, ?, ?, ?)");
        // $stmt->execute([$email, $hashedPassword, $name, $kana]);

        Admin::create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana
        ]);
        // -> created_at, updated_at が自動で入る
        // -> 作成された Admin Model インスタンスが返される
    }

    // 新規作成
    // public function create($data) // 新しいレコードをデータベースに作成
    // {
    //     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    //     $stmt = $this->pdo->prepare("INSERT INTO admins (name, kana, email, password, created_at, updated_at)
    //         VALUES (?, ?, ?, ?, NOW(), NOW())");
    //     $stmt->execute([$data['name'], $data['kana'], $data['email'], $data['password']]);
    //     return $this->pdo->lastInsertId();
    // }
}
