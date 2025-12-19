<?php

class ProductController
{
    /**
     * Affiche le catalogue complet des produits.
     * Gère les filtres (catégorie, prix, tri).
     * @return void
     */
    public function showCatalogue()
    {
        $productManager = new ProductManager();
        $filters = [];

        // Filtres de recherche
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

        // Tri des résultats
        if (isset($_GET['sort'])) {
            $parts = explode('-', $_GET['sort']);
            if (count($parts) === 2) {
                $filters['order_by'] = $parts[0];
                $filters['direction'] = $parts[1];
            }
        }

        // Récupération des produits selon les filtres
        $products = $productManager->findByFilter($filters);

        // Récupération des plages de prix pour le slider (basé sur les catégories sélectionnées uniquement)
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

        // Récupération des valeurs distinctes pour les listes déroulantes de l'administration
        $distinctColors = $productManager->getDistinctValues('color');
        $distinctToolTypes = $productManager->getDistinctValues('tool_type');

        // Affiche le catalogue complet avec les produits et filtres
        $view = new View("Catalogue");
        $view->render("catalogue", [
            'products' => $products,
            'categories' => $categories,
            'currentCategories' => $filters['category_id'] ?? [], // Tableau vide si aucun filtre de catégorie
            'currentMinPrice' => $filters['min_price'] ?? $priceRange['min_price'],
            'currentMaxPrice' => $filters['max_price'] ?? $priceRange['max_price'],
            'minPrice' => $priceRange['min_price'],
            'maxPrice' => $priceRange['max_price'],
            'currentSort' => $_GET['sort'] ?? '',
            'distinctColors' => $distinctColors,       // Passer les options spécifiques
            'distinctToolTypes' => $distinctToolTypes  // Passer les options spécifiques
        ]);
    }

    /**
     * Affiche les produits d'une catégorie spécifique.
     * Configure automatiquement les filtres appropriés (couleur, odeur, type).
     * @param string $categoryName Le nom de la catégorie (Resines, Entretien, Outillage)
     * @return void
     */
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

        // 4. Récupération des produits selon les filtres
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

    /**
     * Affiche la page de détail d'un produit.
     * @return void
     * @throws Exception Si l'ID est manquant ou le produit introuvable.
     */
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

        // Correspondance pour le fil d'ariane / Nom de catégorie
        $categories = [
            1 => 'Résines',
            2 => 'Entretien',
            3 => 'Outillage'
        ];
        $categoryName = $categories[$product->getCategoryId()] ?? 'Catalogue';

        // Données pour les listes déroulantes de modification
        $distinctColors = $productManager->getDistinctValues('color');
        $distinctToolTypes = $productManager->getDistinctValues('tool_type');

        $view = new View($product->getName());
        $view->render('detail', [
            'product' => $product,
            'categoryName' => $categoryName,
            'distinctColors' => $distinctColors,
            'distinctToolTypes' => $distinctToolTypes
        ]);
    }

    /**
     * Met à jour les informations d'un produit.
     * Nécessite des droits d'administrateur.
     * @return void
     * @throws Exception Si le produit est introuvable.
     */
    public function updateProduct()
    {
        // Vérification des droits d'administrateur
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            header('Location: index.php?action=home');
            exit();
        }

        $id = (int) Utils::request('id');
        $productManager = new ProductManager();
        $product = $productManager->findOneById($id);

        if (!$product) {
            throw new Exception("Produit introuvable.");
        }

        // Mise à jour des champs de base
        $product->setName(Utils::request('name'));
        $product->setDescription(Utils::request('description'));
        $product->setPrice((float) Utils::request('price'));
        $product->setCategoryId((int) Utils::request('category_id'));

        $product->setColor(Utils::request('color') ?: null);

        // Gestion Odeur
        $noScent = Utils::request('no_scent'); // Valeur attendue : 'yes' (Sans odeur = Oui) ou 'no' (Sans odeur = Non)
        if ($product->getCategoryId() === 2) {
            if ($noScent === 'yes') {
                $product->setScent('Oui');
            } elseif ($noScent === 'no') {
                $product->setScent('Non');
            } else {
                $product->setScent(null);
            }
        } else {
            $product->setScent(null);
        }

        $product->setToolType(Utils::request('tool_type') ?: null);

        // Gestion de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadDir = 'img/';
            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $product->setImage($uploadFile);
            }
        }

        if ($productManager->update($product)) {
            $_SESSION['flash'] = "Produit mis à jour avec succès.";
        } else {
            $_SESSION['flash'] = "Erreur lors de la mise à jour.";
        }

        header('Location: index.php?action=showProduct&id=' . $id);
    }

    /**
     * Supprime un produit.
     * Nécessite des droits d'administrateur.
     * @return void
     */
    public function deleteProduct()
    {
        // Vérification des droits d'administrateur
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            header('Location: index.php?action=home');
            exit();
        }

        $id = (int) Utils::request('id');
        $productManager = new ProductManager();

        if ($productManager->delete($id)) {
            $_SESSION['flash'] = "Produit supprimé avec succès.";
            header('Location: index.php?action=home');
        } else {
            $_SESSION['flash'] = "Erreur lors de la suppression.";
            header('Location: index.php?action=showProduct&id=' . $id);
        }
    }

    /**
     * Ajoute un nouveau produit.
     * Nécessite des droits d'administrateur.
     * @return void
     */
    public function addProduct()
    {
        // Vérification Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            $_SESSION['flash'] = "Accès refusé.";
            Utils::redirect('catalogue');
        }

        // Récupération des données du formulaire
        $name = Utils::request('name');
        $description = Utils::request('description');
        $categoryId = (int) Utils::request('category_id');
        $price = (float) Utils::request('price');

        // VAL: 1. Vérification des champs obligatoires de base
        if (empty($name) || empty($description) || empty($price) || !$categoryId) {
            $_SESSION['flash'] = "Veuillez remplir tous les champs obligatoires.";
            $_SESSION['form_submitted'] = $_POST;
            $_SESSION['open_modal'] = true;
            Utils::redirect('catalogue');
        }

        // VAL: 2. Vérification de l'image (Obligatoire)
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash'] = "L'ajout d'une image est obligatoire.";
            $_SESSION['form_submitted'] = $_POST;
            $_SESSION['open_modal'] = true;
            Utils::redirect('catalogue');
        }

        $productManager = new ProductManager();

        // Gestion dynamique des champs spécifiques et validation
        $color = null;
        $scent = null;
        $toolType = null;

        if ($categoryId === 1) { // Résines
            $color = Utils::request('color');
            // Option Résines
            if (empty($color)) {
                $_SESSION['flash'] = "Veuillez sélectionner une couleur pour les résines.";
                $_SESSION['form_submitted'] = $_POST;
                $_SESSION['open_modal'] = true;
                Utils::redirect('catalogue');
            }
        } elseif ($categoryId === 2) { // Entretien
            $noScent = Utils::request('no_scent');
            if ($noScent === 'yes') {
                $scent = "Oui";
            } elseif ($noScent === 'no') {
                $scent = "Non";
            } else {
                $_SESSION['flash'] = "Veuillez préciser si le produit est sans odeur.";
                $_SESSION['form_submitted'] = $_POST;
                $_SESSION['open_modal'] = true;
                Utils::redirect('catalogue');
            }
        } elseif ($categoryId === 3) { // Outillage
            $toolType = Utils::request('tool_type');
            // Option Outillage
            if (empty($toolType)) {
                $_SESSION['flash'] = "Veuillez sélectionner un type d'outil pour l'outillage.";
                $_SESSION['form_submitted'] = $_POST;
                $_SESSION['open_modal'] = true;
                Utils::redirect('catalogue');
            }
        }

        // Téléchargement de l'image
        $uploadDir = 'img/';
        $filename = uniqid() . '_' . basename($_FILES['image']['name']); // Nom unique de l'image
        $targetPath = $uploadDir . $filename;
        $imagePath = 'img/camera-icon.png'; // Image de sécurité

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        } else {
            $_SESSION['flash'] = "Erreur lors du téléchargement de l'image.";
            $_SESSION['form_submitted'] = $_POST;
            $_SESSION['open_modal'] = true;
            Utils::redirect('catalogue');
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

    /**
     * Affiche les résultats de la recherche.
     * @return void
     */
    public function search()
    {
        $searchTerm = Utils::request('search', '');
        $productManager = new ProductManager();
        $filters = ['search' => $searchTerm];

        // Filtres de recherche
        if (isset($_GET['categories']) && is_array($_GET['categories'])) {
            $filters['category_id'] = $_GET['categories'];
        }

        if (isset($_GET['min_price']) && $_GET['min_price'] !== '') {
            $filters['min_price'] = (float) $_GET['min_price'];
        }

        if (isset($_GET['max_price']) && $_GET['max_price'] !== '') {
            $filters['max_price'] = (float) $_GET['max_price'];
        }

        // Tri des résultats
        if (isset($_GET['sort'])) {
            $parts = explode('-', $_GET['sort']);
            if (count($parts) === 2) {
                $filters['order_by'] = $parts[0];
                $filters['direction'] = $parts[1];
            }
        }

        $products = $productManager->findByFilter($filters);

        // Plages de prix globales
        $priceRange = $productManager->getMinMaxPrices();

        $categories = [
            1 => 'Résines',
            2 => 'Entretien',
            3 => 'Outillage'
        ];

        $view = new View("Recherche");
        $view->render("search", [
            'products' => $products,
            'searchTerm' => $searchTerm,
            'categories' => $categories,
            'currentCategories' => $filters['category_id'] ?? [],
            'currentMinPrice' => $filters['min_price'] ?? $priceRange['min_price'],
            'currentMaxPrice' => $filters['max_price'] ?? $priceRange['max_price'],
            'minPrice' => $priceRange['min_price'],
            'maxPrice' => $priceRange['max_price'],
            'currentSort' => $_GET['sort'] ?? ''
        ]);
    }
    /**
     * Retourne les produits au format JSON pour la recherche AJAX (Admin) de le la réponse à une offre ("Faire une offre").
     */
    public function searchJson()
    {
        // Vérification Admin
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit();
        }

        $term = Utils::request('term', '');
        $productManager = new ProductManager();
        $allProducts = $productManager->findByFilter(['search' => $term]);
        // Affiche les 3 produits les plus cohérents avec la recherche
        $products = array_slice($allProducts, 0, 3);

        $json = [];
        foreach ($products as $p) {
            $json[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'price' => $p->getPrice(),
                'image' => $p->getImage()
            ];
        }

        header('Content-Type: application/json'); // Indique que la réponse est au format JSON
        echo json_encode($json); // Retourne les produits au format JSON
        exit();
    }
}
