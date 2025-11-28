<?php

class UserController
{
    public function showAccount()
    {
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        if (!isset($_SESSION['user'])) {
            Utils::redirect("loginForm");
        }

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
