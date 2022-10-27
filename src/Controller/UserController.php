<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    protected array $errors = [];

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
        if (!isset($userData['userPassword']) || empty($userData['userPassword'])) {
            $this->errors[] = "Le mot de passe est obligatoire";
        }
        if (strlen($userData['userPassword']) < 8) {
            $this->errors[] = 'Le mot de passe doit être supérieur à 8 caractères';
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

    public function userFormVerification()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = array_map('trim', $_POST);

            $this->userNameVerification($userData);
            $this->userEmailVerification($userData);
            $this->userPasswordVerification($userData);
            return $this->errors;
        }
    }

    public function index(): string
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll('id');

        return $this->twig->render('User/index.html.twig', ['users' => $users]);
    }
}
