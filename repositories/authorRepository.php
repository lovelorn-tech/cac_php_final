<?php

require_once("../models/Author.php");
require_once("../models/RepositoryResponse.php");
require_once("../config/db.php");

class AuthorRepository
{
    public static function getAuthor(int $id): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "SELECT * FROM Authors WHERE id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$id]);
            $author = $stmt->fetch();
            if ($author) {
                return new RepositoryResponse(200, "Author sent", new Author(
                    $author['id'],
                    $author['name'],
                    $author['lastname'],
                    $author['dob']
                ));
            }
            return new RepositoryResponse(404, "Author not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function getAuthors(): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "SELECT * FROM Authors";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $authors = [];
            foreach ($data as $author) {
                $authors[] =
                    new Author(
                        $author['id'],
                        $author['name'],
                        $author['lastname'],
                        $author['dob']
                    );
            }
            return new RepositoryResponse(200, "Authors list sent.", $authors);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function createAuthor(Author $author): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "INSERT INTO Authors (name, lastname, dob) VALUES (?, ?, ?)";
            $con->prepare($query)->execute([$author->getName(), $author->getLastname(), $author->getDob()]);
            return new RepositoryResponse(201, "Author successfully created", $author);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function updateAuthor(Author $author): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "UPDATE Authors SET name= ?, lastname= ?, dob= ? WHERE id = ?";
            $con->prepare($query)->execute([$author->getName(), $author->getLastname(), $author->getDob(), $author->getId()]);
            return new RepositoryResponse(200, "Author successfully updated", $author);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function deleteAuthor(int $id): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "UPDATE Authors SET deleted = :deleted WHERE id = :id";
            $stmt = $con->prepare($query);
            $stmt->bindValue('deleted', (int)1, PDO::PARAM_INT);
            $stmt->bindValue('id', (int)$id, PDO::PARAM_INT);
            $stmt->execute();
            return new RepositoryResponse(200, "Author successfully deleted", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
}
