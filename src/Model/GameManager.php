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
        " (id_owner, name, type, minimum_players_age, min_number_players, max_number_players) 
        VALUES (:id_owner, :name, :type, :minimum_players_age, :min_number_players, :max_number_players)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_owner', $game['idGameOwner'], PDO::PARAM_STR);
        $statement->bindValue(':name', $game['gameName'], PDO::PARAM_STR);
        $statement->bindValue(':type', $game['gameGenre'], PDO::PARAM_STR);
        $statement->bindValue(':minimum_players_age', $game['gameAgeMinimumPlayers'], PDO::PARAM_STR);
        $statement->bindValue(':min_number_players', $game['gameMinimumNumberPlayers'], PDO::PARAM_STR);
        $statement->bindValue(':max_number_players', $game['gameMaximumNumberPlayers'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
