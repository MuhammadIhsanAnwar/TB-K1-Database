<?php
/**
 * Simplified JWT implementation for cPanel compatibility
 * Based on Firebase JWT library
 */

namespace Firebase\JWT;

class JWT {
    public static function encode($payload, $key, $alg = 'HS256') {
        $header = ['typ' => 'JWT', 'alg' => $alg];
        
        $segments = [];
        $segments[] = self::urlsafeB64Encode(json_encode($header));
        $segments[] = self::urlsafeB64Encode(json_encode($payload));
        
        $signing_input = implode('.', $segments);
        $signature = self::sign($signing_input, $key, $alg);
        $segments[] = self::urlsafeB64Encode($signature);
        
        return implode('.', $segments);
    }
    
    public static function decode($jwt, $key) {
        $tks = explode('.', $jwt);
        
        if (count($tks) != 3) {
            throw new \Exception('Wrong number of segments');
        }
        
        list($headb64, $bodyb64, $cryptob64) = $tks;
        
        $header = json_decode(self::urlsafeB64Decode($headb64));
        $payload = json_decode(self::urlsafeB64Decode($bodyb64));
        $sig = self::urlsafeB64Decode($cryptob64);
        
        if ($payload->exp < time()) {
            throw new \Exception('Expired token');
        }
        
        $signing_input = $headb64 . '.' . $bodyb64;
        
        if ($key instanceof Key) {
            $key = $key->key;
        }
        
        if (!self::verify($signing_input, $sig, $key, $header->alg)) {
            throw new \Exception('Signature verification failed');
        }
        
        return $payload;
    }
    
    private static function sign($msg, $key, $alg) {
        switch ($alg) {
            case 'HS256':
                return hash_hmac('sha256', $msg, $key, true);
            default:
                throw new \Exception('Algorithm not supported');
        }
    }
    
    private static function verify($msg, $signature, $key, $alg) {
        switch ($alg) {
            case 'HS256':
                $hash = hash_hmac('sha256', $msg, $key, true);
                return hash_equals($signature, $hash);
            default:
                throw new \Exception('Algorithm not supported');
        }
    }
    
    private static function urlsafeB64Encode($input) {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }
    
    private static function urlsafeB64Decode($input) {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}

class Key {
    public $key;
    public $algorithm;
    
    public function __construct($key, $algorithm) {
        $this->key = $key;
        $this->algorithm = $algorithm;
    }
}
