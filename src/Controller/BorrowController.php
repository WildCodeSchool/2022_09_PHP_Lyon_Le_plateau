<?php

namespace App\Controller;

use App\Model\BorrowManager;

class BorrowController extends GameController
{
    public function borrowRequests(): ?string
    {
        if (!isset($this->user['id'])) {
            header('Location: /users/login');
            return null;
        }

        $borrowManager = new BorrowManager();
        $requestsReceived = $borrowManager->selectBorrowRequestsReceived($this->user['id']);

        return $this->twig->render('/Myaccount/requestsReceived.html.twig', ['requestsReceived' => $requestsReceived]);
    }

    public function manageRequests(int $id, int $acceptance): void
    {
        $borrowManager = new BorrowManager();
        $borrowManager->updateRequest($id, $acceptance);

        header('Location: /myaccount');
    }
}
