<?php

namespace App\Controller;

use App\Model\GameManager;
use App\Service\GameVerification;

class GameController extends AbstractController
{
    protected array $errors = [];

    public function index(): string
    {
        $gameManager = new GameManager();
        $games = $gameManager->selectAll('game_id');

        return $this->twig->render('Game/index.html.twig', ['games' => $games]);
    }

    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $game = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($game);

            // Display error (to be modified for image case)
            if (!empty($errors)) {
                return $this->twig->render('Game/add.html.twig', ['errors' => $errors, 'game' => $game]);
            } else {
                $game['gameImage'] = 'default.jpg';
                if (!empty($_FILES['gameImage']['tmp_name'])) {
                    $ext = "." . pathinfo($_FILES['gameImage']['name'], PATHINFO_EXTENSION);
                    $gameImage = $game['idGameOwner'] . "_" . $game['gameName'] . "_" . uniqid() . $ext;
                    move_uploaded_file($_FILES['gameImage']['tmp_name'], '../public/uploads/' . $gameImage);
                    $game['gameImage'] = $gameImage;
                }

                $gameManager = new GameManager();
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
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($gameData);

            if (!empty($errors)) {
                return $this->twig->render('Game/edit.html.twig', ['errors' => $errors, 'game' => $gameData]);
            } else {
                $gameData['gameImage'] = $game['image'];
                if (!empty($_FILES['gameImage']['tmp_name'])) {
                    $ext = "." . pathinfo($_FILES['gameImage']['name'], PATHINFO_EXTENSION);
                    $gameImage = $gameData['idGameOwner'] . "_" . $gameData['gameName'] . "_" . uniqid() . $ext;
                    move_uploaded_file($_FILES['gameImage']['tmp_name'], '../public/uploads/' . $gameImage);
                    $gameData['gameImage'] = $gameImage;
                }
                $gameManager->update($gameData, $id);
                header('Location: /games/show');
                return null;
            }
        }

        return $this->twig->render('Game/edit.html.twig', [
            'game' => $game,
        ]);
    }

    public function gamesPages(): string
    {
        $gameManager = new GameManager();
        $games = $gameManager->selectAll('name');
        $page = ($_GET['page'] - 1) * 12;
        $selectedGames = $gameManager->select12Games($page, 'name');

        return $this->twig->render('Game/games.html.twig', ['games' => $games, 'selectedGames' => $selectedGames]);
    }
}
