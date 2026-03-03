<?php

namespace App\Core;

class Router {
    private $routes = [];
    private $middlewares = [];
    
    public function get($path, $handler, $middlewares = []) {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }
    
    public function post($path, $handler, $middlewares = []) {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }
    
    public function put($path, $handler, $middlewares = []) {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }
    
    public function delete($path, $handler, $middlewares = []) {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }
    
    private function addRoute($method, $path, $handler, $middlewares) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }
    
    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        $uri = strtok($uri, '?');
        
        // Remove base path if running in subdirectory
        $basePath = '/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        if (empty($uri)) {
            $uri = '/';
        }
        
        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                // Execute middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle();
                }
                
                // Execute handler
                if (is_callable($route['handler'])) {
                    return call_user_func_array($route['handler'], $matches);
                } elseif (is_string($route['handler'])) {
                    [$controller, $method] = explode('@', $route['handler']);
                    $controller = "App\\Controllers\\{$controller}";
                    $controllerInstance = new $controller();
                    return call_user_func_array([$controllerInstance, $method], $matches);
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
    
    private function convertToRegex($path) {
        // Convert :param to named regex groups
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
