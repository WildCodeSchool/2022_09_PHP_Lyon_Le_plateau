<?php

namespace App\Controller;

use App\Model\GameManager;
use App\Model\UserManager;
use App\Service\GameVerification;
use App\Model\BorrowManager;

class GameController extends AbstractController
{
    protected array $errors = [];

    public function index(): string|null
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $gameManager = new GameManager();
        $games = $gameManager->selectAll('game_id');

        return $this->twig->render('Game/index.html.twig', ['games' => $games]);
    }

    public function addAdmin(): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
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
        if (!isset($this->user['id'])) {
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
                header('Location: /myaccount');
                return null;
            }
        }
        return $this->twig->render('Game/addPublic.html.twig');
    }

    public function addPublicDesktop(): array|null
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $game = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $gameVerification = new GameVerification();
            $errors = $gameVerification->gameFormVerification($game);

            // Display error (to be modified for image case)
            if (!empty($errors)) {
                return $errors;
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
                return null;
            }
        }
        return null;
    }

    public function editAdmin(int $id): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
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

    public function editPublic(int $id): ?string
    {
        if (!isset($this->user['id'])) {
            return $this->twig->render('errors/error.html.twig');
        }

        $gameManager = new GameManager();
        $game = $gameManager->selectOneGameById($id);

        if ($this->user['id'] != $game['id_owner']) {
            return $this->twig->render('errors/error.html.twig');
        }

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
                header('Location: /myaccount#myGames');
                return null;
            }
        }

        return $this->twig->render('Game/editPublic.html.twig', [
            'game' => $game,
            'users' => $users,
        ]);
    }

    public function gamesPages(): string
    {
        $gameManager = new GameManager();
        $games = $gameManager->selectAll('name');

        $count = count($games);
        $nbpage = ceil($count / 12);

        if ($_GET['page'] < 1 || $_GET['page'] > $nbpage) {
            return $this->twig->render('errors/error.html.twig');
        }
        $page = ($_GET['page'] - 1) * 12;
        $user = null;
        $requestedGames = [];

        if (isset($this->user['id'])) {
            $user = $this->user['id'];
            $borrowManager = new BorrowManager();
            $borrows = $borrowManager->selectPendingBorrowByUserId($user);
            foreach ($borrows as $borrow) {
                $requestedGames[] = $borrow['id_game'];
            }
        }
        $selectedGames = $gameManager->select12Games($page, $user);

        return $this->twig->render('Game/games.html.twig', [
            'games' => $games,
            'selectedGames' => $selectedGames,
            'requestedGames' => $requestedGames,
            'nbpage' => $nbpage
        ]);
    }

    public function showMyGames(): array|null
    {
        $gameManager = new GameManager();
        $myGames = $gameManager->selectMyGames($this->user['id']);

        return $myGames;
    }

    public function editGameAvailability(int $id, int $availability): string|null
    {
        $gameManager = new GameManager();
        $game = $gameManager->selectOneGameById($id);

        if ($this->user['id'] != $game['id_owner']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $gameManager->changeAvailability($id, $availability);
        header('Location: /myaccount#myGames');
        return null;
    }
}
