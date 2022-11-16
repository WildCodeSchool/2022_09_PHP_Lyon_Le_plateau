<?php

namespace App\Controller;

use App\Model\BorrowManager;

class BorrowController extends GameController
{
    public function myAccount(): string|null
    {
        if (!isset($this->user['id'])) {
            header('Location: /users/login');
            return null;
        }
        $this->addPublic();
        $this->addBorrow();
        $pendingLoans = $this->showPendingBorrow();
        $acceptedLoans = $this->showAcceptedBorrow();
        $declineLoans = $this->showDeclineBorrow();

        return $this->twig->render(
            'Myaccount/index.html.twig',
            ['Pendingloans' => $pendingLoans, 'Acceptedloans' => $acceptedLoans, 'Declineloans' => $declineLoans]
        );
    }

    public function showAllBorrow(): array|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->SelectAllBorrowByUserId($this->user['id']);

        return $loans;
    }

    public function showPendingBorrow(): array|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->selectPendingBorrowByUserId($this->user['id']);

        return $loans;
    }

    public function showAcceptedBorrow(): array|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->selectAcceptedBorrowByUserId($this->user['id']);

        return $loans;
    }

    public function showDeclineBorrow(): array|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->selectDeclineBorrowByUserId($this->user['id']);

        return $loans;
    }

    public function addBorrow()
    {
        if (!empty($_GET['game_id'])) {
            $borrowManager = new BorrowManager();
            $borrowManager->insertBorrow($_GET['game_id'], $this->user['id']);

            header('Location: /myaccount');
            return null;
        }
    }
}
