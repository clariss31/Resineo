<?php
require_once 'config/config.php';
require_once 'config/autoload.php';
session_start();

require_once 'controllers/HomeController.php';

// On récupère l'action demandée par l'utilisateur.
// Si aucune action n'est demandée, on affiche la page d'accueil.
$action = Utils::request('action', 'home');

// Try catch global pour gérer les erreurs
try {
    // Pour chaque action, on appelle le bon contrôleur et la bonne méthode.
    switch ($action) {
        // Pages accessibles à tous.
        case 'home':
            $homeController = new HomeController();
            $homeController->showHome();
            break;

        case 'catalogue':
            $productController = new ProductController();
            $productController->showCatalogue();
            break;

        case 'resines':
            $productController = new ProductController();
            $productController->showCategory('resines');
            break;

        case 'entretien':
            $productController = new ProductController();
            $productController->showCategory('entretien');
            break;

        case 'outillage':
            $productController = new ProductController();
            $productController->showCategory('outillage');
            break;

        case 'search':
            $productController = new ProductController();
            $productController->search();
            break;

        case 'showProduct':
            $productController = new ProductController();
            $productController->showProduct();
            break;

        case 'updateProduct':
            $productController = new ProductController();
            $productController->updateProduct();
            break;

        case 'deleteProduct':
            $productController = new ProductController();
            $productController->deleteProduct();
            break;

        case 'addProduct':
            $productController = new ProductController();
            $productController->addProduct();
            break;

        case 'devis':
            $quoteController = new QuoteController();
            $quoteController->showQuote();
            break;

        case 'addToQuote':
            $quoteController = new QuoteController();
            $quoteController->addToQuote();
            break;

        case 'removeFromQuote':
            $quoteController = new QuoteController();
            $quoteController->removeFromQuote();
            break;

        case 'updateQuantity':
            $quoteController = new QuoteController();
            $quoteController->updateQuantity();
            break;

        case 'clearQuote':
            $quoteController = new QuoteController();
            $quoteController->clearQuote();
            break;

        case 'sendQuote':
            $quoteController = new QuoteController();
            $quoteController->sendQuote();
            break;

        // Authentification
        case 'registerForm':
            $authController = new AuthController();
            $authController->showRegister();
            break;

        case 'register':
            $authController = new AuthController();
            $authController->register();
            break;

        case 'loginForm':
            $authController = new AuthController();
            $authController->showLogin();
            break;

        case 'connectUser':
            $authController = new AuthController();
            $authController->login();
            break;

        case 'disconnectUser':
            $authController = new AuthController();
            $authController->logout();
            break;

        case 'compte':
            $userController = new UserController();
            $userController->showAccount();
            break;

        case 'messagerie':
            $userController = new UserController();
            $userController->showMessaging();
            break;

        case 'updateAccount':
            $userController = new UserController();
            $userController->updateAccount();
            break;

        default:
            throw new Exception("La page demandée n'existe pas.");
    }
} catch (Exception $e) {
    // En cas d'erreur, on affiche la page d'erreur.
    $errorView = new View('Erreur');
    $errorView->render('errorPage', ['errorMessage' => $e->getMessage()]);
}