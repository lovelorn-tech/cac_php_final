<?php

require_once("../models/Movie.php");
require_once("../config/db.php");
require_once("authorRepository.php");

class MovieRepository
{
    public static function getMovie(int $id): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "SELECT m.id, m.title, m.description, m.thumbnail, m.video, m.duration, m.release_date, a.id AS author_id, a.name as author_name, a.lastname AS author_lastname, a.dob AS author_dob FROM movies m INNER JOIN Authors a ON m.AuthorID = a.id WHERE m.id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$id]);
            $movie = $stmt->fetch();
            if ($movie) {
                return new RepositoryResponse(200, "Movie sent", new Movie(
                    $movie['id'],
                    $movie['title'],
                    $movie['description'],
                    new Author(
                        $movie['author_id'],
                        $movie['author_name'],
                        $movie['author_lastname'],
                        $movie['author_dob']
                    ),
                    $movie['thumbnail'],
                    $movie['video'],
                    $movie['duration'],
                    $movie['release_date']
                ));
            }
            return new RepositoryResponse(404, "Movie not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function getMovies(): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "SELECT m.id, m.title, m.description, m.thumbnail, m.video, m.duration, m.release_date, a.id AS author_id, a.name as author_name, a.lastname AS author_lastname, a.dob AS author_dob FROM movies m INNER JOIN Authors a ON m.AuthorID = a.id";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $movies = [];
            foreach ($data as $movie) {
                $movies[] = new Movie(
                    $movie['id'],
                    $movie['title'],
                    $movie['description'],
                    new Author(
                        $movie['author_id'],
                        $movie['author_name'],
                        $movie['author_lastname'],
                        $movie['author_dob']
                    ),
                    $movie['thumbnail'],
                    $movie['video'],
                    $movie['duration'],
                    $movie['release_date']
                );
            }
            return new RepositoryResponse(200, "Movies sent", $movies);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function createMovie(Movie $movie): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "INSERT INTO Movies (title, description, thumbnail, video, duration, release_date, AuthorID) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $con->prepare($query)->execute([
                $movie->getTitle(),
                $movie->getDescription(),
                $movie->getThumbnail(),
                $movie->getVideo(),
                $movie->getDuration(),
                $movie->getDate(),
                $movie->getAuthor()->getId()
            ]);
            return new RepositoryResponse(201, "Movie successfully created", $movie);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function updateMovie(Movie $movie): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "UPDATE Movies SET title = ?, description = ?, thumbnail = ?, video = ?, duration = ?, release_date = ?, AuthorID = ? WHERE id = ?";
            $con->prepare($query)->execute([
                $movie->getTitle(),
                $movie->getDescription(),
                $movie->getThumbnail(),
                $movie->getVideo(),
                $movie->getDuration(),
                $movie->getDate(),
                $movie->getAuthor()->getId(),
                $movie->getId()
            ]);
            return new RepositoryResponse(200, "Movie successfully updated", $movie);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function deleteMovie(int $id): RepositoryResponse
    {
        try {
            $con = Connection::getConnection();
            $query = "UPDATE Movies SET deleted= ? WHERE id = ?";
            $con->prepare($query)->execute([1, $id]);
            return new RepositoryResponse(200, "Movie successfully deleted", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
}
