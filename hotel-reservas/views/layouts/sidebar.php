<?php
?>
<!-- Sidebar para Panel Administrativo -->
<div class="sidebar bg-dark text-white vh-100 position-fixed" style="width: 250px;">
    <div class="p-3 border-bottom">
        <h5 class="mb-0">
            <i class="bi bi-building"></i> Panel Admin
        </h5>
    </div>
    
    <nav class="nav flex-column p-3">
        <!-- Dashboard -->
        <a href="<?= url('/dashboard') ?>" 
           class="nav-link text-white <?= isActiveRoute('/dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <!-- Reservas -->
        <div class="nav-item">
            <a href="#reservasSubmenu" 
               class="nav-link text-white" 
               data-bs-toggle="collapse" 
               aria-expanded="<?= strpos($_SERVER['REQUEST_URI'], 'reservas') !== false ? 'true' : 'false' ?>">
                <i class="bi bi-calendar-check"></i> Reservas 
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse <?= strpos($_SERVER['REQUEST_URI'], 'reservas') !== false ? 'show' : '' ?>" 
                 id="reservasSubmenu">
                <nav class="nav flex-column ms-3">
                    <a href="<?= url('/reservas') ?>" 
                       class="nav-link text-white-50 <?= isActiveRoute('/reservas') ?>">
                        <i class="bi bi-list"></i> Ver Todas
                    </a>
                    <a href="<?= url('/reservas/create') ?>" 
                       class="nav-link text-white-50">
                        <i class="bi bi-plus-circle"></i> Nueva Reserva
                    </a>
                    <a href="<?= url('/reservas?estado=pendiente') ?>" 
                       class="nav-link text-white-50">
                        <i class="bi bi-clock"></i> Pendientes
                    </a>
                    <a href="<?= url('/reservas?estado=confirmada') ?>" 
                       class="nav-link text-white-50">
                        <i class="bi bi-check-circle"></i> Confirmadas
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Clientes -->
        <div class="nav-item">
            <a href="#clientesSubmenu" 
               class="nav-link text-white" 
               data-bs-toggle="collapse" 
               aria-expanded="<?= strpos($_SERVER['REQUEST_URI'], 'clientes') !== false ? 'true' : 'false' ?>">
                <i class="bi bi-people"></i> Clientes 
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse <?= strpos($_SERVER['REQUEST_URI'], 'clientes') !== false ? 'show' : '' ?>" 
                 id="clientesSubmenu">
                <nav class="nav flex-column ms-3">
                    <a href="<?= url('/clientes') ?>" 
                       class="nav-link text-white-50 <?= isActiveRoute('/clientes') ?>">
                        <i class="bi bi-list"></i> Ver Todos
                    </a>
                    <a href="<?= url('/clientes/create') ?>" 
                       class="nav-link text-white-50">
                        <i class="bi bi-person-plus"></i> Nuevo Cliente
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Habitaciones (Solo Admin) -->
        <?php if (Session::isAdmin()): ?>
            <div class="nav-item">
                <a href="#habitacionesSubmenu" 
                   class="nav-link text-white" 
                   data-bs-toggle="collapse" 
                   aria-expanded="<?= strpos($_SERVER['REQUEST_URI'], 'habitaciones') !== false ? 'true' : 'false' ?>">
                    <i class="bi bi-door-open"></i> Habitaciones 
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <div class="collapse <?= strpos($_SERVER['REQUEST_URI'], 'habitaciones') !== false ? 'show' : '' ?>" 
                     id="habitacionesSubmenu">
                    <nav class="nav flex-column ms-3">
                        <a href="<?= url('/habitaciones') ?>" 
                           class="nav-link text-white-50 <?= isActiveRoute('/habitaciones') ?>">
                            <i class="bi bi-list"></i> Ver Todas
                        </a>
                        <a href="<?= url('/habitaciones/create') ?>" 
                           class="nav-link text-white-50">
                            <i class="bi bi-plus-circle"></i> Nueva Habitación
                        </a>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
        
        <hr class="my-3">
        
        <!-- Reportes (Solo Admin) -->
        <?php if (Session::isAdmin()): ?>
            <a href="<?= url('/reportes') ?>" class="nav-link text-white">
                <i class="bi bi-graph-up"></i> Reportes
            </a>
        <?php endif; ?>
        
        <!-- Configuración (Solo Admin) -->
        <?php if (Session::isAdmin()): ?>
            <a href="<?= url('/configuracion') ?>" class="nav-link text-white">
                <i class="bi bi-gear"></i> Configuración
            </a>
        <?php endif; ?>
        
        <hr class="my-3">
        
        <!-- Usuario -->
        <div class="nav-item">
            <a href="#usuarioSubmenu" 
               class="nav-link text-white" 
               data-bs-toggle="collapse">
                <i class="bi bi-person-circle"></i> 
                <?= e(Session::get('usuario_nombre')) ?>
                <i class="bi bi-chevron-down float-end"></i>
            </a>
            <div class="collapse" id="usuarioSubmenu">
                <nav class="nav flex-column ms-3">
                    <a href="<?= url('/perfil') ?>" class="nav-link text-white-50">
                        <i class="bi bi-person"></i> Mi Perfil
                    </a>
                    <a href="<?= url('/logout') ?>" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                </nav>
            </div>
        </div>
    </nav>
</div>

<!-- Main Content con Offset -->
<div class="main-content" style="margin-left: 250px;"></div>