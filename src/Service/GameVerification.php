<?php

namespace App\Service;

class GameVerification
{
    protected array $errors = [];

    public function gameFormVerification(array $gameData)
    {
        $this->gameIdOwnerVerification($gameData);
        $this->gameDescriptionVerification($gameData);
        $this->gameNumberPlayersVerification($gameData);
        $this->gameNumberPlayersMinMaxVerification($gameData);
        $this->gameAgePlayersVerification($gameData);
        $this->gameImageVerification();


        return $this->errors;
    }

    public function gameIdOwnerVerification(array $gameData): void
    {
        if (!isset($gameData['idGameOwner']) || empty($gameData['idGameOwner'])) {
            $this->errors[] = "L'id du propriétaire du jeu est obligatoire";
        }
        if (
            !isset($gameData['idGameOwner'])
            || !filter_var($gameData['idGameOwner'], FILTER_VALIDATE_INT)
        ) {
            $this->errors[] = "L'id du propriétaire du jeu n'a pas le bon format";
        }
    }

    public function gameDescriptionVerification(array $gameData): void
    {
        if (!isset($gameData['gameName']) || empty($gameData['gameName'])) {
            $this->errors[] = "Le nom est obligatoire";
        }
        if (strlen($gameData['gameName']) > 100) {
            $this->errors[] = 'Le nom doit être inférieur à 100 caractères';
        }
        if (!isset($gameData['gameGenre']) || empty($gameData['gameGenre'])) {
            $this->errors[] = "Le genre du jeu est obligatoire";
        }
    }

    public function gameNumberPlayersVerification(array $gameData): void
    {
        if (!isset($gameData['gameMinimumNumberPlayers']) || empty($gameData['gameMinimumNumberPlayers'])) {
            $this->errors[] = "Le nombre minimum de joueurs est obligatoire";
        }
        if (
            !isset($gameData['gameMinimumNumberPlayers'])
            || !filter_var(
                $gameData['gameMinimumNumberPlayers'],
                FILTER_VALIDATE_INT,
                array("options" => array("min_range" => 1, "max_range" => 99))
            )
        ) {
            $this->errors[] = "Le nombre minimum de joueurs n'a pas le bon format";
        }
        if (!isset($gameData['gameMaximumNumberPlayers']) || empty($gameData['gameMaximumNumberPlayers'])) {
            $this->errors[] = "Le nombre maximum de joueurs est obligatoire";
        }
        if (
            !isset($gameData['gameMaximumNumberPlayers'])
            || !filter_var(
                $gameData['gameMaximumNumberPlayers'],
                FILTER_VALIDATE_INT,
                array("options" => array("min_range" => 1, "max_range" => 99))
            )
        ) {
            $this->errors[] = "Le nombre maximum de joueurs n'a pas le bon format";
        }
    }

    public function gameNumberPlayersMinMaxVerification(array $gameData)
    {
        if (($gameData['gameMinimumNumberPlayers']) > ($gameData['gameMaximumNumberPlayers'])) {
            $this->errors[] = 'Le nombre de joueurs maximum est inférieur au nombre de joueurs minimum';
        }
    }

    public function gameAgePlayersVerification(array $gameData): void
    {
        if (!isset($gameData['gameAgeMinimumPlayers']) || empty($gameData['gameAgeMinimumPlayers'])) {
            $this->errors[] = "L'age minimum des joueurs est obligatoire";
        }
        if (
            !isset($gameData['gameAgeMinimumPlayers'])
            || !filter_var(
                $gameData['gameAgeMinimumPlayers'],
                FILTER_VALIDATE_INT,
                array("options" => array("min_range" => 1, "max_range" => 99))
            )
        ) {
            $this->errors[] = "L'age minimum des joueurs n'a pas le bon format";
        }
    }

    public function gameImageVerification(): void
    {
        if ($_FILES['gameImage']['error'] !== 4) {
            switch ($_FILES['gameImage']['error']) {
                case 0:
                    if (strpos($_FILES['gameImage']['type'], "image/") !== 0) {
                        $this->errors[] = "Le fichier téléchargé n'est pas une image";
                    }
                    break;
                case 1:
                    $this->errors[] = "La taille de l'image du jeu doit être inférieure à 2Mo";
                    // no break
                default:
                    $this->errors[] = "Erreur de téléchargement de l'image du jeu";
            }
        }
    }
}
