<?php

namespace Api\Responses;

class ApiResponse
{
    private int $statusCode;
    private array $data;
    private ?string $message;
    private bool $success;

    public function __construct(
        array $data = [],
        int $statusCode = 200,
        ?string $message = null,
        bool $success = true
    ) {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->success = $success;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');

        $response = [
            'success' => $this->success,
            'data' => $this->data,
        ];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        echo json_encode($response);
        exit;
    }

    public static function success(array $data = [], ?string $message = null, int $statusCode = 200): self    {
        return new self($data, $statusCode, $message, true);
    }

    public static function error(string $message, int $statusCode = 400, array $data = []): self
    {
        return new self($data, $statusCode, $message, false);
    }
}
