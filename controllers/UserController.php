<?php

class UserController
{
    public function showAccount()
    {
        // Affiche la page "Mon compte"
        $view = new View("Mon Compte");
        $view->render("account");
    }

    public function showMessaging()
    {
        // Affiche la messagerie
        $view = new View("Messagerie");
        $view->render("messaging");
    }
}
