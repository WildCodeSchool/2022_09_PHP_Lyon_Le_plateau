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
        u.lastname as borrower_lastname, u.id as borrower_id, b.request_date, b.acceptance_date, 
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
        u.lastname as borrower_lastname, u.id as borrower_id, b.request_date, b.acceptance_date, 
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
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id . " and s.borrow_status = 'Accept??'";

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectDeclinedBorrowByUserId(int $id): array|false
    {
        $query = "SELECT b.id AS borrow_id, b.id_game, g.name as game_name, g.type, g.min_number_players, 
        g.max_number_players, g.minimum_players_age, g.image, g.availability, owner.firstname as owner_firstname, 
        owner.lastname as owner_lastname, g.id_owner as owner_id, u.firstname as borrower_firstname,
        u.lastname as borrower_lastname, u.id as borrower_id, b.request_date, b.acceptance_date, 
        s.borrow_status  FROM " . static::TABLE . " as b inner join user as u on u.id = b.id_user
        INNER JOIN game AS g ON g.id = b.id_game inner join user as owner on owner.id = g.id_owner 
        INNER JOIN status AS s ON s.id = b.id_status WHERE b.id_user=" . $id . " and s.borrow_status = 'Refus??'";

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

    public function updateBorrowStatusAsOver(int $borrowId): void
    {
        $query = "UPDATE " . static::TABLE . " AS g SET id_status=4 WHERE g.id=:id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $borrowId, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectBorrowRequestsReceived(int $idOwner, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT *, b.id as borrow_id 
            FROM ' . static::TABLE . ' AS b
            INNER JOIN user AS u ON u.id = b.id_user 
            INNER JOIN game AS g ON g.id = b.id_game 
            WHERE g.id_owner = ' . $idOwner . ' AND b.id_status = 1;';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        return $this->pdo->query($query)->fetchAll();
    }

    public function updateRequest(int $id, int $status): bool
    {
        $query = 'UPDATE borrow AS b
        INNER JOIN game AS g ON b.id_game = g.id
        INNER JOIN user AS u ON g.id_owner = u.id
        SET id_status = :status, acceptance_date = now()';
        if ($status == 2) {
            $query .= ', g.availability = false';
        }
        $query .= ' WHERE b.id = :id_request;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_request', $id, PDO::PARAM_INT);
        $statement->bindValue(':status', $status, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function updateGameReturned(int $id): void
    {
        $query = 'UPDATE game AS g INNER JOIN borrow AS b ON b.id_game = g.id 
        SET g.availability = true, b.id_status = 4 
        WHERE b.id=:id;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectOneBorrowById(int $id): array|false
    {
        $query = 'SELECT g.id AS game_id, g.name, g.type, g.minimum_players_age, g.image, g.id_owner, 
        g.min_number_players, g.max_number_players, g.availability, u.id AS user_id, u.firstname AS user_firstname, 
        u.lastname AS user_lastname, u.email, u.password, b.id AS borrow_id, b.id_game, b.id_user, b.id_status, 
        o.id AS owner_id, o.firstname AS owner_firstname, o.lastname AS owner_lastname
        FROM game AS g
        INNER JOIN user AS o ON o.id = g.id_owner
        INNER JOIN borrow as b ON b.id_game = g.id
        INNER JOIN user as u ON b.id_user = u.id
        WHERE b.id=:id;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
