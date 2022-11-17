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
            . " SET `id_owner` = :id_owner, `name` = :name, `type` = :type, `min_number_players` = :minNumberPlayers,
        `max_number_players` = :maxNumberPlayers, `minimum_players_age` = :minimumPlayersAge,
        `image` = :image WHERE id=:id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':id_owner', $gameData['idGameOwner'], PDO::PARAM_INT);
        $statement->bindValue(':name', $gameData['gameName'], PDO::PARAM_STR);
        $statement->bindValue(':type', $gameData['gameGenre'], PDO::PARAM_STR);
        $statement->bindValue(':minNumberPlayers', $gameData['gameMinimumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':maxNumberPlayers', $gameData['gameMaximumNumberPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':minimumPlayersAge', $gameData['gameAgeMinimumPlayers'], PDO::PARAM_INT);
        $statement->bindValue(':image', $gameData['gameImage'], PDO::PARAM_STR);

        return $statement->execute();
    }

    public function select12Games(int $page, string $orderBy = 'name', string $direction = 'ASC'): array
    {
        $query = 'SELECT * FROM game INNER JOIN user ON user.id = game.id_owner ORDER BY '
            . $orderBy . " " . $direction . ' LIMIT 12 OFFSET ' . $page;
        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT g.id as game_id, g.name, g.type, g.minimum_players_age, g.image, g.id_owner, 
        g.min_number_players, g.max_number_players, g.availability, u.id as user_id, u.firstname, 
        u.lastname, u.email, u.password FROM ' . static::TABLE . ' as g inner join user as u on u.id = g.id_owner';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectOneGameById(int $id): array
    {
        $query = 'SELECT g.id AS game_id, g.name, g.type, g.minimum_players_age, g.image, g.id_owner, 
        g.min_number_players, g.max_number_players, g.availability, u.id AS user_id, u.firstname, 
        u.lastname, u.email, u.password FROM game AS g INNER JOIN user AS u ON u.id = g.id_owner WHERE g.id=:id;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectMyGames(int $id): array
    {
        $query = 'SELECT * FROM game as g WHERE g.id_owner=:id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function updateGameReturned(int $id): void
    {
        $query = 'UPDATE game AS g INNER JOIN borrow AS b ON b.id_game = g.id 
        SET g.availability = true, b.id_status = 4 
        WHERE g.id=:id AND b.id_status = 2;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
