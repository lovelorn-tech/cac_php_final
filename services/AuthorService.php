<?php

class AuthorService {
    public static function validateId(int $id): array {
        if (!preg_match("/^[0-9]+$/", $id)) {
            return [false, 'Author ID is invalid.'];
        }
        return [true, ''];
    }
}