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
        $productManager = new ProductManager();
        $filters = [];

        // Filtre par catégorie
        if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
            $filters['category_id'] = (int) $_GET['category_id'];
        }

        $products = $productManager->findByFilter($filters);
        $categories = [
            1 => 'Résines',
            2 => 'Entretien',
            3 => 'Outillage'
        ];

        // Affiche le catalogue complet avec les produits
        $view = new View("Catalogue");
        $view->render("catalogue", [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $filters['category_id'] ?? null
        ]);
    }

    public function showCategory(string $categoryName)
    {
        // Affiche une catégorie spécifique (Résines, Entretien, Outillage)
        $categoryId = null;
        $filterColumn = null;

        switch (strtolower($categoryName)) {
            case 'resines':
                $categoryId = 1;
                $filterColumn = 'color';
                break;
            case 'entretien':
                $categoryId = 2;
                $filterColumn = 'scent';
                break;
            case 'outillage':
                $categoryId = 3;
                $filterColumn = 'tool_type';
                break;
        }

        $products = [];
        $filterOptions = [];

        if ($categoryId !== null) {
            $productManager = new ProductManager();
            $filters = ['category_id' => $categoryId];

            // Gestion des filtres spécifiques
            if ($filterColumn) {
                // Récupération des options de filtre disponibles
                $filterOptions = $productManager->getDistinctValues($filterColumn);

                // Application du filtre si sélectionné
                if (isset($_GET[$filterColumn]) && !empty($_GET[$filterColumn])) {
                    $filters[$filterColumn] = $_GET[$filterColumn];
                }
            }

            $products = $productManager->findByFilter($filters);
        }

        $view = new View(ucfirst($categoryName));
        $view->render($categoryName, [
            'categoryName' => $categoryName,
            'products' => $products,
            'filterOptions' => $filterOptions,
            'currentFilter' => $_GET[$filterColumn] ?? null
        ]);
    }
}
