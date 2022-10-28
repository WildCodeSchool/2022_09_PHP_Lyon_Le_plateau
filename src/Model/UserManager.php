<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

     /**
     * Insert new user in database
     */
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
}
