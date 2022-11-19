<?php

namespace App\Controller;

use App\Model\BorrowManager;

class BorrowController extends GameController
{
    public function myAccount(): ?string
    {
        if (!isset($this->user['id'])) {
            header('Location: /users/login');
            return null;
        }

        $errors = $this->addPublicDesktop();
        $this->addBorrow();
        $pendingLoans = $this->showPendingBorrow();
        $acceptedLoans = $this->showAcceptedBorrow();
        $declinedLoans = $this->showDeclinedBorrow();
        $requestsReceived = $this->showBorrowRequests();
        $myGames = $this->showMyGames();

        return $this->twig->render(
            'Myaccount/index.html.twig',
            [
                'Pendingloans' => $pendingLoans,
                'Acceptedloans' => $acceptedLoans,
                'Declinedloans' => $declinedLoans,
                'requestsReceived' => $requestsReceived,
                'myGames' => $myGames,
                'errors' => $errors
            ]
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

    public function showDeclinedBorrow(): array|null
    {
        $borrowManager = new BorrowManager();
        $loans = $borrowManager->selectDeclinedBorrowByUserId($this->user['id']);

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
    public function showBorrowRequests(): ?array
    {
        $borrowManager = new BorrowManager();
        $requestsReceived = $borrowManager->selectBorrowRequestsReceived($this->user['id']);

        return $requestsReceived;
    }

    public function manageRequests(int $id, int $status): void
    {
        $borrowManager = new BorrowManager();
        $borrowManager->updateRequest($id, $status);

        header('Location: /myaccount');
    }

    public function giveBackGame(int $id): void
    {
        $borrowManager = new BorrowManager();
        $borrowManager->updateGameReturned($id);

        header('Location: /myaccount');
    }
}
