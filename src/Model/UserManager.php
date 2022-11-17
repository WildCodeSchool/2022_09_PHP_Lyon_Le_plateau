<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function updateUser(array $userData, int $id): bool
    {
        $query = "UPDATE " . self::TABLE
            . " SET `firstname` = :firstname, `lastname` = :lastname, `email` = :email WHERE id=:id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':firstname', $userData['userFirstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $userData['userLastname'], PDO::PARAM_STR);
        $statement->bindValue(':email', $userData['userEmail'], PDO::PARAM_STR);
        return $statement->execute();
    }

    public function updatePassword(array $userData, int $id): bool
    {
        $query = "UPDATE " . self::TABLE
            . " SET `password` = :password WHERE id=:id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':password', password_hash($userData['userPassword'], PASSWORD_BCRYPT), PDO::PARAM_STR);
        return $statement->execute();
    }

    public function insert(array $user): int
    {
        $query = "INSERT INTO " . self::TABLE .
            " (firstname, lastname, email, password) 
        VALUES (:firstname, :lastname, :email, :password)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':firstname', $user['userFirstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user['userLastname'], PDO::PARAM_STR);
        $statement->bindValue(':email', $user['userEmail'], PDO::PARAM_STR);
        $statement->bindValue(':password', password_hash($user['userPassword'], PASSWORD_BCRYPT), PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function uniqueEmail(string $email)
    {
        $email = $_POST['userEmail'];
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE email=?");
        $statement->execute([$email]);
        $user = $statement->fetch();
        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    public function selectOneByEmail(string $email): array|false
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE email=:email");
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
