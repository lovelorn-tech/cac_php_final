<?php 

require_once("../DTO/movie/movieCreateDTO.php");
require_once("../models/File.php");
require_once("AuthorService.php");

class MovieService {
    public static function validateMovieCreateDTO(MovieCreateDTO $movie): array {
        $valid = AuthorService::validateId($movie->getAuthorID());
        if (!$valid[0]) return $valid;
        $valid = MovieService::validateThumbnail($movie->getThumbnail());
        if (!$valid[0]) return $valid;
        return MovieService::validateVideo($movie->getVideo());
    }

    public static function validateMovieUpdateDTO(MovieUpdateDTO $movie): array {
        $valid = MovieService::validateId($movie->getId());
        if (!$valid[0]) return $valid;
        if ($movie->getAuthorID() != null){
            $valid = AuthorService::validateId($movie->getAuthorID());
            if (!$valid[0]) return $valid;
        }
        return $valid;
    }

    public static function validateId(int $id): array {
        if (!preg_match("/^[0-9]+$/", $id)) {
            return [false, 'Movie ID is invalid.'];
        }
        return [true, ''];
    }

    public static function validateThumbnail(File $thumbnail): array {
        if ($thumbnail->getSize() > 1000000) {
            return [false, 'Thumbnail file exceeds the size limit.'];
        }
        if(!in_array(pathinfo($thumbnail->getOriginalName(), PATHINFO_EXTENSION), ['jpg', 'png', 'gif', 'jpeg'])){
            return [false, 'Thumbnail file type is invalid.'];
        }
        return [true, ''];
    }

    public static function validateVideo(File $video): array {
        if ($video->getSize() > 100000000) {
            return [false, 'Video file exceeds the size limit.'];
        }
        if(!in_array(pathinfo($video->getOriginalName(), PATHINFO_EXTENSION), ['mp4', 'webp'])){
            return [false, 'Video file type is invalid.'];
        }
        return [true, ''];
    }
}