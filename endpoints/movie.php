<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, PUT, DELETE");

require_once("../controllers/movieController.php");

$method = $_SERVER['REQUEST_METHOD'];

try{
    if($method == 'GET' && isset($_GET['id'])){
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            echo 'Error => Id "' . $_GET['id'] . '" is invalid, must be a number.';
            return;
        }
        echo MovieController::get($_GET['id']);
    }
    else if ($method == 'GET'){
        echo MovieController::index();
    }else if ($method == 'POST'){
    
    }else if ($method == 'PUT'){
    
    }else if ($method == 'DELETE'){
    
    }else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
}catch(\Throwable $err){
    echo $err->getMessage();
    header('HTTP/1.1 500 Internal Server Error');
}