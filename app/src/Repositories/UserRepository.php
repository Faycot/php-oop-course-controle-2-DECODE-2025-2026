<?php

namespace Api\Repositories;

use Api\Config\Database;
use Api\Entities\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        $usersData = $stmt->fetchAll();

        $users = [];
        foreach ($usersData as $userData) {
            $users[] = $this->hydrate($userData);
        }

        return $users;
    }

    public function findById(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $userData = $stmt->fetch();

        return $userData ? $this->hydrate($userData) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch();

        return $userData ? $this->hydrate($userData) : null;
    }

    public function create(User $user): User
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);

        $user->setId((int) $this->db->lastInsertId());

        return $user;
    }

    public function update(User $user): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'id' => $user->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(array $data): User
    {
        return new User(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['id'],
            $data['created_at']
        );
    }
}
