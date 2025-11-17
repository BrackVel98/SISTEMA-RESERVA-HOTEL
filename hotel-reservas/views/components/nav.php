<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\components\nav.php

$base = '/hotel-reservas/public';
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Usuario';
$currentPage = $_SERVER['REQUEST_URI'] ?? '';

// Función para determinar si una página está activa
function isActive($page, $currentPage) {
    return strpos($currentPage, $page) !== false ? 'active' : '';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        
        <!-- Logo y Nombre -->
        <a class="navbar-brand fw-bold" href="<?= $base ?>/">
            <i class="fas fa-hotel me-2"></i>
            <?= APP_NAME ?>
        </a>
        
        <!-- Botón Hamburguesa (Móvil) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menú Principal -->
        <div class="collapse navbar-collapse" id="navbarMain">
            
            <?php if ($isLoggedIn): ?>
                
                <!-- Menú Izquierdo (Links Principales) -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('dashboard', $currentPage) ?>" 
                           href="<?= $base ?>/dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('reservas', $currentPage) ?>" 
                           href="<?= $base ?>/reservas">
                            <i class="fas fa-calendar-check me-1"></i>
                            Reservas
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('habitaciones', $currentPage) ?>" 
                           href="<?= $base ?>/habitaciones">
                            <i class="fas fa-bed me-1"></i>
                            Habitaciones
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('clientes', $currentPage) ?>" 
                           href="<?= $base ?>/clientes">
                            <i class="fas fa-users me-1"></i>
                            Clientes
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('pagos', $currentPage) ?>" 
                           href="<?= $base ?>/pagos">
                            <i class="fas fa-dollar-sign me-1"></i>
                            Pagos
                        </a>
                    </li>
                    
                </ul>
                
                <!-- Menú Derecho (Usuario) -->
                <ul class="navbar-nav ms-auto">
                    
                    <!-- Dropdown Usuario -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" 
                           href="#" 
                           id="userDropdown" 
                           role="button" 
                           data-bs-toggle="dropdown" 
                           aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span><?= htmlspecialchars($userName) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item text-danger" href="<?= $base ?>/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
                
            <?php else: ?>
                
                <!-- Menú para usuarios NO autenticados -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base ?>/">
                            <i class="fas fa-home me-1"></i>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('login', $currentPage) ?>" 
                           href="<?= $base ?>/login">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Iniciar Sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('register', $currentPage) ?>" 
                           href="<?= $base ?>/register">
                            <i class="fas fa-user-plus me-1"></i>
                            Registrarse
                        </a>
                    </li>
                </ul>
                
            <?php endif; ?>
            
        </div>
        
    </div>
</nav>

<!-- Estilos adicionales para el navbar -->
<style>
.navbar-nav .nav-link {
    transition: all 0.3s ease;
    border-radius: 5px;
    margin: 0 2px;
    padding: 0.5rem 1rem;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.navbar-nav .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 200px;
}

.dropdown-item {
    padding: 0.7rem 1.5rem;
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

.dropdown-item:hover {
    background-color: rgba(220, 53, 69, 0.1);
    padding-left: 2rem;
}

.dropdown-item i {
    width: 20px;
}

/* Asegurar que el dropdown funcione en móviles */
@media (max-width: 991.98px) {
    .dropdown-menu {
        position: static !important;
        transform: none !important;
        box-shadow: none;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dropdown-item {
        color: #fff;
    }
    
    .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
}
</style>