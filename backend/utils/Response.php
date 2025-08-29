<?php
class Response {
    public static function json($data, $status=200){
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *"); // CORS
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
