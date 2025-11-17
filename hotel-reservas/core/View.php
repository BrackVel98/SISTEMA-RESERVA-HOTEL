<?php
/**
 * Clase View - Manejo de vistas
 */
class View
{
    /**
     * Renderizar una vista
     */
    public static function render($view, $data = [])
    {
        // Convertir array de datos en variables
        extract($data);
        
        // Construir ruta del archivo de vista
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        // Verificar que la vista existe
        if (!file_exists($viewFile)) {
            error_log("Vista no encontrada: {$viewFile}");
            die("Error 500: Vista '{$view}' no encontrada");
        }
        
        // Incluir y renderizar la vista
        require_once $viewFile;
        
        // IMPORTANTE: Terminar la ejecución después de renderizar
        exit;
    }
    
    /**
     * Renderizar una vista con layout
     */
    public static function renderWithLayout($view, $data = [], $layout = 'main')
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        
        if (!file_exists($viewFile)) {
            error_log("Vista no encontrada: {$viewFile}");
            die("Error 500: Vista '{$view}' no encontrada");
        }
        
        if (!file_exists($layoutFile)) {
            error_log("Layout no encontrado: {$layoutFile}");
            die("Error 500: Layout '{$layout}' no encontrado");
        }
        
        // Capturar el contenido de la vista
        ob_start();
        require_once $viewFile;
        $content = ob_get_clean();
        
        // Renderizar con layout
        require_once $layoutFile;
        
        // IMPORTANTE: Terminar la ejecución
        exit;
    }
}