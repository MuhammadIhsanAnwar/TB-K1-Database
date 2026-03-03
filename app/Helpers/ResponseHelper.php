<?php

class ResponseHelper {
    public static function success($message, $data = [], $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
    
    public static function error($message, $errors = [], $status = 400) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ]);
        exit;
    }
    
    public static function unauthorized($message = 'Unauthorized') {
        self::error($message, [], 401);
    }
    
    public static function forbidden($message = 'Forbidden') {
        self::error($message, [], 403);
    }
    
    public static function notFound($message = 'Not found') {
        self::error($message, [], 404);
    }
    
    public static function validationError($errors) {
        self::error('Validation failed', $errors, 422);
    }
}
