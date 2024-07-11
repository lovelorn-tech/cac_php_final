<?php

class RepositoryResponse
{
    private int $status;
    private string $message;
    private mixed $data;

    public function __construct(int $status, string $message, mixed $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
