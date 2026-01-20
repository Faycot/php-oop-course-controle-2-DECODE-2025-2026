<?php

declare(strict_types=1);

namespace Api\Controller;

use Api\Entities\Post;
use Api\Repositories\CommentRepository;
use Api\Repositories\PostRepository;

final class PostController extends ApiController
{
    private PostRepository $postRepository;
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->commentRepository = new CommentRepository();
    }

    public function index(): void
    {
        $page = (int) $this->getParam('page', 1);
        $limit = (int) $this->getParam('limit', 10);
        $offset = ($page - 1) * $limit;

        $posts = $this->postRepository->findAll($limit, $offset);
        $total = $this->postRepository->count();

        $postsArray = array_map(fn($post) => $post->toArray(), $posts);

        $this->sendSuccess([
            'posts' => $postsArray,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit),
            ],
        ]);
    }

    public function show(int $id): void
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            $this->sendError('Post not found', 404);
            return;
        }

        $comments = $this->commentRepository->findByPostId($id);
        $commentsArray = array_map(fn($comment) => $comment->toArray(), $comments);

        $this->sendSuccess([
            'post' => $post->toArray(),
            'comments' => $commentsArray,
        ]);
    }

    public function create(): void
    {
        $data = $this->getJsonInput();

        if (empty($data['title']) || empty($data['content']) || empty($data['user_id'])) {
            $this->sendError('Title, content and user_id are required', 400);
            return;
        }

        $post = new Post($data['title'], $data['content'], $data['user_id']);
        $post = $this->postRepository->create($post);

        $this->sendSuccess($post->toArray(), 'Post created successfully', 201);
    }

    public function update(int $id): void
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            $this->sendError('Post not found', 404);
            return;
        }

        $data = $this->getJsonInput();

        if (isset($data['title'])) {
            $post->setTitle($data['title']);
        }

        if (isset($data['content'])) {
            $post->setContent($data['content']);
        }

        $this->postRepository->update($post);

        $this->sendSuccess($post->toArray(), 'Post updated successfully');
    }

    public function delete(int $id): void
    {
        $post = $this->postRepository->findById($id);

        if (!$post) {
            $this->sendError('Post not found', 404);
            return;
        }

        $this->postRepository->delete($id);

        $this->sendSuccess([], 'Post deleted successfully');
    }
}