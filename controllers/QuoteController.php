<?php

class QuoteController
{
    /**
     * Affiche le contenu du devis (panier).
     * Calcule le total et récupère les détails des produits.
     * @return void
     */
    public function showQuote()
    {
        $products = [];
        $total = 0;

        if (isset($_SESSION['quote']) && !empty($_SESSION['quote'])) {
            $productManager = new ProductManager();
            foreach ($_SESSION['quote'] as $productId => $quantity) {
                $product = $productManager->findOneById($productId);
                if ($product) {
                    $product->quantity = $quantity; // Attache temporairement la quantité à l'objet pour la vue
                    $products[] = $product;
                    $total += $product->getPrice() * $quantity;
                }
            }
        }

        // Affiche la page de devis (panier)
        $view = new View("Mon Devis");
        $view->render("quote", ['quoteItems' => $products, 'total' => $total]);
    }

    /**
     * Ajoute un produit au devis.
     * Gère les requêtes classiques et AJAX.
     * @return void
     */
    public function addToQuote()
    {
        $productId = Utils::request('product_id');
        $quantity = Utils::request('quantity', 1);
        $message = "Erreur lors de l'ajout.";
        $success = false;

        if ($productId) {
            if (!isset($_SESSION['quote'])) {
                $_SESSION['quote'] = [];
            }

            if (isset($_SESSION['quote'][$productId])) {
                $_SESSION['quote'][$productId] += $quantity;
            } else {
                $_SESSION['quote'][$productId] = $quantity;
            }

            // Récupération du nom du produit pour le message flash
            $productManager = new ProductManager();
            $product = $productManager->findOneById($productId);
            $productName = $product ? $product->getName() : "Produit";

            $message = "$productName ajouté au devis !";
            $success = true;

            // Message flash de confirmation (legacy)
            $_SESSION['flash'] = $message;
        }

        // Vérification d'une requête AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $message,
                'quoteCount' => array_sum($_SESSION['quote'] ?? [])
            ]);
            exit;
        }

        // Redirection vers la page précédente ou le catalogue par défaut
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=catalogue';
        header("Location: $referer");
        exit();
    }

    /**
     * Supprime un produit du devis.
     * @return void
     */
    public function removeFromQuote()
    {
        $productId = Utils::request('product_id');

        if ($productId && isset($_SESSION['quote'][$productId])) {
            unset($_SESSION['quote'][$productId]);
        }

        Utils::redirect('devis');
    }

    /**
     * Vide entièrement le devis.
     * @return void
     */
    public function clearQuote()
    {
        if (isset($_SESSION['quote'])) {
            unset($_SESSION['quote']);
        }
        Utils::redirect('devis');
    }

    /**
     * Met à jour la quantité d'un produit dans le devis.
     * @return void
     */
    public function updateQuantity()
    {
        $productId = Utils::request('product_id');
        $direction = Utils::request('direction'); // 'increase' ou 'decrease'

        if ($productId && isset($_SESSION['quote'][$productId])) {
            if ($direction === 'increase') {
                $_SESSION['quote'][$productId]++;
            } elseif ($direction === 'decrease') {
                $_SESSION['quote'][$productId]--;
                if ($_SESSION['quote'][$productId] <= 0) {
                    unset($_SESSION['quote'][$productId]); // Supprime si 0
                }
            }
        }

        Utils::redirect('devis');
    }

    /**
     * Simule l'envoi du devis.
     * @return void
     */
    public function sendQuote()
    {
        // Simuler l'envoi du devis (envoi email, BDD, etc.)
        // Pour l'instant, on vide juste le devis (simulation d'envoi réussi).
        unset($_SESSION['quote']);

        // Redirection vers la page devis (qui affichera "Votre devis est vide") ou une page de succès
        Utils::redirect('devis');
    }
}
