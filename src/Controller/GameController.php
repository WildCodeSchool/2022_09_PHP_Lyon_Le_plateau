<?php

namespace App\Controller;

use App\Model\GameManager;

class GameController extends AbstractController
{
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
            // function verification to add //

            // if validation is ok, insert and redirection
            $gameManager = new GameManager();
            $id = $gameManager->insert($game);

            header('Location:/games/show?id=' . $id);
            return null;
        }

        return $this->twig->render('Game/add.html.twig');
    }
}
