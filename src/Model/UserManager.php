<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    public function update(array $userData, int $id): bool
    {
        $query = "UPDATE " . self::TABLE
            . " SET `firstname` = :firstname, `lastname` = :lastname, `email` = :email,
        `password` = :password WHERE id=:id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':firstname', $userData['userFirstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $userData['userLastname'], PDO::PARAM_STR);
        $statement->bindValue(':email', $userData['userEmail'], PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['userPassword'], PDO::PARAM_STR);
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
        $statement->bindValue(':password', $user['userPassword'], PDO::PARAM_STR);

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

    public function selectAllUser(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . " WHERE admin=false";
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
