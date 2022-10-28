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
}
