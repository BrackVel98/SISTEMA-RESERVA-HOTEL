<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\middleware\AuthMiddleware.php

/**
 * Middleware de autenticación
 */
class AuthMiddleware
{
    /**
     * Verificar que el usuario esté autenticado
     */
    public static function handle()
    {
        // Verificar si hay sesión iniciada
        if (!isset($_SESSION['usuario'])) {
            $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta página';
            header('Location: /hotel-reservas/public/login');
            exit;
        }
        
        // Usuario autenticado, continuar
        return true;
    }
    
    /**
     * Verificar que el usuario tenga un rol específico
     */
    public static function checkRole($rolesPermitidos = [])
    {
        // Primero verificar autenticación
        self::handle();
        
        // Si no se especificaron roles, permitir acceso
        if (empty($rolesPermitidos)) {
            return true;
        }
        
        // Verificar rol del usuario
        $rolUsuario = $_SESSION['usuario']['rol'] ?? null;
        
        if (!in_array($rolUsuario, $rolesPermitidos)) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta página';
            header('Location: /hotel-reservas/public/dashboard');
            exit;
        }
        
        return true;
    }
    
    /**
     * Verificar que el usuario NO esté autenticado (para login/register)
     */
    public static function guest()
    {
        if (isset($_SESSION['usuario'])) {
            header('Location: /hotel-reservas/public/dashboard');
            exit;
        }
        
        return true;
    }
}