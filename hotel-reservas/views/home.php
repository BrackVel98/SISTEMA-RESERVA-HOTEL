<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\home.php

$base = '/hotel-reservas/public';
$title = $data['title'] ?? 'Hotel Reservas';
$tiposHabitacion = $data['tiposHabitacion'] ?? [];

// Verificar si hay usuario autenticado
$isAuthenticated = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .tipo-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
        }
        .tipo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .tipo-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .price-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <?php require_once __DIR__ . '/components/nav.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a <?= APP_NAME ?></h1>
            <p class="lead mb-5">Encuentra y reserva la habitación perfecta para tu estadía</p>
            
            <?php if ($isAuthenticated): ?>
                <a href="<?= $base ?>/reservas/crear" class="btn btn-light btn-lg px-5 me-3">
                    <i class="fas fa-calendar-plus"></i> Nueva Reserva
                </a>
                <a href="<?= $base ?>/dashboard" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-tachometer-alt"></i> Ir al Dashboard
                </a>
            <?php else: ?>
                <a href="<?= $base ?>/buscar" class="btn btn-light btn-lg px-5 me-3">
                    <i class="fas fa-search"></i> Buscar Habitaciones
                </a>
                <a href="<?= $base ?>/login" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Tipos de Habitación -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">
                <i class="fas fa-bed text-primary"></i>
                Nuestras Habitaciones
            </h2>
            
            <?php if (empty($tiposHabitacion)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    No hay tipos de habitación disponibles en este momento.
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($tiposHabitacion as $tipo): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card tipo-card border-0 shadow">
                                <div class="card-body text-center p-4">
                                    <!-- Icono según tipo -->
                                    <div class="tipo-icon">
                                        <?php
                                        $icono = '🏨';
                                        $nombre = strtolower($tipo['nombre']);
                                        if (strpos($nombre, 'individual') !== false) {
                                            $icono = '🛏️';
                                        } elseif (strpos($nombre, 'doble') !== false) {
                                            $icono = '🛏️🛏️';
                                        } elseif (strpos($nombre, 'familiar') !== false) {
                                            $icono = '👨‍👩‍👧‍👦';
                                        } elseif (strpos($nombre, 'suite') !== false) {
                                            $icono = '🌟';
                                        }
                                        echo $icono;
                                        ?>
                                    </div>
                                    
                                    <h4 class="card-title fw-bold mb-3">
                                        <?= htmlspecialchars($tipo['nombre']) ?>
                                    </h4>
                                    
                                    <p class="card-text text-muted mb-4">
                                        <?= htmlspecialchars($tipo['descripcion']) ?>
                                    </p>
                                    
                                    <!-- Características -->
                                    <div class="mb-4">
                                        <small class="text-muted">
                                            <i class="fas fa-users"></i>
                                            Capacidad: <?= $tipo['capacidad'] ?> personas
                                        </small>
                                    </div>
                                    
                                    <!-- Precio -->
                                    <div class="price-tag">
                                        <h3 class="mb-0">$<?= number_format($tipo['precio_base'], 2) ?></h3>
                                        <small>por noche</small>
                                    </div>
                                    
                                    <!-- Botón de acción -->
                                    <?php if ($isAuthenticated): ?>
                                        <a href="<?= $base ?>/reservas/crear?tipo_habitacion_id=<?= $tipo['id'] ?>" 
                                           class="btn btn-primary btn-lg w-100 mt-3">
                                            <i class="fas fa-calendar-plus"></i>
                                            Reservar Ahora
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $base ?>/buscar?tipo=<?= $tipo['id'] ?>" 
                                           class="btn btn-primary btn-lg w-100 mt-3">
                                            <i class="fas fa-search"></i>
                                            Ver Disponibilidad
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Sección de Características -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">¿Por qué elegirnos?</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h5>WiFi Gratis</h5>
                    <p class="text-muted">Internet de alta velocidad en todas las habitaciones</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="fas fa-parking"></i>
                    </div>
                    <h5>Estacionamiento</h5>
                    <p class="text-muted">Parking gratuito para todos nuestros huéspedes</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="display-4 text-primary mb-3">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h5>Servicio 24/7</h5>
                    <p class="text-muted">Atención personalizada las 24 horas del día</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-2">
                <i class="fas fa-phone me-2"></i> +1 234 567 890
                <span class="mx-3">|</span>
                <i class="fas fa-envelope me-2"></i> info@hotelreservas.com
            </p>
            <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para animaciones -->
    <script>
        // Animación al hacer scroll
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.tipo-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '0';
                        entry.target.style.transform = 'translateY(20px)';
                        
                        setTimeout(() => {
                            entry.target.style.transition = 'all 0.5s ease';
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, 100);
                    }
                });
            });
            
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>
</html>