<?php

namespace Api\Entities;

class Comment
{
    private ?int $id;
    private string $content;
    private int $userId;
    private int $postId;
    private ?string $createdAt;

    public function __construct(
        string $content,
        int $userId,
        int $postId,
        ?int $id = null,
        ?string $createdAt = null
    ) {
        $this->content = $content;
        $this->userId = $userId;
        $this->postId = $postId;
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

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function setPostId(int $postId): void
    {
        $this->postId = $postId;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user_id' => $this->userId,
            'post_id' => $this->postId,
            'created_at' => $this->createdAt,
        ];
    }
}
