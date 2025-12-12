<?php

class MessageManager extends AbstractEntityManager
{
    /**
     * Récupère les messages d'une conversation.
     * @param int $conversationId
     * @return Message[]
     */
    public function findByConversation(int $conversationId): array
    {
        $sql = "SELECT m.* FROM messages m WHERE conversation_id = :conversation_id ORDER BY created_at ASC";
        $result = $this->db->query($sql, ['conversation_id' => $conversationId]);
        $messages = [];

        while ($row = $result->fetch()) {
            $messages[] = new Message($row);
        }

        return $messages;
    }

    /**
     * Envoie un message.
     * @param Message $message
     * @return bool
     */
    public function create(Message $message): bool
    {
        $sql = "INSERT INTO messages (conversation_id, sender_id, content, type, created_at) VALUES (:conversation_id, :sender_id, :content, :type, NOW())";
        try {
            $this->db->query($sql, [
                'conversation_id' => $message->getConversationId(),
                'sender_id' => $message->getSenderId(),
                'content' => $message->getContent(),
                'type' => $message->getType()
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
