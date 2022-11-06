<?php

namespace App\Controller;

class ContactController extends AbstractController
{
    /**
     * Display constact page
     */
    public function contact(): string
    {
        return $this->twig->render('Contact/contact.html.twig');
    }
}
