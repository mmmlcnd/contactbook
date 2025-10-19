<?php

namespace App\Models;

use PDO;

class Teacher
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO teachers (name, kana, email, password, grade, class, permission, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        $stmt->execute([
            $data['name'],
            $data['kana'],
            $data['email'],
            $data['password'],
            $data['grade'],
            $data['class'],
            $data['permission']
        ]);
        return $this->pdo->lastInsertId();
    }
}
