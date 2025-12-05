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
        // Récupération de tous les produits
        $productManager = new ProductManager();
        $products = $productManager->findAll();

        // Affiche le catalogue complet avec les produits
        $view = new View("Catalogue");
        $view->render("catalogue", ['products' => $products]);
    }

    public function showCategory(string $categoryName)
    {
        // Affiche une catégorie spécifique (Résines, Entretien, Outillage)
        $categoryId = null;
        switch (strtolower($categoryName)) {
            case 'resines':
                $categoryId = 1;
                break;
            case 'entretien':
                $categoryId = 2;
                break;
            case 'outillage':
                $categoryId = 3;
                break;
        }

        $products = [];
        if ($categoryId !== null) {
            $productManager = new ProductManager();
            $products = $productManager->findByFilter(['category_id' => $categoryId]);
        }

        $view = new View(ucfirst($categoryName));
        $view->render($categoryName, ['categoryName' => $categoryName, 'products' => $products]);
    }
}
