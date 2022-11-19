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
            . " SET `id_owner` = :id_owner, `name` = :name, `type` = :type, `availability` = :availability,
             `min_number_players` = :minNumberPlayers,
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
        $statement->bindValue(':availability', $gameData['gameAvailability'], PDO::PARAM_INT);
        $statement->bindValue(':image', $gameData['gameImage'], PDO::PARAM_STR);

        return $statement->execute();
    }

    public function select12Games(int $page, ?int $user, string $orderBy = 'name', string $direction = 'ASC'): array
    {
        $query = 'SELECT g.id as game_id, g.name, g.type, g.minimum_players_age, g.image, g.id_owner, 
        g.min_number_players, g.max_number_players, g.availability, u.id as user_id, u.firstname, 
        u.lastname, u.email, u.password FROM game AS g INNER JOIN user AS u ON u.id = g.id_owner';
        if ($user != null) {
            $query .= ' WHERE u.id <> ' . $user;
        }
        $query .= ' ORDER BY ' . $orderBy . " " . $direction . ' LIMIT 12 OFFSET ' . $page;
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
        $query = 'SELECT g.id AS game_id, g.name, g.type, g.minimum_players_age, g.image, g.id_owner, 
        g.min_number_players, g.max_number_players, g.availability, u.id AS user_id, u.firstname, 
        u.lastname, u.email, u.password, b.id AS borrow_id, b.id_game, b.id_user, b.id_status FROM game as g
        INNER JOIN user as u ON g.id_owner = u.id
        LEFT JOIN borrow as b ON b.id_game = g.id
        WHERE g.id_owner=:id ORDER BY g.name ASC';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function changeAvailability(int $id, int $availability): void
    {
        $query = 'UPDATE game AS g SET g.availability = :availability WHERE g.id=:id;';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':availability', $availability, \PDO::PARAM_BOOL);
        $statement->execute();
    }
}
