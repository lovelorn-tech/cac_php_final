<?php

class File
{
    private array $errors = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.'
    ];

    private string $originalName;
    private ?string $storageName;
    private string $mimetype;
    private int $size;
    private string $tmpName;
    private int $error;
    private ?string $storagePath;

    public function __construct(string $originalName, string $mimetype, int $size, string $tmpName, int $error)
    {
        $this->originalName = $originalName;
        $this->mimetype = $mimetype;
        $this->size = $size;
        $this->tmpName = $tmpName;
        $this->error = $error;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getMimeType(): string
    {
        return $this->mimetype;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    public function getErrorValue(): int
    {
        return $this->error;
    }

    public function getErrorMessage(): string
    {
        return $this->errors[$this->error];
    }

    public function getStoragePath(): ?string
    {
        return $this->storagePath;
    }

    public function getStorageName(): ?string
    {
        return $this->storageName;
    }

    public function setStoragePath(string $basePath): void
    {
        $this->storageName = str_replace(
            ['-', ' ', "\\", "/", ':', '*', ','], 
            '_', 
            uniqid() . date("D M d, Y G:i") . '.' . pathinfo($this->getOriginalName(), PATHINFO_EXTENSION)
        );
        $this->storagePath = str_replace("\\", "/", $basePath . $this->storageName);
    }
}
