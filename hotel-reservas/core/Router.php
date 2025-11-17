<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\core\Router.php

class Router
{
    private $routes = [];
    private $basePath = '/hotel-reservas/public';
    
    public function get($path, $handler, $middleware = [])
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post($path, $handler, $middleware = [])
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    private function addRoute($method, $path, $handler, $middleware)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remover query string
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        
        // Remover base path
        if (strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }
        
        // Si está vacío, es la raíz
        if (empty($requestUri)) {
            $requestUri = '/';
        }
        
        // Buscar ruta coincidente
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches);
                    
                    // Ejecutar middleware
                    if (!empty($route['middleware'])) {
                        foreach ($route['middleware'] as $middleware) {
                            if ($middleware === 'auth') {
                                if (!$this->checkAuth()) {
                                    $_SESSION['error'] = 'Debes iniciar sesión para acceder';
                                    header('Location: ' . $this->basePath . '/login');
                                    exit;
                                }
                            }
                        }
                    }
                    
                    // Ejecutar handler
                    $this->executeHandler($route['handler'], $matches);
                    
                    // IMPORTANTE: No continuar después de ejecutar el handler
                    exit;
                }
            }
        }
        
        // No se encontró la ruta
        $this->notFound();
        exit;
    }
    
    private function convertToRegex($path)
    {
        $path = preg_quote($path, '/');
        $path = preg_replace('/\\\{([a-zA-Z0-9_]+)\\\}/', '([a-zA-Z0-9_-]+)', $path);
        return '/^' . $path . '$/';
    }
    
    private function executeHandler($handler, $params = [])
    {
        list($controllerName, $methodName) = explode('@', $handler);
        
        if (!class_exists($controllerName)) {
            error_log("Controlador no encontrado: {$controllerName}");
            die("Error 500: Controlador no encontrado");
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            error_log("Método no encontrado: {$controllerName}@{$methodName}");
            die("Error 500: Método no encontrado");
        }
        
        // Ejecutar método del controlador
        call_user_func_array([$controller, $methodName], $params);
        
        // El método del controlador debe llamar a exit
    }
    
    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    private function notFound()
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }
        h1 { font-size: 120px; margin-bottom: 20px; }
        h2 { font-size: 36px; margin-bottom: 20px; }
        p { font-size: 18px; margin-bottom: 30px; }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>🔍 Página no encontrada</h2>
        <p>La página que buscas no existe.</p>
        <a href="' . $this->basePath . '/" class="btn">🏠 Volver al inicio</a>
    </div>
</body>
</html>';
    }
}