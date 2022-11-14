<?php

namespace App\Model;

use PDO;

class BorrowManager extends GameManager
{
    public const TABLE = 'borrow';

    public function selectAllBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id_game, g.name as game_name, g.type, g.min_number_players, g.max_number_players, 
        g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b.acceptance_date, 
        b.acceptance FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        inner join game as g on g.id = b.id_game inner join user as owner on owner.id = g.id_owner
        WHERE b.id_user=" . $id;

        return $this->pdo->query($query)->fetchAll();
    }

    public function insertBorrow(int $gameId, int $userId): int
    {
        $query = "INSERT INTO " . self::TABLE . " (id_game, id_user) VALUES (:id_game, :id_user)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_game', $gameId, PDO::PARAM_INT);
        $statement->bindValue(':id_user', $userId, PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
