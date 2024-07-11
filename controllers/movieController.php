<?php

require_once("../repositories/movieRepository.php");

class MovieController {
    public static function index() {
        try{
            $movies = MovieRepository::getMovies();
            $serializedMovies = [];
            foreach ($movies as $movie) {
                $serializedMovies[] = $movie->serialize();
            }
            return json_encode($serializedMovies);
        }catch(\Throwable $err) {
            throw $err;
        }
    }

    public static function get(int $id): string {
        try{
            $movie = MovieRepository::getMovie($id);
            if ($movie) {
                return json_encode($movie->serialize());
            }
            return json_encode([]);
        }catch(\Throwable $err) {
            throw $err;
        }
    }
}