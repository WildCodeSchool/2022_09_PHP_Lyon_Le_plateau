<?php

namespace App\Controller;

use App\Model\ContactManager;

class ContactController extends AbstractController
{
    protected array $errors = [];

    public function userContactFormVerification(array $userMessage)
    {
        $this->userNameVerification($userMessage);
        $this->userEmailVerification($userMessage);
        $this->userMessageVerification($userMessage);
        return $this->errors;
    }

    public function userNameVerification(array $userMessage): void
    {
        if (!isset($userMessage['userFirstname']) || empty($userMessage['userFirstname'])) {
            $this->errors[] = "Le prénom est obligatoire";
        }
        if (strlen($userMessage['userFirstname']) > 100) {
            $this->errors[] = 'Le prénom doit être inférieur à 100 caractères';
        }
        if (!isset($userMessage['userLastname']) || empty($userMessage['userLastname'])) {
            $this->errors[] = "Le nom est obligatoire";
        }
        if (strlen($userMessage['userLastname']) > 100) {
            $this->errors[] = 'Le nom doit être inférieur à 100 caractères';
        }
    }

    public function userEmailVerification(array $userMessage): void
    {
        if (!isset($userMessage['userEmail']) || empty($userMessage['userEmail'])) {
            $this->errors[] = "L'email est obligatoire";
        }
        if (!empty($userMessage['userEmail']) && !filter_var($userMessage['userEmail'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'a pas le bon format";
        }
        if (strlen($userMessage['userEmail']) > 100) {
            $this->errors[] = "L\'email doit être inférieur à 100 caractères";
        }
    }

    public function userMessageVerification(array $userMessage): void
    {
        if (!isset($userMessage['userMessage']) || empty($userMessage['userMessage'])) {
            $this->errors[] = "Le message est obligatoire";
        }
        if (strlen($userMessage['userMessage']) > 255) {
            $this->errors[] = 'Le message doit être inférieur à 255 caractères';
        }
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userMessage = array_map('trim', $_POST);
            $this->userContactFormVerification($userMessage);

            if (!empty($this->errors)) {
                return $this->twig->render('Contact/contact.html.twig', ['errors' => $this->errors]);
            } else {
                $contactManager = new ContactManager();
                $contactManager->insert($userMessage);
                $thanksNote = "Merci pour votre message !";
                return $this->twig->render('Contact/contact.html.twig', ['thanks' => $thanksNote]);
            }
        }
        return $this->twig->render('Contact/contact.html.twig');
    }

    public function index(): string|null
    {
        if (!$this->user['admin']) {
            echo 'Accès interdit';
            header('HTTP/1.1 401 Unauthorized');
            return null;
        }

        $contactManager = new ContactManager();
        $contacts = $contactManager->selectAll('isRead');

        return $this->twig->render('Contact/index.html.twig', ['contacts' => $contacts]);
    }

    public function changeReadStatus(int $id): void
    {
        $contactManager = new ContactManager();
        $userMessage = $contactManager->selectOneById($id);

        if ($userMessage['isRead'] == true) {
            $contactManager->updateAsNotRead($id);
        } else {
            $contactManager->updateAsRead($id);
        }

        header('Location: /contact/show');
    }
}
