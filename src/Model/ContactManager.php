<?php

namespace App\Model;

use PDO;

class ContactManager extends AbstractManager
{
    public const TABLE = 'contact';

    public function insert(array $user): int
    {
        $query = "INSERT INTO " . self::TABLE .
            " (firstname, lastname, email, message) 
        VALUES (:firstname, :lastname, :email, :message)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':firstname', $user['userFirstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user['userLastname'], PDO::PARAM_STR);
        $statement->bindValue(':email', $user['userEmail'], PDO::PARAM_STR);
        $statement->bindValue(':message', $user['userMessage'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function selectOneById(int $id): array
    {
        $query = "SELECT * FROM " . static::TABLE . " WHERE id = $id;";
        $statement = $this->pdo->query($query);
        $userMessage = $statement->fetch();

        return $userMessage;
    }

    public function updateAsRead(int $id): void
    {
        $statement = $this->pdo->prepare('UPDATE contact SET isRead = 1 WHERE id = :id;');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    public function updateAsNotRead(int $id): void
    {
        $statement = $this->pdo->prepare('UPDATE contact SET isRead = 0 WHERE id = :id;');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }
}
