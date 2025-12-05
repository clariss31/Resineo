<?php

class QuoteController
{
    public function showQuote()
    {
        $products = [];
        $total = 0;

        if (isset($_SESSION['quote']) && !empty($_SESSION['quote'])) {
            $productManager = new ProductManager();
            foreach ($_SESSION['quote'] as $productId => $quantity) {
                $product = $productManager->findOneById($productId);
                if ($product) {
                    $product->quantity = $quantity; // Temporarily attach quantity to object for view
                    $products[] = $product;
                    $total += $product->getPrice() * $quantity;
                }
            }
        }

        // Affiche la page de devis (panier)
        $view = new View("Mon Devis");
        $view->render("quote", ['quoteItems' => $products, 'total' => $total]);
    }

    public function addToQuote()
    {
        $productId = Utils::request('product_id');
        $quantity = Utils::request('quantity', 1);

        if ($productId) {
            if (!isset($_SESSION['quote'])) {
                $_SESSION['quote'] = [];
            }

            if (isset($_SESSION['quote'][$productId])) {
                $_SESSION['quote'][$productId] += $quantity;
            } else {
                $_SESSION['quote'][$productId] = $quantity;
            }
        }

        // Redirect to the previous page (referer) or default to home/catalogue
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=catalogue';
        header("Location: $referer");
        exit();
    }

    public function removeFromQuote()
    {
        $productId = Utils::request('product_id');

        if ($productId && isset($_SESSION['quote'][$productId])) {
            unset($_SESSION['quote'][$productId]);
        }

        Utils::redirect('devis');
    }

    public function updateQuantity()
    {
        $productId = Utils::request('product_id');
        $direction = Utils::request('direction'); // 'increase' or 'decrease'

        if ($productId && isset($_SESSION['quote'][$productId])) {
            if ($direction === 'increase') {
                $_SESSION['quote'][$productId]++;
            } elseif ($direction === 'decrease') {
                $_SESSION['quote'][$productId]--;
                if ($_SESSION['quote'][$productId] <= 0) {
                    unset($_SESSION['quote'][$productId]); // Remove if 0
                }
            }
        }

        Utils::redirect('devis');
    }

    public function sendQuote()
    {
        // Ici, on pourrait traiter le formulaire (envoyer un email, enregistrer en BDD, etc.)
        // Pour l'instant, on vide juste le devis (simulation d'envoi réussi).
        unset($_SESSION['quote']);

        // Redirection vers la page devis (qui affichera "Votre devis est vide") ou une page de succès
        Utils::redirect('devis');
    }
}
