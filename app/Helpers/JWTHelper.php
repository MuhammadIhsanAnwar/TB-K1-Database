<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper {
    private static $secret;
    private static $algorithm = 'HS256';
    
    public static function init() {
        $config = require __DIR__ . '/../../config/app.php';
        self::$secret = $config['jwt_secret'];
    }
    
    public static function encode($payload) {
        self::init();
        $config = require __DIR__ . '/../../config/app.php';
        
        $issuedAt = time();
        $expire = $issuedAt + $config['jwt_expire'];
        
        $token = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $payload
        ];
        
        return JWT::encode($token, self::$secret, self::$algorithm);
    }
    
    public static function decode($token) {
        self::init();
        
        try {
            $decoded = JWT::decode($token, new Key(self::$secret, self::$algorithm));
            return $decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }
    
    public static function verify($token) {
        return self::decode($token) !== null;
    }
    
    public static function createRefreshToken() {
        return bin2hex(random_bytes(32));
    }
}
