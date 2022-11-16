<?php

namespace App\Model;

use PDO;

class BorrowManager extends GameManager
{
    public const TABLE = 'borrow';

    public function selectBorrowRequestsReceived(int $idOwner, string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT *, b.id as borrow_id 
            FROM ' . static::TABLE . ' AS b
            INNER JOIN user AS u ON u.id = b.id_user 
            INNER JOIN game AS g ON g.id = b.id_game 
            WHERE g.id_owner = ' . $idOwner . ' AND b.acceptance = 0;';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function updateRequest(int $id, int $acceptance)
    {
        $query = 'UPDATE borrow AS b INNER JOIN game AS g ON b.id_game = g.id 
        SET acceptance = :acceptance, acceptance_date = now()';
        if ($acceptance == 2) {
            $query .= ', availability = false';
        }
        $query .= ' WHERE b.id = :id_request;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_request', $id, PDO::PARAM_INT);
        $statement->bindValue(':acceptance', $acceptance, PDO::PARAM_INT);

        return $statement->execute();
    }
}
