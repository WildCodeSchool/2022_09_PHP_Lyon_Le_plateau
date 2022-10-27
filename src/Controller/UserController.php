<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('id');

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }
}
