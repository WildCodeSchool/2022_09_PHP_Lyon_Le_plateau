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
        $pendingLoans = $this->showPendingBorrow();
        $acceptedLoans = $this->showAcceptedBorrow();
        $declinedLoans = $this->showDeclinedBorrow();
        $requestsReceived = $this->showBorrowRequests();
        $dateNow = time();
        $myGames = $this->showMyGames();

        return $this->twig->render(
            'Myaccount/index.html.twig',
            [
                'pendingLoans' => $pendingLoans,
                'acceptedLoans' => $acceptedLoans,
                'declinedLoans' => $declinedLoans,
                'requestsReceived' => $requestsReceived,
                'dateNow' => $dateNow,
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

    public function addBorrow(int $id)
    {
        if (!isset($this->user['id'])) {
            header('Location: /users/login');
            return null;
        }
        $borrowManager = new BorrowManager();
        $borrowManager->insertBorrow($id, $this->user['id']);

        header('Location: /myaccount#pendingLoans');
        return null;
    }

    public function cancelBorrow(int $id): string|null
    {
        $borrowManager = new BorrowManager();
        $borrow = $borrowManager->selectOneById($id);

        if ($this->user['id'] != $borrow['id_user']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $borrowManager->updateBorrowStatusAsOver($id);
        header('Location: /myaccount#pendingLoans');
        return null;
    }


    public function showBorrowRequests(): ?array
    {
        $borrowManager = new BorrowManager();
        $requestsReceived = $borrowManager->selectBorrowRequestsReceived($this->user['id']);

        return $requestsReceived;
    }

    public function manageRequests(int $id, int $status): string|null
    {
        $borrowManager = new BorrowManager();
        $borrow = $borrowManager->selectOneBorrowById($id);

        if ($this->user['id'] != $borrow['id_owner']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $borrowManager->updateRequest($id, $status);
        header('Location: /myaccount#requestsReceived');
        return null;
    }

    public function giveBackGame(int $id): string|null
    {
        $borrowManager = new BorrowManager();
        $borrow = $borrowManager->selectOneBorrowById($id);

        if ($this->user['id'] != $borrow['owner_id']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $borrowManager->updateGameReturned($id);
        header('Location: /myaccount#myGames');
        return null;
    }
}
