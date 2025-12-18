<?php

class ConversationManager extends AbstractEntityManager
{
    /**
     * Récupère ou crée la conversation unique d'un utilisateur.
     * @param int $userId
     * @return Conversation
     */
    public function getClientConversation(int $userId): Conversation
    {
        $sql = "SELECT c.*, u.firstname, u.lastname FROM conversations c 
                JOIN users u ON c.user_id = u.id 
                WHERE user_id = :user_id LIMIT 1";
        $result = $this->db->query($sql, ['user_id' => $userId]);
        $row = $result->fetch();

        if ($row) {
            $conversation = new Conversation($row);
            $conversation->setUserName($row['firstname'] . ' ' . $row['lastname']);
            return $conversation;
        }

        // Création automatique si inexistante
        $this->createConversation($userId);

        // On réessaie de récupérer la conversation
        $result = $this->db->query($sql, ['user_id' => $userId]);
        $row = $result->fetch();

        if ($row) {
            $conversation = new Conversation($row);
            $conversation->setUserName($row['firstname'] . ' ' . $row['lastname']);
            return $conversation;
        }

        // Si toujours rien, c'est un problème critique
        throw new Exception("Impossible de créer ou récupérer la conversation pour l'utilisateur $userId.");
    }

    /**
     * Récupère toutes les conversations pour l'admin.
     * @return Conversation[]
     */
    public function findAllWithDetails(): array
    {
        $sql = "SELECT c.*, u.firstname, u.lastname, u.image as user_image,
                (SELECT created_at FROM messages m WHERE m.conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message_date,
                (SELECT content FROM messages m WHERE m.conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message_content
                FROM conversations c
                JOIN users u ON c.user_id = u.id
                ORDER BY last_message_date DESC";
        $result = $this->db->query($sql);
        $conversations = [];

        while ($row = $result->fetch()) {
            $conv = new Conversation($row);
            $conv->setUserName($row['firstname'] . ' ' . $row['lastname']);
            $conv->setUserImage($row['user_image']);
            $conv->setLastMessageDate($row['last_message_date']);
            $conv->setLastMessageContent($row['last_message_content']);
            $conversations[] = $conv;
        }

        return $conversations;
    }

    public function findOneById(int $id): ?Conversation
    {
        $sql = "SELECT c.*, u.firstname, u.lastname, u.image as user_image FROM conversations c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $row = $result->fetch();

        if ($row) {
            $conversation = new Conversation($row);
            $conversation->setUserName($row['firstname'] . ' ' . $row['lastname']);
            $conversation->setUserImage($row['user_image']);
            return $conversation;
        }
        return null;
    }

    private function createConversation(int $userId): void
    {
        $sql = "INSERT INTO conversations (user_id, title, status, created_at) 
                VALUES (:user_id, 'Support Client', 'open', NOW())";

        $this->db->query($sql, [
            'user_id' => $userId
        ]);
    }
}
