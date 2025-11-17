<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\core\Controller.php

/**
 * Clase base Controller
 */
class Controller
{
    public function __construct()
    {
        // Asegurar que la sesión está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Renderizar una vista
     */
    protected function view($view, $data = [])
    {
        View::render($view, $data);
        // exit se llama en View::render()
    }
    
    /**
     * Redirigir a una URL
     */
    protected function redirect($url)
    {
        $base = '/hotel-reservas/public';
        header('Location: ' . $base . $url);
        exit;
    }
    
    /**
     * Enviar respuesta JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}