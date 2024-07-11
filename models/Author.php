<?php

require_once("interfaces/ISerializable.php");

class Author implements ISerializable {
    private int $id;
    private string $name;
    private string $lastname;
    private string $dob;

    public function __construct(int $id, string $name, string $lastname, string $dob)
    {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->dob = $dob;
    }

    // GETTERS
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getDob(): string
    {
        return $this->dob;
    }

    // SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setDob(string $dob): void
    {
        $this->dob = $dob;
    }

    public function serialize(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'lastname' => $this->getLastname(),
            'dob' => $this->getDob()
        ];
    }
}