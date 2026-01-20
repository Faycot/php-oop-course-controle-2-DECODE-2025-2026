<?php

declare(strict_types=1);

namespace Api\Controller;

use Api\Responses\ApiResponse;

abstract class ApiController
{
    protected function getJsonInput(): array
    {
        $input = file_get_contents('php://input');

        if ($input === false || trim($input) === '') {
            return [];
        }

        $data = json_decode($input, true);

        return is_array($data) ? $data : [];
    }

    protected function getQueryParams(): array
    {
        return $_GET;
    }

    protected function getParam(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    protected function sendSuccess(array $data = [], ?string $message = null, int $statusCode = 200): void
    {
        ApiResponse::success($data, $message, $statusCode)->send();
    }

    protected function sendError(string $message, int $statusCode = 400, array $data = []): void
    {
        ApiResponse::error($message, $statusCode, $data)->send();
    }
}