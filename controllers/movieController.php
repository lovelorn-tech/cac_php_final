<?php

require_once("../repositories/movieRepository.php");
require_once("../repositories/authorRepository.php");
require_once("../models/SerializableArray.php");
require_once("../DTO/movie/movieCreateDTO.php");
require_once("../DTO/movie/movieUpdateDTO.php");
require_once("../DTO/ResponseDTO.php");
require_once("../services/FileService.php");
require_once("../services/MovieService.php");

class MovieController
{

    public static function index(): ResponseDTO
    {
        try {
            $moviesResponse = MovieRepository::getMovies();
            if ($moviesResponse->getStatus() == 200 && $moviesResponse->getData() != null) {
                $movies = $moviesResponse->getData();
                $moviesSerArray = new SerializableArray($movies);
                return new ResponseDTO(200, $moviesResponse->getMessage(), $moviesSerArray);
            }
            return new ResponseDTO(404, "Movies not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function get(int $id): ResponseDTO
    {
        try {
            $movieResponse = MovieRepository::getMovie($id);
            if ($movieResponse->getStatus() == 200 && $movieResponse->getData() != null) {
                return new ResponseDTO(200, $movieResponse->getMessage(), $movieResponse->getData());
            }
            return new ResponseDTO(404, "Movie not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function create(MovieCreateDTO $movieCreateDTO): ResponseDTO
    {
        try {
            // Validate movie input params
            $valid = MovieService::validateMovieCreateDTO($movieCreateDTO);
            if (!$valid[0]) {
                return new ResponseDTO(400, "Bad request. " . $valid[1], null);
            }

            // Validate if author exists.
            $authorResponse = AuthorRepository::getAuthor($movieCreateDTO->getAuthorID());
            if (!$authorResponse->getStatus() == 200 || !$authorResponse->getData() != null) {
                return new ResponseDTO(404, "Failed to create movie. Movie author not found", null);
            }

            // Save thumbnail and video to disk.
            $movieCreateDTO->getThumbnail()->setStoragePath($_SERVER['DOCUMENT_ROOT'] . "/public/storage/thumbnails/");
            $thumbnail = move_uploaded_file(
                $movieCreateDTO->getThumbnail()->getTmpName(),
                $movieCreateDTO->getThumbnail()->getStoragePath()
            );
            $movieCreateDTO->getVideo()->setStoragePath($_SERVER['DOCUMENT_ROOT'] . "/public/storage/videos/");
            $video = move_uploaded_file(
                $movieCreateDTO->getVideo()->getTmpName(),
                $movieCreateDTO->getVideo()->getStoragePath()
            );

            if (!$thumbnail || !$video) {
                FileService::deleteFile($movieCreateDTO->getThumbnail()->getStoragePath());
                FileService::deleteFile($movieCreateDTO->getVideo()->getStoragePath());
                return new ResponseDTO(500, "Failed to create movie. Failed to save thumbnail or video", null);
            }

            // Create movie entity.
            $movie = new Movie(
                0,
                $movieCreateDTO->getTitle(),
                $movieCreateDTO->getDescription(),
                $authorResponse->getData(),
                $movieCreateDTO->getThumbnail()->getStorageName(),
                $movieCreateDTO->getVideo()->getStorageName(),
                $movieCreateDTO->getDuration(),
                $movieCreateDTO->getReleaseDate()
            );

            // Save movie on DB
            $movieResponse = MovieRepository::createMovie($movie);
            return new ResponseDTO($movieResponse->getStatus(), $movieResponse->getMessage(), $movieResponse->getData());
        } catch (\Throwable $err) {
            FileService::deleteFile($movieCreateDTO->getThumbnail()->getStoragePath());
            FileService::deleteFile($movieCreateDTO->getVideo()->getStoragePath());
            throw $err;
        }
    }

    public static function update(MovieUpdateDTO $movieUpdateDTO): ResponseDTO
    {
        try {
            // Validate movie input params
            $valid = MovieService::validateMovieUpdateDTO($movieUpdateDTO);
            if (!$valid[0]) {
                return new ResponseDTO(400, "Bad request. " . $valid[1], null);
            }

            // Validate if movie exists.
            $movieResponse = MovieRepository::getMovie($movieUpdateDTO->getId());
            if ($movieResponse->getStatus() != 200 || $movieResponse->getData() == null) {
                return new ResponseDTO(404, "Movie not found", null);
            }
            $movieDB = $movieResponse->getData();

            // Validate if author exists.
            if ($movieUpdateDTO->getAuthorID() != null && $movieUpdateDTO->getAuthorID() != $movieDB->getAuthor()->getId()) {
                $authorResponse = AuthorRepository::getAuthor($movieUpdateDTO->getAuthorID());
                if (!$authorResponse->getStatus() == 200 || !$authorResponse->getData() != null) {
                    return new ResponseDTO(404, "Failed to update movie. Movie author not found", null);
                }
                $movieDB->setAuthor($authorResponse->getData());
            }

            if ($movieUpdateDTO->getTitle()) {
                $movieDB->setTitle($movieUpdateDTO->getTitle());
            }

            if ($movieUpdateDTO->getDescription()) {
                $movieDB->setDescription($movieUpdateDTO->getDescription());
            }

            if ($movieUpdateDTO->getReleaseDate()) {
                $movieDB->setDate($movieUpdateDTO->getReleaseDate());
            }

            if ($movieUpdateDTO->getDuration()) {
                $movieDB->setDuration($movieUpdateDTO->getDuration());
            }

            // Update movie on DB
            $movieResponse = MovieRepository::updateMovie($movieDB);
            return new ResponseDTO($movieResponse->getStatus(), $movieResponse->getMessage(), $movieResponse->getData());
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    public static function updateFiles(int $id, ?File $thumbnail, ?File $video)
    {
        try {
            // Validate movie input params
            $valid = MovieService::validateId($id);
            if (!$valid[0]) {
                return new ResponseDTO(400, "Bad request. " . $valid[1], null);
            }

            // Validate if movie exists.
            $movieResponse = MovieRepository::getMovie($id);
            if ($movieResponse->getStatus() != 200 || $movieResponse->getData() == null) {
                return new ResponseDTO(404, "Movie not found", null);
            }
            $movieDB = $movieResponse->getData();

            // Save thumbnail and video to disk if exists.
            $oldThumbnailPath = null;
            $oldVideoPath = null;

            if ($thumbnail != null) {
                $valid = MovieService::validateThumbnail($thumbnail);
                if (!$valid[0]) {
                    return new ResponseDTO(400, "Bad request. " . $valid[1], null);
                }
                $thumbnail->setStoragePath($_SERVER['DOCUMENT_ROOT'] . "/public/storage/thumbnails/");
                $uploaded = move_uploaded_file(
                    $thumbnail->getTmpName(),
                    $thumbnail->getStoragePath()
                );
                if (!$uploaded) {
                    FileService::deleteFile($thumbnail->getStoragePath());
                    return new ResponseDTO(500, "Failed to update movie. Failed to save thumbnail or video", null);
                }
                $oldThumbnailPath = $movieDB->getThumbnail();
                $movieDB->setThumbnail($thumbnail->getStoragePath());
            }

            if ($video != null) {
                $valid = MovieService::validateVideo($video);
                if (!$valid[0]) {
                    return new ResponseDTO(400, "Bad request. " . $valid[1], null);
                }
                $video->setStoragePath($_SERVER['DOCUMENT_ROOT'] . "/public/storage/videos/");
                $uploaded = move_uploaded_file(
                    $video->getTmpName(),
                    $video->getStoragePath()
                );
                if (!$uploaded) {
                    FileService::deleteFile($video->getStoragePath());
                    $thumbnail != null
                        ? FileService::deleteFile($thumbnail->getStoragePath())
                        : false;
                    return new ResponseDTO(500, "Failed to create movie. Failed to save thumbnail or video", null);
                }
                $oldVideoPath = $movieDB->getVideo();
                $movieDB->setVideo($video->getStoragePath());
            }

            // Update movie in DB
            $movieResponse = MovieRepository::updateMovie($movieDB);
            if ($movieResponse->getStatus() == 200) {
                $oldThumbnailPath != null ? FileService::deleteFile($oldThumbnailPath->getStoragePath()) : false;
                $oldVideoPath != null ? FileService::deleteFile($oldVideoPath->getStoragePath()) : false;
            }
        } catch (\Throwable $err) {
            $thumbnail != null && $thumbnail->getStoragePath()
                ? FileService::deleteFile($thumbnail->getStoragePath())
                : false;
            $video != null && $video->getStoragePath()
                ? FileService::deleteFile($video->getStoragePath())
                : false;
            throw $err;
        }
    }

    public static function delete(int $id): ResponseDTO
    {
        try {
            $valid = MovieService::validateId($id);
            if (!$valid[0]) {
                return new ResponseDTO(400, "Bad request. " . $valid[1], null);
            }
            $movieResponse = MovieRepository::getMovie($id);
            if ($movieResponse->getStatus() == 200 && $movieResponse->getData() != null) {
                $movieResponse = MovieRepository::deleteMovie($movieResponse->getData()->getId());
                return new ResponseDTO(200, $movieResponse->getMessage(), null);
            }
            return new ResponseDTO(404, "Movie not found", null);
        } catch (\Throwable $err) {
            throw $err;
        }
    }
}
