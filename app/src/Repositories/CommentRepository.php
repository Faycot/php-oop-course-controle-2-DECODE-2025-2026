<?php

namespace Api\Repositories;

use Api\Config\Database;
use Api\Entities\Comment;
use PDO;

class CommentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM comments ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        $commentsData = $stmt->fetchAll();

        $comments = [];
        foreach ($commentsData as $commentData) {
            $comments[] = $this->hydrate($commentData);
        }

        return $comments;
    }

    public function findById(int $id): ?Comment
    {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $commentData = $stmt->fetch();

        return $commentData ? $this->hydrate($commentData) : null;
    }

    public function findByPostId(int $postId): array
    {
        $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $postId]);
        $commentsData = $stmt->fetchAll();

        $comments = [];
        foreach ($commentsData as $commentData) {
            $comments[] = $this->hydrate($commentData);
        }

        return $comments;
    }

    public function findByUserId(int $userId): array
    {
        $sql = "SELECT * FROM comments WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $commentsData = $stmt->fetchAll();

        $comments = [];
        foreach ($commentsData as $commentData) {
            $comments[] = $this->hydrate($commentData);
        }

        return $comments;
    }

    public function create(Comment $comment): Comment
    {
        $sql = "INSERT INTO comments (content, user_id, post_id) VALUES (:content, :user_id, :post_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'content' => $comment->getContent(),
            'user_id' => $comment->getUserId(),
            'post_id' => $comment->getPostId(),
        ]);

        $comment->setId((int) $this->db->lastInsertId());

        return $comment;
    }

    public function update(Comment $comment): bool
    {
        $sql = "UPDATE comments SET content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'content' => $comment->getContent(),
            'id' => $comment->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(array $data): Comment
    {
        return new Comment(
            $data['content'],
            $data['user_id'],
            $data['post_id'],
            $data['id'],
            $data['created_at']
        );
    }
}
