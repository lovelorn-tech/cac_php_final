<?php

require_once("interfaces/ISerializable.php");
require_once("Author.php");

class Movie implements ISerializable
{
    private int $id;
    private string $title;
    private ?string $description;
    private Author $author;
    private string $thumbnail;
    private string $video;
    private string $duration;
    private string $date;

    // Constructor
    public function __construct(int $id, string $title, ?string $description, Author $author, string $thumbnail, string $video, string $duration, string $date)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->thumbnail = $thumbnail;
        $this->video = $video;
        $this->duration = $duration;
        $this->date = $date;
    }

    // GETTERS
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    // SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    public function setThumbnail(string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function setVideo(string $video): void
    {
        $this->video = $video;
    }

    public function setDuration(string $duration): void
    {
        $this->duration = $duration;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function serialize(): array {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'author' => $this->getAuthor()->serialize(),
            'thumbnail' => $this->getThumbnail(),
            'video' => $this->getVideo(),
            'duration' => $this->getDuration(),
            'date' => $this->getDate()
        ];
    }
}
