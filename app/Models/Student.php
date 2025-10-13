<?php

namespace App\Models;

use PDO;

class Student
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function attemptLogin($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->execute([$email]);
        $student = $stmt->fetch(PDO::FETCH_OBJ);

        if ($student && password_verify($password, $student->password)) {
            return $student;
        }

        return null;
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, kana, email, password, grade, class, permission, created_at, updated_at)
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
