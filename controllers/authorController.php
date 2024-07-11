<?php

require_once("../repositories/movieRepository.php");
require_once("../repositories/authorRepository.php");
require_once("../services/AuthorService.php");
require_once("../models/SerializableArray.php");
require_once("../DTO/ResponseDTO.php");

class AuthorController {
    public static function index(): ResponseDTO
    {
        try {
            $authorsResponse = AuthorRepository::getAuthors();
            if ($authorsResponse->getStatus() == 200 && $authorsResponse->getData() != null) {
                $authors = $authorsResponse->getData();
                $authorsSerArray = new SerializableArray($authors);
                return new ResponseDTO(200, $authorsResponse->getMessage(), $authorsSerArray);
            }
            return new ResponseDTO(404, "Authors not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
    public static function get(int $id): ResponseDTO
    {
        try {
            $authorResponse = AuthorRepository::getAuthor($id);
            if ($authorResponse->getStatus() == 200 && $authorResponse->getData() != null) {
                return new ResponseDTO(200, $authorResponse->getMessage(), $authorResponse->getData());
            }
            return new ResponseDTO(404, "Author not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
    public static function delete(int $id): ResponseDTO
    {
        try {
            $valid = AuthorService::validateId($id);
            if (!$valid[0]) {
                return new ResponseDTO(400, "Bad request. " . $valid[1], null);
            }
            $authorResponse = AuthorRepository::getAuthor($id);
            if ($authorResponse->getStatus() == 200 && $authorResponse->getData() != null) {
                $authorResponse = AuthorRepository::deleteAuthor($authorResponse->getData()->getId());
                return new ResponseDTO(200, $authorResponse->getMessage(), null);
            }
            return new ResponseDTO(404, "Author not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
}