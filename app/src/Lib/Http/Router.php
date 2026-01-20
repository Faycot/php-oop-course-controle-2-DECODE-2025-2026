<?php

declare(strict_types=1);

namespace Api\Config;

use Api\Controller\AuthController;
use Api\Controller\CommentController;
use Api\Controller\PostController;
use Api\Http\Request;
use Api\Responses\ApiResponse;

final class Router
{
    private Request $request;
    private string $method;
    private string $path;

    public function __construct()
    {
        $this->request = new Request();

        $this->method = $this->request->method();
        $this->path = $this->request->path();


        $this->path = preg_replace('#^/api#', '', $this->path) ?? $this->path;
    }

    public function route(): void
    {
        try {
            if ($this->path === '/auth/register' && $this->method === 'POST') {
                (new AuthController())->register();
                return;
            }

            if ($this->path === '/auth/login' && $this->method === 'POST') {
                (new AuthController())->login();
                return;
            }

            if ($this->path === '/posts' && $this->method === 'GET') {
                (new PostController())->index();
                return;
            }

            if (preg_match('#^/posts/(\d+)$#', $this->path, $matches) && $this->method === 'GET') {
                (new PostController())->show((int) $matches[1]);
                return;
            }

            if ($this->path === '/posts' && $this->method === 'POST') {
                (new PostController())->create();
                return;
            }

            if (preg_match('#^/posts/(\d+)$#', $this->path, $matches) && $this->method === 'PATCH') {
                (new PostController())->update((int) $matches[1]);
                return;
            }

            if (preg_match('#^/posts/(\d+)$#', $this->path, $matches) && $this->method === 'DELETE') {
                (new PostController())->delete((int) $matches[1]);
                return;
            }

            if (preg_match('#^/posts/(\d+)/comments$#', $this->path, $matches) && $this->method === 'GET') {
                (new CommentController())->index((int) $matches[1]);
                return;
            }

            if (preg_match('#^/posts/(\d+)/comments$#', $this->path, $matches) && $this->method === 'POST') {
                (new CommentController())->create((int) $matches[1]);
                return;
            }

            if (preg_match('#^/comments/(\d+)$#', $this->path, $matches) && $this->method === 'PATCH') {
                (new CommentController())->update((int) $matches[1]);
                return;
            }

            if (preg_match('#^/comments/(\d+)$#', $this->path, $matches) && $this->method === 'DELETE') {
                (new CommentController())->delete((int) $matches[1]);
                return;
            }

            ApiResponse::error('Route not found', 404)->send();
        } catch (\Throwable $e) {
            ApiResponse::error('Internal server error: ' . $e->getMessage(), 500)->send();
        }
    }
}