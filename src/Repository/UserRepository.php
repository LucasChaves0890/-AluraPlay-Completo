<?php

namespace Repository;

use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo)
    {   
    }

    public function getUser(string $email)
    {
        $sql = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $email);    
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateHash(string $password, int $id)
    {
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, password_hash($password, PASSWORD_ARGON2ID));
        $stmt->bindValue(2, $id);
        $stmt->execute();
    }

}
