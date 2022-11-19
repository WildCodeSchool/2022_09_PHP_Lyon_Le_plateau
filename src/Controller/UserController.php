<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\UserVerification;

class UserController extends AbstractController
{
    protected array $errors = [];

    public function index(): string|null
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $userManager = new UserManager();
        $users = $userManager->selectAll('id');

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }

    public function addAdmin(): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);

            $userVerification = new UserVerification();
            $errors = $userVerification->userFormVerification($user);

            if (!empty($errors)) {
                return $this->twig->render('User/addAdmin.html.twig', ['errors' => $errors, 'user' => $user]);
            } else {
                $userManager = new UserManager();
                $userManager->insert($user);
                header('Location: /users/show');
                return null;
            }
        }

        return $this->twig->render('User/addAdmin.html.twig');
    }

    public function editAdmin(int $id): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = array_map('trim', $_POST);

            $userVerification = new UserVerification();
            $errors = $userVerification->userNameVerification($userData);
            $errors = $userVerification->userEmailEditVerification($userData);

            if (!empty($errors)) {
                return $this->twig->render('User/editAdmin.html.twig', ['errors' => $errors, 'user' => $user]);
            } else {
                $userManager->updateUser($userData, $id);
                header('Location: /users/show');
                return null;
            }
        }
        return $this->twig->render('User/editAdmin.html.twig', ['user' => $user]);
    }

    public function editPasswordAdmin(int $id): ?string
    {
        if (!isset($this->user['admin']) || !$this->user['admin']) {
            return $this->twig->render('errors/error.html.twig');
        }

        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = array_map('trim', $_POST);
            $user = $userManager->selectOneById($id);

            $userVerification = new UserVerification();
            $errors = $userVerification->userPasswordVerification($userData);
            $errors = $userVerification->userPasswordConfirmationVerification($userData);

            if (!empty($errors)) {
                return $this->twig->render('User/editPasswordAdmin.html.twig', ['user' => $user, 'errors' => $errors]);
            } else {
                $userManager->updatePassword($userData, $id);
                header('Location: /users/show');
                return null;
            }
        }

        return $this->twig->render('User/editPasswordAdmin.html.twig', ['user' => $user]);
    }

    public function login(): string|null
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataConnexion = array_map('trim', $_POST);
            $userManager = new UserManager();
            $user = $userManager->selectOneByEmail($dataConnexion['userEmail']);

            if ($user && password_verify($dataConnexion['userPassword'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $user['admin'] ? header('Location: /games/show') : header('Location: /myaccount');
            }
            $error = 'Erreur d\'email ou mot de passe';
        }
        return $this->twig->render('User/login.html.twig', ['error' => $error]);
    }

    public function logout()
    {
        session_unset();
        header('Location: /');
    }
}
