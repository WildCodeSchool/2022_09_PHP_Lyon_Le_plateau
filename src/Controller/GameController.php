<?php

namespace App\Controller;

use App\Model\GameManager;
use App\Model\UserManager;
use App\Service\GameVerification;

class GameController extends AbstractController
{
    protected array $errors = [];

    public function index(): string|null
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            echo 'Accès interdit';
            header('HTTP/1.1 401 Unauthorized');
            return null;
        }

        $gameManager = new GameManager();
        $games = $gameManager->selectAll('game_id');

        return $this->twig->render('Game/index.html.twig', ['games' => $games]);
    }

    public function addAdmin(): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            echo 'Accès interdit';
            header('HTTP/1.1 401 Unauthorized');
            return null;
        }

        $userManager = new UserManager();
        $users = $userManager->selectAll('firstname');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $game = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($game);

            // Display error (to be modified for image case)
            if (!empty($errors)) {
                return $this->twig->render('Game/addAdmin.html.twig', ['errors' => $errors, 'game' => $game]);
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

        return $this->twig->render('Game/addAdmin.html.twig', ['users' => $users]);
    }

    public function addPublic()
    {
        if (!isset($this->user['admin'])) {
            header('Location: /users/login');
            return null;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $game = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($game);

            // Display error (to be modified for image case)
            if (!empty($errors)) {
                return $this->twig->render('Game/addPublic.html.twig', ['errors' => $errors, 'game' => $game]);
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
                header('Location: /games?page=1');
                return null;
            }
        }
    }


    public function editAdmin(int $id): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            echo 'Accès interdit';
            header('HTTP/1.1 401 Unauthorized');
            return null;
        }

        $gameManager = new GameManager();
        $game = $gameManager->selectOneGameById($id);
        $userManager = new UserManager();
        $users = $userManager->selectAll('firstname');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameData = array_map('trim', $_POST);
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($gameData);

            if (!empty($errors)) {
                $gameData = "";
                return $this->twig->render('Game/editAdmin.html.twig', [
                    'errors' => $errors, 'game' => $game,
                    'users' => $users
                ]);
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

        return $this->twig->render('Game/editAdmin.html.twig', [
            'game' => $game,
            'users' => $users,
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

    public function showMyGames(): string
    {
        $gameManager = new GameManager();
        $myGames = $gameManager->selectMyGames($this->user['id']);

        return $this->twig->render('Myaccount/_mygames.html.twig', ['myGames' => $myGames]);
    }

    public function returnGame(int $id)
    {
        $gameManager = new GameManager();
        $gameManager->returnGame($id);

        header('Location: /mygames');
    }
}
