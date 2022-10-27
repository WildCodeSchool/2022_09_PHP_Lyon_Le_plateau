<?php

namespace App\Model;

use PDO;

class GameManager extends AbstractManager
{
    public const TABLE = 'game';

    /**
     * Insert new item in database
     */
    public function insert(array $game): int
    {
        $query = "INSERT INTO " . self::TABLE .
        " (id_owner, name, type, min_number_players, max_number_players, minimum_players_age) 
        VALUES (:id_owner, :name, :type, :min_number_players, :max_number_player :minimum_players_age)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_owner', $game['idGameOwner'], PDO::PARAM_STR);
        $statement->bindValue(':name', $game['gameName'], PDO::PARAM_STR);
        $statement->bindValue(':type', $game['gameGenre'], PDO::PARAM_STR);
        $statement->bindValue(':min_number_players', $game['gameMinNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':max_number_players', $game['gameMaxNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':minimum_players_age', $game['gameAgeMinimumPlayers'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
