<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use PDO;

class Admin extends Model
{
    protected $pdo;

    protected $fillable = [
        'email',
        'password'
    ];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // 管理者をIDで取得
    public function find($id) // 指定されたIDに対応するレコードを取得
    {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // メールアドレスで認証※Auth.phpに記述
    // public function attemptLogin($email, $password)
    // {
    //     $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = ?");
    //     $stmt->execute([$email]);
    //     $admin = $stmt->fetch(PDO::FETCH_OBJ);

    //     if ($admin && password_verify($password, $admin->password)) {
    //         return $admin;
    //     }

    //     return null;
    // }

    // 新規作成
    public function create($data) // 新しいレコードをデータベースに作成
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO admins (name, kana, email, password, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$data['name'], $data['kana'], $data['email'], $data['password']]);
        return $this->pdo->lastInsertId();
    }
}
