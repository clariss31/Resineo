<?php

class MessageController
{
    /**
     * Affiche la messagerie du client (Utilisateur connecté).
     */
    public function showClientMessaging()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=connect');
            exit();
        }

        // Si l'utilisateur est un admin, on le redirige vers l'interface d'administration
        if ($_SESSION['user']->getRole() === 'admin') {
            $this->showAdminMessaging();
            return;
        }

        $user = $_SESSION['user'];
        $conversationManager = new ConversationManager();
        $messageManager = new MessageManager();

        // Récupère ou crée la conversation
        $conversation = $conversationManager->getClientConversation($user->getId());
        $messages = $messageManager->findByConversation($conversation->getId());

        // Récupère le message pré-rempli s'il existe
        $prefillContent = Utils::request('prefill', '');

        $view = new View("Messagerie");
        $view->render("messaging", [
            'conversation' => $conversation,
            'messages' => $messages,
            'user' => $user,
            'prefillContent' => $prefillContent
        ]);
    }

    /**
     * Affiche la messagerie admin.
     */
    public function showAdminMessaging()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'admin') {
            header('Location: index.php');
            exit();
        }

        $conversationManager = new ConversationManager();
        $messageManager = new MessageManager();

        // Liste de toutes les conversations
        $conversations = $conversationManager->findAllWithDetails();

        // Conversation active (par défaut la première ou celle demandée)
        $activeConversationId = (int) Utils::request('id', 0);

        // Initialisation des variables
        $activeConversation = null;
        $messages = [];

        if ($activeConversationId) {
            $activeConversation = $conversationManager->findOneById($activeConversationId);
        } elseif (!empty($conversations)) {
            // Si aucune conversation n'est active, on prend la première
            $activeConversation = $conversations[0];
        }

        // Récupération des messages de la conversation active
        if ($activeConversation) {
            $messages = $messageManager->findByConversation($activeConversation->getId());
        }

        $view = new View("Administration - Messagerie");
        $view->render("admin_messaging", [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
            'user' => $_SESSION['user']
        ]);
    }

    /**
     * Traite l'envoi d'un message.
     */
    public function sendMessage()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit();
        }

        $conversationId = (int) Utils::request('conversation_id');
        $content = Utils::request('content');
        $type = Utils::request('type', 'text');

        if (!$conversationId || empty($content)) {
            header('Location: index.php?action=messagerie');
            exit();
        }

        $message = new Message();
        $message->setConversationId($conversationId);
        $message->setSenderId($_SESSION['user']->getId());
        $message->setContent($content);
        $message->setType($type);

        $messageManager = new MessageManager();
        $messageManager->create($message);

        // Redirection selon le rôle
        if ($_SESSION['user']->getRole() === 'admin') {
            header('Location: index.php?action=adminMessages&id=' . $conversationId);
        } else {
            header('Location: index.php?action=messagerie');
        }
    }
}
