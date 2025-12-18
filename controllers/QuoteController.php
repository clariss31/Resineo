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

        // Sécurité : Les administrateurs ne peuvent pas créer de devis
        if (isset($_SESSION['user']) && $_SESSION['user']->getRole() === 'admin') {
            Utils::redirect('catalogue');
        }

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
        if (!isset($_SESSION['user'])) {
            // Si pas connecté, redirection ou gestion (normalement le bouton n'est pas accessible, ou on redirige vers connect)
            // Pour l'instant on suppose connecté car le formulaire est dans une zone sécurisée ou on redirige.
            header('Location: index.php?action=connect');
            exit();
        }

        // Sécurité : Les administrateurs ne peuvent pas envoyer de devis
        if ($_SESSION['user']->getRole() === 'admin') {
            Utils::redirect('catalogue');
        }

        if (isset($_SESSION['quote']) && !empty($_SESSION['quote'])) {
            $user = $_SESSION['user'];
            $userMessage = Utils::request('message');

            // 1. Récupérer les produits du devis
            $products = [];
            $productManager = new ProductManager();
            foreach ($_SESSION['quote'] as $productId => $quantity) {
                $product = $productManager->findOneById($productId);
                if ($product) {
                    $products[] = [
                        'name' => $product->getName(),
                        'price' => $product->getPrice(),
                        'image' => $product->getImage(),
                        'quantity' => $quantity
                    ];
                }
            }

            // 2. Construire le contenu JSON
            $contentData = [
                'user_message' => $userMessage,
                'items' => $products
            ];
            $jsonContent = json_encode($contentData);

            // 3. Récupérer/Créer conversation
            $conversationManager = new ConversationManager();
            $conversation = $conversationManager->getClientConversation($user->getId());

            // 4. Créer le message
            $message = new Message();
            $message->setConversationId($conversation->getId());
            $message->setSenderId($user->getId());
            $message->setContent($jsonContent);
            $message->setType('quote_request');

            $messageManager = new MessageManager();
            $messageManager->create($message);

            // 5. Vider le panier
            unset($_SESSION['quote']);
            $_SESSION['flash'] = "Votre demande de devis a été envoyée au support.";
        }

        // Redirection vers messagerie pour voir le message envoyé
        Utils::redirect('messagerie');
    }
}
