<?php

class RequestService {
    public static function getContent(): array {
        $_METHOD = array();
        parse_str(file_get_contents("php://input"), $_METHOD);
        return $_METHOD;
    }
}