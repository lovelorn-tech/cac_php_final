<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");

require_once("../controllers/authorController.php");
require_once("../services/RequestService.php");

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method == 'GET' && isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            echo 'Error => Id "' . $_GET['id'] . '" is invalid, must be a number.';
            return;
        }
        echo json_encode(AuthorController::get($_GET['id'])->serialize());
    } else if ($method == 'GET') {
        echo json_encode(AuthorController::index()->serialize());
    } else if ($method == 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
    } else if ($method == 'PUT') {
        header('HTTP/1.1 405 Method Not Allowed');
    } else if ($method == 'DELETE') {
        $_DELETE = RequestService::getContent();
        if (!isset($_DELETE['id']) || !preg_match("/^[0-9]+$/", $_DELETE['id'])) {
            echo 'Error => Bad request. Missing author ID.';
            return;
        }
        echo json_encode(AuthorController::delete($_DELETE['id'])->serialize());
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
} catch (\Throwable $err) {
    echo $err->getMessage();
    header('HTTP/1.1 500 Internal Server Error');
}
