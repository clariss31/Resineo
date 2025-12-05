<?php

class HomeController
{
    public function showHome()
    {
        // Affiche la page d'accueil
        $view = new View("Accueil");
        $view->render("home");
    }
}
