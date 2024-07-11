<?php

require_once("../models/File.php");

class FileService {
    public static function deleteFile(string $path): void {
        if(file_exists($path)) unlink($path);
    }
}