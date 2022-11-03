<?php

namespace App\Model;

use PDO;

class GameManager extends AbstractManager
{
    public const TABLE = 'game';
    public const FOREIGN_TABLE = 'user';

    /**
     * Insert new item in database
     */
    public function insert(array $game): int
    {
        $query = "INSERT INTO " . self::TABLE .
        " (id_owner, name, type, minimum_players_age, min_number_players, max_number_players, image) 
        VALUES (:id_owner, :name, :type, :minimum_players_age, :min_number_players, :max_number_players, :image)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id_owner', $game['idGameOwner'], PDO::PARAM_INT);
        $statement->bindValue(':name', $game['gameName'], PDO::PARAM_STR);
        $statement->bindValue(':type', $game['gameGenre'], PDO::PARAM_STR);
        $statement->bindValue(':minimum_players_age', $game['gameAgeMinimumPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':min_number_players', $game['gameMinimumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':max_number_players', $game['gameMaximumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':max_number_players', $game['gameMaximumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':image', $game['gameImage'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $gameData, int $id): bool
    {
        $query = "UPDATE " . self::TABLE
        . " SET `name` = :name, `type` = :type, `min_number_players` = :minNumberPlayers,
        `max_number_players` = :maxNumberPlayers, `minimum_players_age` = :minimumPlayersAge,
        `image` = :image WHERE id=:id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':name', $gameData['gameName'], PDO::PARAM_STR);
        $statement->bindValue(':type', $gameData['gameGenre'], PDO::PARAM_STR);
        $statement->bindValue(':minNumberPlayers', $gameData['gameMinimumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':maxNumberPlayers', $gameData['gameMaximumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':minimumPlayersAge', $gameData['gameAgeMinimumPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':image', $gameData['gameImage'], PDO::PARAM_STR);

        return $statement->execute();
    }

    public function select12Games(string $orderBy = 'name', string $direction = 'ASC'): array
    {
        $page = ($_GET['page'] - 1) * 12;
        $query = 'SELECT * FROM game INNER JOIN user ON user.id = game.id_owner ORDER BY '
        . $orderBy . $direction . ' LIMIT 12 OFFSET ' . $page;
        return $this->pdo->query($query)->fetchAll();
    }
}
