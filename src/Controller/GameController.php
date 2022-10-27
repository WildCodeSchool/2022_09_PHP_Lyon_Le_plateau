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
}
