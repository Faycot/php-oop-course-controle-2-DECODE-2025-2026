<?php

declare(strict_types=1);

namespace Api\Controller;

use Api\Entities\Comment;
use Api\Repositories\CommentRepository;
use Api\Repositories\PostRepository;

final class CommentController extends ApiController
{
    private CommentRepository $commentRepository;
    private PostRepository $postRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->postRepository = new PostRepository();
    }

    public function index(int $postId): void
    {
        $post = $this->postRepository->findById($postId);

        if ($post === null) {
            $this->sendError('Post not found', 404);
            return;
        }

        $comments = $this->commentRepository->findByPostId($postId);

        $commentsArray = array_map(
            static fn(Comment $comment) => $comment->toArray(),
            $comments
        );

        $this->sendSuccess(['comments' => $commentsArray], null, 200);
    }

    public function create(int $postId): void
    {
        $post = $this->postRepository->findById($postId);

        if ($post === null) {
            $this->sendError('Post not found', 404);
            return;
        }

        $data = $this->getJsonInput();

        $content = trim((string) ($data['content'] ?? ''));
        $userId = (int) ($data['user_id'] ?? 0);

        if ($content === '' || $userId <= 0) {
            $this->sendError('Content and user_id are required', 400);
            return;
        }

        $comment = new Comment($content, $userId, $postId);
        $comment = $this->commentRepository->create($comment);

        $this->sendSuccess($comment->toArray(), 'Comment created successfully', 201);
    }

    public function update(int $id): void
    {
        $comment = $this->commentRepository->findById($id);

        if ($comment === null) {
            $this->sendError('Comment not found', 404);
            return;
        }

        $data = $this->getJsonInput();
        $content = trim((string) ($data['content'] ?? ''));

        if ($content === '') {
            $this->sendError('Content is required', 400);
            return;
        }

        $comment->setContent($content);

        $this->commentRepository->update($comment);

        $this->sendSuccess($comment->toArray(), 'Comment updated successfully', 200);
    }

    public function delete(int $id): void
    {
        $comment = $this->commentRepository->findById($id);

        if ($comment === null) {
            $this->sendError('Comment not found', 404);
            return;
        }

        $this->commentRepository->delete($id);

        $this->sendSuccess([], 'Comment deleted successfully', 200);
    }
}