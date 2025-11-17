<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hotel Reservas' ?> - Sistema de Gestión</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <!-- Estilos adicionales -->
    <?php if (isset($additionalStyles)): ?>
        <?= $additionalStyles ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('/') ?>">
                <i class="bi bi-building"></i> Hotel Reservas
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= isActiveRoute('/') ?>" href="<?= url('/') ?>">
                            <i class="bi bi-house"></i> Inicio
                        </a>
                    </li>
                    
                    <?php if (Session::isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isActiveRoute('/dashboard') ?>" href="<?= url('/dashboard') ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if (Session::isAdmin() || Session::isRecepcionista()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= isActiveRoute('/reservas') ?>" href="<?= url('/reservas') ?>">
                                    <i class="bi bi-calendar-check"></i> Reservas
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link <?= isActiveRoute('/clientes') ?>" href="<?= url('/clientes') ?>">
                                    <i class="bi bi-people"></i> Clientes
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if (Session::isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= isActiveRoute('/habitaciones') ?>" href="<?= url('/habitaciones') ?>">
                                    <i class="bi bi-door-open"></i> Habitaciones
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (Session::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> 
                                <?= e(Session::get('usuario_nombre')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            <?= ucfirst(Session::get('usuario_rol')) ?>
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/perfil') ?>">
                                        <i class="bi bi-person"></i> Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= url('/logout') ?>">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/register') ?>">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Contenido principal -->
    <main class="py-4">
        <div class="container">
            <!-- Mensajes flash -->
            <?= flashMessage('success') ?>
            <?= flashMessage('error') ?>
            <?= flashMessage('warning') ?>
            <?= flashMessage('info') ?>
            
            <!-- Contenido de la página -->
            <?= $content ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-building"></i> Hotel Reservas</h5>
                    <p>Sistema de gestión hotelera - Reservas online</p>
                </div>
                <div class="col-md-3">
                    <h6>Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/') ?>" class="text-white-50">Inicio</a></li>
                        <li><a href="<?= url('/buscar') ?>" class="text-white-50">Buscar Habitaciones</a></li>
                        <li><a href="<?= url('/contacto') ?>" class="text-white-50">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Contacto</h6>
                    <p class="text-white-50">
                        <i class="bi bi-telephone"></i> (01) 123-4567<br>
                        <i class="bi bi-envelope"></i> info@hotel.com<br>
                        <i class="bi bi-geo-alt"></i> Lima, Perú
                    </p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; <?= date('Y') ?> Hotel Reservas. Todos los derechos reservados.</small>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('js/main.js') ?>"></script>
    
    <!-- Scripts adicionales -->
    <?php if (isset($additionalScripts)): ?>
        <?= $additionalScripts ?>
    <?php endif; ?>
</body>
</html>