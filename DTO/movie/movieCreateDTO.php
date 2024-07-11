<?php

require_once("../models/interfaces/ISerializable.php");
require_once("../models/File.php");

class MovieCreateDTO implements ISerializable {
    private string $title;
    private ?string $description;
    private string $duration;
    private string $date;
    private int $authorId;
    private File $thumnail;
    private File $video;

    public function __construct(string $title, ?string $description, string $duration, string $date, int $authorId, File $thumnail, File $video)
    {
        $this->title = $title;
        $this->description = $description;
        $this->duration = $duration;
        $this->date = $date;
        $this->authorId = $authorId;
        $this->thumnail = $thumnail;
        $this->video = $video;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getDuration(): string {
        return $this->duration;
    }

    public function getReleaseDate(): string {
        return $this->date;
    }

    public function getAuthorID(): int {
        return $this->authorId;
    }

    public function getThumbnail(): File {
        return $this->thumnail;
    }

    public function getVideo(): File {
        return $this->video;
    }

    public function serialize(): array
    {
        return [
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "duration" => $this->getDuration(),
            "release_date" => $this->getReleaseDate(),
            "authorId" => $this->getAuthorID(),
            "thumbnail" => $this->getThumbnail()->getOriginalName(),
            "video" => $this->getVideo()->getOriginalName()
        ];
    }
}