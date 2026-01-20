<?php

namespace Api\Repositories;

use Api\Config\Database;
use Api\Entities\Post;
use PDO;

class PostRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $postsData = $stmt->fetchAll();

        $posts = [];
        foreach ($postsData as $postData) {
            $posts[] = $this->hydrate($postData);
        }

        return $posts;
    }

    public function findById(int $id): ?Post
    {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $postData = $stmt->fetch();

        return $postData ? $this->hydrate($postData) : null;
    }

    public function findByUserId(int $userId, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $postsData = $stmt->fetchAll();

        $posts = [];
        foreach ($postsData as $postData) {
            $posts[] = $this->hydrate($postData);
        }

        return $posts;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM posts";
        $stmt = $this->db->query($sql);

        return (int) $stmt->fetchColumn();
    }

    public function countByUserId(int $userId): int
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    public function create(Post $post): Post
    {
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'user_id' => $post->getUserId(),
        ]);

        $post->setId((int) $this->db->lastInsertId());

        return $post;
    }

    public function update(Post $post): bool
    {
        $sql = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'id' => $post->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(array $data): Post
    {
        return new Post(
            $data['title'],
            $data['content'],
            $data['user_id'],
            $data['id'],
            $data['created_at']
        );
    }
}
