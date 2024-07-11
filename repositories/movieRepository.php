<?php

require_once("../models/Movie.php");
require_once("../config/db.php");

class MovieRepository {
    public static function getMovie(int $id): ?Movie {
        try{
            $con = Connection::getConnection();
            $query = "SELECT m.id, m.title, m.description, m.thumbnail, m.video, m.duration, m.release_date, a.id AS author_id, a.name as author_name, a.lastname AS author_lastname, a.dob AS author_dob FROM movies m INNER JOIN Authors a ON m.AuthorID = a.id WHERE m.id = ?";
            $stmt = $con->prepare($query);
            $stmt->execute([$id]);
            $movie = $stmt->fetch();
            if ($movie) {
                return new Movie(
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
            return null;
        }catch(\Throwable $err) {
            throw $err;
        }
    }

    public static function getMovies(): array {
        try{
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
            return $movies;
        }catch(\Throwable $err) {
            throw $err;
        }
    }
}