<?php

class HomeController
{
    /**
     * Affiche la page d'accueil avec les derniers produits.
     * @return void
     */
    public function showHome()
    {
        $productManager = new ProductManager();
        $newProducts = $productManager->findLatest(4);

        // Affiche la page d'accueil
        $view = new View("Accueil");
        $view->render("home", ['newProducts' => $newProducts]);
    }
}
