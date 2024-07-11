<?php

require_once("../models/interfaces/ISerializable.php");

class ResponseDTO implements ISerializable {
    private int $status;
    private string $message;
    private ?ISerializable $data;

    public function __construct(int $status, string $message, ?ISerializable $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getData(): ?ISerializable {
        return $this->data;
    }

    public function setStatus(int $status) : void {
        $this->status = $status;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function setData(ISerializable $data): void {
        $this->data = $data;
    }

    public function serialize(): array
    {
        return [
            "status" => $this->getStatus(),
            "message" => $this->getMessage(),
            "data" => $this->getData() != null ? $this->getData()->serialize() : null

        ];
    }
}