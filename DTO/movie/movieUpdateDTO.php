<?php

require_once("../models/interfaces/ISerializable.php");
require_once("../models/File.php");

class MovieUpdateDTO implements ISerializable {
    private int $id;
    private ?string $title;
    private ?string $description;
    private ?string $duration;
    private ?string $date;
    private ?int $authorId;

    public function __construct(int $id, ?string $title, ?string $description, ?string $duration, ?string $date, ?int $authorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->date = $date;
        $this->authorId = $authorId;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getDuration(): ?string {
        return $this->duration;
    }

    public function getReleaseDate(): ?string {
        return $this->date;
    }

    public function getAuthorID(): ?int {
        return $this->authorId;
    }

    public function serialize(): array
    {
        return [
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "duration" => $this->getDuration(),
            "release_date" => $this->getReleaseDate(),
            "authorId" => $this->getAuthorID()
        ];
    }
}