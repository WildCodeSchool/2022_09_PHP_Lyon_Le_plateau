<?php

namespace App\Model;

use PDO;

class BorrowManager extends GameManager
{
    public const TABLE = 'borrow';

    public function selectAllBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id AS borrow_id, b.id_game, g.name as game_name, g.type, g.min_number_players,
        g.max_number_players, g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b. request_date, b.acceptance_date, 
        s.borrow_status  FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        INNER JOIN game AS g ON g.id = b.id_game inner join user as owner on owner.id = g.id_owner 
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id;

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectPendingBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id AS borrow_id, b.id_game, g.name as game_name, g.type, g.min_number_players, 
        g.max_number_players, g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b. request_date, b.acceptance_date, 
        s.borrow_status  FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        INNER JOIN game AS g ON g.id = b.id_game inner join user as owner on owner.id = g.id_owner 
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id . " and s.borrow_status = 'En attente'";

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAcceptedBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id AS borrow_id, b.id_game, g.name as game_name, g.type, g.min_number_players, 
        g.max_number_players, g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b. request_date, b.acceptance_date, 
        s.borrow_status  FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        INNER JOIN game AS g ON g.id = b.id_game inner join user as owner on owner.id = g.id_owner 
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id . " and s.borrow_status = 'Accepté'";

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectDeclinedBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id AS borrow_id, b.id_game, g.name as game_name, g.type, g.min_number_players, 
        g.max_number_players, g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b. request_date, b.acceptance_date, 
        s.borrow_status  FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        INNER JOIN game AS g ON g.id = b.id_game inner join user as owner on owner.id = g.id_owner 
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id . " and s.borrow_status = 'Refusé'";

        return $this->pdo->query($query)->fetchAll();
    }

    public function insertBorrow(int $gameId, int $userId): int
    {
        $query = "INSERT INTO "  . static::TABLE . " (id_game, id_user, request_date) 
        VALUES (:id_game, :id_user, NOW())";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_game', $gameId, PDO::PARAM_INT);
        $statement->bindValue(':id_user', $userId, PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
