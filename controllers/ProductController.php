<?php

class ProductController
{
    public function showHome()
    {
        // Affiche la page d'accueil
        $view = new View("Accueil");
        $view->render("home");
    }

    public function showCatalogue()
    {
        // Affiche le catalogue complet
        $view = new View("Catalogue");
        $view->render("catalogue");
    }

    public function showCategory(string $categoryName)
    {
        // Affiche une catégorie spécifique (Résines, Entretien, Outillage)
        $view = new View(ucfirst($categoryName));
        $view->render($categoryName, ['categoryName' => $categoryName]);
    }
}
