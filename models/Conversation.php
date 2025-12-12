<?php

class Conversation extends AbstractEntity
{
    private int $user_id;
    private string $title;
    private string $status;
    private string $created_at;

    private ?string $userName = null;
    private ?string $userImage = null;
    private ?string $lastMessageDate = null;
    private ?string $lastMessageContent = null;

    // Getters and Setters

    public function getLastMessageContent(): ?string
    {
        return $this->lastMessageContent;
    }

    public function setLastMessageContent(?string $lastMessageContent): void
    {
        $this->lastMessageContent = $lastMessageContent;
    }

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): void
    {
        $this->userImage = $userImage;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    public function getLastMessageDate(): ?string
    {
        return $this->lastMessageDate;
    }

    public function setLastMessageDate(?string $lastMessageDate): void
    {
        $this->lastMessageDate = $lastMessageDate;
    }
}
