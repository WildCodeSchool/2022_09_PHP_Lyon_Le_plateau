<?php

namespace App\Controller;

use App\Model\BorrowManager;

class BorrowController extends GameController
{
    public function indexBorrow(): string|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->SelectAllBorrowByUserId($this->user['id']);

        return $this->twig->render('Myaccount/index.html.twig', ['loans' => $loans]);
    }

    public function addBorrow(): string|null
    {
        if (!isset($this->user['id'])) {
            header('Location: /users/login');
            return null;
        }

        if (!empty($_GET['game_id'])) {
            $borrowManager = new BorrowManager();
            $borrowManager->insertBorrow($_GET['game_id'], $this->user['id']);

            header('Location: /myaccount');
            return null;
        }

        return $this->indexBorrow();
    }
}
