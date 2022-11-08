<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    protected array $errors = [];

    public function userFormVerification(array $userData)
    {
        $this->userNameVerification($userData);
        if (!empty($userData['id'])) {
            $userManager = new UserManager();
            $user = $userManager->selectOneById($userData['id']);
            if ($userData['userEmail'] !== ($user['email'])) {
                $this->userEmailVerification($userData);
            }
        } else {
            $this->userEmailVerification($userData);
        }
        $this->userPasswordVerification($userData);
        return $this->errors;
    }

    public function userNameVerification(array $userData): void
    {
        if (!isset($userData['userFirstname']) || empty($userData['userFirstname'])) {
            $this->errors[] = "Le prénom est obligatoire";
        }
        if (strlen($userData['userFirstname']) > 100) {
            $this->errors[] = 'Le prénom doit être inférieur à 100 caractères';
        }
        if (!isset($userData['userLastname']) || empty($userData['userLastname'])) {
            $this->errors[] = "Le nom est obligatoire";
        }
        if (strlen($userData['userLastname']) > 100) {
            $this->errors[] = 'Le nom doit être inférieur à 100 caractères';
        }
    }

    public function userEmailVerification(array $userData): void
    {
        if (!isset($userData['userEmail']) || !filter_var($userData['userEmail'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'a pas le bon format";
        }
        if (strlen($userData['userEmail']) > 100) {
            $this->errors[] = 'L\'email doit être inférieur à 100 caractères';
        }
        $userManager = new UserManager();
        $uniqueEmail = $userManager->uniqueEmail($userData['userEmail']);
        if ($uniqueEmail == true) {
            $this->errors[] = 'L\'email existe déja';
        }
    }


    public function userPasswordVerification(array $userData): void
    {
        if (!isset($userData['userPassword']) || empty($userData['userPassword'])) {
            $this->errors[] = "Le mot de passe est obligatoire";
        }
        if (strlen($userData['userPassword']) < 8) {
            $this->errors[] = 'Le mot de passe doit être supérieur à 8 caractères';
        }
        if (strlen(filter_var($userData['userPassword'], FILTER_SANITIZE_NUMBER_INT)) == 0) {
            $this->errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }
        if (strlen(filter_var($userData['userPassword'], FILTER_SANITIZE_SPECIAL_CHARS)) == 0) {
            $this->errors[] = 'Le mot de passe doit contenir au moins un caractère spécial';
        }
        if (!isset($userData['userPasswordConfirmation']) || empty($userData['userPasswordConfirmation'])) {
            $this->errors[] = "La confirmation du mot de passe est obligatoire";
        }
        if ($userData['userPasswordConfirmation'] !== $userData['userPassword']) {
            $this->errors[] = "Les mots de passe doivent être identiques";
        }
    }

    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('id');

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }

    public function edit(int $id): ?string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = array_map('trim', $_POST);
            $this->userFormVerification($userData);

            if (!empty($this->errors)) {
                return $this->twig->render('User/edit.html.twig', ['errors' => $this->errors, 'user' => $user]);
            } else {
                $userManager->update($userData, $id);
                header('Location: /users/show');
                return null;
            }
        }
        return $this->twig->render('User/edit.html.twig', ['user' => $user]);
    }

    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $userData = array_map('trim', $_POST);

            // TODO validations (length, format...)
            $this->userFormVerification($userData);

            // Display error (to be modified for image case)
            if (!empty($this->errors)) {
                return $this->twig->render('User/add.html.twig', ['errors' => $this->errors, 'user' => $userData]);
            } else {
                $userManager = new UserManager();
                $userManager->insert($userData);
                header('Location: /users/show');
                return null;
            }
        }

        return $this->twig->render('User/add.html.twig');
    }
}
