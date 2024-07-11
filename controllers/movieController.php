<?php

require_once("../repositories/movieRepository.php");
require_once("../models/SerializableArray.php");
require_once("../DTO/ResponseDTO.php");

class MovieController {
    public static function index(): ResponseDTO {
        try{
            $moviesResponse = MovieRepository::getMovies();
            if ($moviesResponse->getStatus() == 200 && $moviesResponse->getData() != null){
                $movies = $moviesResponse->getData();
                $moviesSerArray = new SerializableArray($movies);
                return new ResponseDTO(200, $moviesResponse->getMessage(), $moviesSerArray);
            }
            return new ResponseDTO(404, "Movies not found", null);
        }catch(\Throwable $err) {
            throw $err;
        }
    }

    public static function get(int $id): ResponseDTO {
        try{
            $movieResponse = MovieRepository::getMovie($id);
            if ($movieResponse->getStatus() == 200 && $movieResponse->getData() != null) {
                return new ResponseDTO(200, $movieResponse->getMessage(), $movieResponse->getData());
            }
            return new ResponseDTO(404, "Movie not found", null);
        }catch(\Throwable $err) {
            throw $err;
        }
    }
}