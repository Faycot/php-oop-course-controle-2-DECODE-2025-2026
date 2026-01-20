<?php

namespace Api\Entities;

class Post
{
    private ?int $id;
    private string $title;
    private string $content;
    private int $userId;
    private ?string $createdAt;

    public function __construct(
        string $title,
        string $content,
        int $userId,
        ?int $id = null,
        ?string $createdAt = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->userId = $userId;
        $this->id = $id;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user_id' => $this->userId,
            'created_at' => $this->createdAt,
        ];
    }
}

