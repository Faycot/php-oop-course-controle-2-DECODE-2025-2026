<?php

declare(strict_types=1);

namespace Api\Controller;

use Api\Entities\User;
use Api\Repositories\UserRepository;

final class AuthController extends ApiController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(): void
    {
        $data = $this->getJsonInput();

        $name = trim((string) ($data['name'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $this->sendError('Name, email and password are required', 400);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendError('Invalid email format', 400);
            return;
        }

        if ($this->userRepository->findByEmail($email) !== null) {
            $this->sendError('Email already exists', 409);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($name, $email, $hashedPassword);
        $user = $this->userRepository->create($user);

        $this->sendSuccess(
            $user->toArray(),
            'User registered successfully',
            201
        );
    }

    public function login(): void
    {
        $data = $this->getJsonInput();

        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->sendError('Email and password are required', 400);
            return;
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user === null || !$user->verifyPassword($password)) {
            $this->sendError('Invalid credentials', 401);
            return;
        }


        $this->sendSuccess(
            ['user' => $user->toArray()],
            'Login successful',
            200
        );
    }
}