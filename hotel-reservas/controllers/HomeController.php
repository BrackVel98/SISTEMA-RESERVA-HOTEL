<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\controllers\HomeController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/TipoHabitacion.php';

class HomeController extends Controller
{
    private $tipoModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->tipoModel = new TipoHabitacion();
    }
    
    /**
     * Página de inicio (pública)
     */
    public function index()
    {
        try {
            $tipos = $this->tipoModel->all('nombre ASC');
        } catch (Exception $e) {
            error_log("Error en HomeController: " . $e->getMessage());
            $tipos = [];
        }
        
        $data = [
            'title' => 'Inicio - ' . APP_NAME,
            'tiposHabitacion' => $tipos
        ];
        
        // Renderizar vista (esto llama a exit internamente)
        $this->view('home', $data);
    }
    
    /**
     * Búsqueda de habitaciones
     */
    public function buscar()
    {
        $data = [
            'title' => 'Buscar Habitaciones - ' . APP_NAME
        ];
        
        $this->view('busqueda/resultados', $data);
    }
}