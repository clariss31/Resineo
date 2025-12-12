<?php

class ProductController
{
    public function showCatalogue()
    {
        $productManager = new ProductManager();
        $filters = [];

        // Filtres
        if (isset($_GET['categories']) && is_array($_GET['categories'])) {
            $filters['category_id'] = $_GET['categories'];
        } elseif (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
            $filters['category_id'] = (int) $_GET['category_id'];
        }

        if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
            $filters['min_price'] = (float) $_GET['min_price'];
        }

        if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
            $filters['max_price'] = (float) $_GET['max_price'];
        }

        // Tri
        if (isset($_GET['sort'])) {
            $parts = explode('-', $_GET['sort']);
            if (count($parts) === 2) {
                $filters['order_by'] = $parts[0];
                $filters['direction'] = $parts[1];
            }
        }

        // Récupération des produits filtrés
        $products = $productManager->findByFilter($filters);

        // Récupération des prix min/max (pour le slider, basé sur les catégories sélectionnées uniquement)
        $rangeFilters = [];
        if (isset($filters['category_id'])) {
            $rangeFilters['category_id'] = $filters['category_id'];
        }
        $priceRange = $productManager->getMinMaxPrices($rangeFilters);

        $categories = [
            1 => 'Résines',
            2 => 'Entretien',
            3 => 'Outillage'
        ];

        // Fetch distinct values for Admin Selects
        $distinctColors = $productManager->getDistinctValues('color');
        $distinctToolTypes = $productManager->getDistinctValues('tool_type');

        // Affiche le catalogue complet avec les produits et filtres
        $view = new View("Catalogue");
        $view->render("catalogue", [
            'products' => $products,
            'categories' => $categories,
            'currentCategories' => $filters['category_id'] ?? [],
            'currentMinPrice' => $filters['min_price'] ?? $priceRange['min_price'],
            'currentMaxPrice' => $filters['max_price'] ?? $priceRange['max_price'],
            'minPrice' => $priceRange['min_price'],
            'maxPrice' => $priceRange['max_price'],
            'currentSort' => $_GET['sort'] ?? '',
            'distinctColors' => $distinctColors,       // Pass specific options
            'distinctToolTypes' => $distinctToolTypes  // Pass specific options
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

        $filters = [];
        if ($categoryId !== null) {
            $filters['category_id'] = $categoryId;
        }

        $productManager = new ProductManager();

        // 1. Gestion des filtres spécifiques (Couleur, Type, etc.)
        $filterOptions = [];
        if ($filterColumn) {
            $filterOptions = $productManager->getDistinctValues($filterColumn);
            if (isset($_GET[$filterColumn]) && !empty($_GET[$filterColumn])) {
                $filters[$filterColumn] = $_GET[$filterColumn];
            }
        }

        // 2. Gestion du filtre Prix
        if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
            $filters['min_price'] = (float) $_GET['min_price'];
        }
        if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
            $filters['max_price'] = (float) $_GET['max_price'];
        }

        // 3. Gestion du Tri
        if (isset($_GET['sort'])) {
            $parts = explode('-', $_GET['sort']);
            if (count($parts) === 2) {
                $filters['order_by'] = $parts[0];
                $filters['direction'] = $parts[1];
            }
        }

        // 4. Récupération des produits filtrés
        $products = $productManager->findByFilter($filters);

        // 5. Récupération des prix min/max pour le slider (borné à la catégorie)
        $rangeFilters = [];
        if ($categoryId !== null) {
            $rangeFilters['category_id'] = $categoryId;
        }
        $priceRange = $productManager->getMinMaxPrices($rangeFilters);


        $view = new View(ucfirst($categoryName));
        $view->render($categoryName, [
            'categoryName' => $categoryName,
            'products' => $products,
            'filterOptions' => $filterOptions,
            'currentFilter' => $_GET[$filterColumn] ?? null,
            'currentMinPrice' => $filters['min_price'] ?? $priceRange['min_price'],
            'currentMaxPrice' => $filters['max_price'] ?? $priceRange['max_price'],
            'minPrice' => $priceRange['min_price'],
            'maxPrice' => $priceRange['max_price'],
            'currentSort' => $_GET['sort'] ?? ''
        ]);
    }

    public function showProduct()
    {
        $id = (int) Utils::request('id');
        if (!$id) {
            throw new Exception("Aucun identifiant de produit spécifié.");
        }

        $productManager = new ProductManager();
        $product = $productManager->findOneById($id);

        if (!$product) {
            throw new Exception("Le produit demandé n'existe pas.");
        }

        // Mapping for Breadcrumbs / Category Name
        $categories = [
            1 => 'Résines',
            2 => 'Entretien',
            3 => 'Outillage'
        ];
        $categoryName = $categories[$product->getCategoryId()] ?? 'Catalogue';

        $view = new View($product->getName());
        $view->render('detail', [
            'product' => $product,
            'categoryName' => $categoryName
        ]);
    }

    public function addProduct()
    {
        // Vérification Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            $_SESSION['flash'] = "Accès refusé.";
            Utils::redirect('catalogue');
        }

        $productManager = new ProductManager();

        // Récupération des données du formulaire
        $name = Utils::request('name');
        $description = Utils::request('description');
        $categoryId = (int) Utils::request('category_id');
        $price = (float) Utils::request('price');

        // Gestion dynamique des champs
        $color = null;
        $scent = null;
        $toolType = null;

        if ($categoryId === 1) { // Résines
            $color = Utils::request('color');
        } elseif ($categoryId === 2) { // Entretien
            $noScent = Utils::request('no_scent');
            if ($noScent) {
                $scent = "Sans odeur";
            } else {
                $scent = Utils::request('scent');
            }
        } elseif ($categoryId === 3) { // Outillage
            $toolType = Utils::request('tool_type');
        }

        // Upload Image
        $imagePath = 'img/camera-icon.png'; // Default
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'img/';
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            }
        }

        $product = new Product();
        $product->setCategoryId($categoryId);
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setImage($imagePath);
        $product->setColor($color);
        $product->setScent($scent);
        $product->setToolType($toolType);

        if ($productManager->create($product)) {
            $_SESSION['flash'] = "Produit ajouté avec succès !";
        } else {
            $_SESSION['flash'] = "Erreur lors de l'ajout.";
        }

        Utils::redirect('catalogue');
    }
}
