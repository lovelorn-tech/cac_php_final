<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");

require_once("../controllers/movieController.php");
require_once("../DTO/movie/movieCreateDTO.php");
require_once("../services/RequestService.php");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'GET' && isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            echo 'Error => Id "' . $_GET['id'] . '" is invalid, must be a number.';
            return;
        }
        echo json_encode(MovieController::get($_GET['id'])->serialize());
    } else if ($method == 'GET') {
        echo json_encode(MovieController::index()->serialize());
    } else if ($method == 'POST') {
        if (isset($_POST['title']) && isset($_POST['duration']) && isset($_POST['release_date']) && isset($_POST['author_id']) && isset($_FILES['thumbnail']) && isset($_FILES['video'])) {
            $thumbnail = $_FILES['thumbnail'];
            $thumbnailFile = new File($thumbnail['name'], $thumbnail['type'], $thumbnail['size'], $thumbnail['tmp_name'], $thumbnail['error'], $thumbnail['full_path']);
            $video = $_FILES['video'];
            $videoFile = new File($video['name'], $video['type'], $video['size'], $video['tmp_name'], $video['error'], $video['full_path']);
            $movie = new MovieCreateDTO($_POST['title'], isset($_POST['description']) ? $_POST['description'] : null,  $_POST['duration'], $_POST['release_date'], $_POST['author_id'], $thumbnailFile, $videoFile);
            echo json_encode(MovieController::create($movie)->serialize());
            return;
        }
        echo "Bad request. Missing data.";
        return;
    } else if ($method == 'PUT') {
        $_PUT = RequestService::getContent();
        if (!isset($_PUT['id']) || !preg_match("/^[0-9]+$/", $_PUT['id'])) {
            echo 'Error => Bad request. Missing movie ID or invalid.';
            return;
        }
        $movie = new MovieUpdateDTO(
            $_PUT['id'],
            isset($_PUT['title']) ? $_PUT['title'] : null,
            isset($_PUT['description']) ? $_PUT['description'] : null,
            isset($_PUT['duration']) ? $_PUT['duration'] : null,
            isset($_PUT['release_date']) ? $_PUT['release_date'] : null,
            isset($_PUT['author_id']) ? $_PUT['author_id'] : null
        );
        echo json_encode(MovieController::update($movie)->serialize());
    } else if ($method == 'DELETE') {
        $_DELETE = RequestService::getContent();
        if (!isset($_DELETE['id']) || !preg_match("/^[0-9]+$/", $_DELETE['id'])) {
            echo 'Error => Bad request. Missing movie ID.';
            return;
        }
        echo json_encode(MovieController::delete($_DELETE['id'])->serialize());
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
} catch (\Throwable $err) {
    echo $err->getMessage();
    header('HTTP/1.1 500 Internal Server Error');
}
