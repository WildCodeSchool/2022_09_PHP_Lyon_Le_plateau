<?php

namespace App\Service;

use App\Model\UserManager;

class UserVerification
{
    protected array $errors = [];

    public function userFormVerification(array $userData): array
    {
        $this->userNameVerification($userData);
        $this->userEmailEditVerification($userData);
        $this->userPasswordVerification($userData);
        $this->userPasswordConfirmationVerification($userData);
        return $this->errors;
    }

    public function userNameVerification(array $userData): array
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
        return $this->errors;
    }

    public function userEmailVerification(array $userData): array
    {
        if (!isset($userData['userEmail']) || empty($userData['userEmail'])) {
            $this->errors[] = "L'email est obligatoire";
        }

        if (!empty($userData['userEmail']) && !filter_var($userData['userEmail'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'a pas le bon format";
        }

        if (strlen($userData['userEmail']) > 100) {
            $this->errors[] = 'L\'email doit être inférieur à 100 caractères';
        }
        $userManager = new UserManager();
        $user = $userManager->uniqueEmail($userData['userEmail']);
        if ($user == true) {
            $this->errors[] = 'L\'email existe déja';
        }
        return $this->errors;
    }

    public function userEmailEditVerification(array $userData): array
    {
        if (!empty($userData['id'])) {
            $userManager = new UserManager();
            $user = $userManager->selectOneById($userData['id']);
            if ($userData['userEmail'] !== ($user['email'])) {
                $this->userEmailVerification($userData);
            }
        } else {
            $this->userEmailVerification($userData);
        }
        return $this->errors;
    }

    public function userPasswordVerification(array $userData): array
    {
        if (!isset($userData['userPassword']) || empty($userData['userPassword'])) {
            $this->errors[] = "Le mot de passe est obligatoire";
        }

        if (!empty($userData['userPassword']) && strlen($userData['userPassword']) < 8) {
            $this->errors[] = 'Le mot de passe doit être supérieur à 8 caractères';
        }

        if (
            !empty($userData['userPassword']) &&
            strlen(filter_var($userData['userPassword'], FILTER_SANITIZE_NUMBER_INT)) == 0
        ) {
            $this->errors[] = 'Le mot de passe doit contenir au moins un chiffre';
        }

        if (!empty($userData['userPassword']) && ctype_alnum($userData['userPassword'])) {
            $this->errors[] = 'Le mot de passe doit contenir au moins un caractère spécial';
        }

        return $this->errors;
    }

    public function userPasswordConfirmationVerification(array $userData): array
    {

        if (!isset($userData['userPasswordConfirmation']) || empty($userData['userPasswordConfirmation'])) {
            $this->errors[] = "La confirmation du mot de passe est obligatoire";
        }

        if (
            !empty($userData['userPasswordConfirmation']) &&
            $userData['userPasswordConfirmation'] !== $userData['userPassword']
        ) {
            $this->errors[] = "Les mots de passe doivent être identiques";
        }

        return $this->errors;
    }
}
