<?php

class Message extends AbstractEntity
{
    private int $conversation_id;
    private int $sender_id;
    private string $content;
    private string $type = 'text';
    private string $created_at;

    // Getters and Setters

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getConversationId(): int
    {
        return $this->conversation_id;
    }

    public function setConversationId(int $conversation_id): void
    {
        $this->conversation_id = $conversation_id;
    }

    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    public function setSenderId(int $sender_id): void
    {
        $this->sender_id = $sender_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }
}
