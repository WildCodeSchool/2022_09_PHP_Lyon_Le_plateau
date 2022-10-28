<?php

namespace App\Controller;

use App\Model\GameManager;

class GameController extends AbstractController
{
    protected array $errors = [];

    public function gameFormVerification(array $gameData)
    {
            $this->gameIdOwnerVerification($gameData);
            $this->gameDescriptionVerification($gameData);
            $this->gameNumberPlayersVerification($gameData);
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

    public function gameAgePlayersVerification(array $gameData): void
    {
        if (!isset($gameData['gameAgeMinimumPlayers']) || empty($gameData['gameAgeMinimumPlayers'])) {
            $this->errors[][] = "L'age minimum des joueurs est obligatoire";
        }
        if (
            !isset($gameData['gameAgeMinimumPlayers'])
            || !filter_var(
                $gameData['gameAgeMinimumPlayers'],
                FILTER_VALIDATE_INT,
                array("options" => array("min_range" => 1, "max_range" => 99))
            )
        ) {
            $this->errors[][] = "L'age minimum des joueurs n'a pas le bon format";
        }
    }

    public function gameImageVerification(): void
    {

        if (isset($_FILES['gameImage']) || !empty($_FILES['gameImage'])) {
            if ($_FILES['gameImage']['error'] !== 0) {
                $this->errors[] = "Erreur de téléchargement de l'image du jeu";
            }
            if ($_FILES['gameImage']['size'] > 2097152) {
                $this->errors[] = "L'image du jeu est trop volumineuse";
            }
            if (strpos($_FILES['gameImage']['type'], "image/") !== 0) {
                $this->errors[] = "Le fichier téléchargé n'est pas une image";
            }
        }
    }

    public function index(): string
    {
        $gameManager = new GameManager();
        $games = $gameManager->selectAll('id');

        return $this->twig->render('Game/index.html.twig', ['games' => $games]);
    }

    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $game = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $this->gameFormVerification($game);

            // if validation is ok, insert and redirection
            $gameManager = new GameManager();
            $gameManager->insert($game);

            // Display error (to be modified for image case)
            if (!empty($this->errors)) {
                return $this->twig->render('Game/add.html.twig', ['errors' => $this->errors, 'game' => $game]);
            } else {
                $gameManager->insert($game);
                header('Location: /games/show');
                return null;
            }
        }

        return $this->twig->render('Game/add.html.twig');
    }

    public function edit(int $id): ?string
    {
        $gameManager = new GameManager();
        $game = $gameManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameData = array_map('trim', $_POST);
            $this->gameFormVerification($gameData);

            if (!empty($this->errors)) {
                return $this->twig->render('Game/edit.html.twig', ['errors' => $this->errors, 'game' => $gameData]);
            } else {
                move_uploaded_file(
                    $_FILES['gameImage']['tmp_name'],
                    '../public/uploads/' . $id . "-" . basename($_FILES['gameImage']['name'])
                );
                $gameData['gameImage'] = $id . "-" . basename($_FILES['gameImage']['name']);
                $gameManager->update($gameData, $id);
                header('Location: /games/show');
                return null;
            }
        }

        return $this->twig->render('Game/edit.html.twig', [
            'game' => $game,
        ]);
    }
}
