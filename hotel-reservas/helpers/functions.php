<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\helpers\functions.php

/**
 * Funciones auxiliares globales
 */

/**
 * Escapar HTML para prevenir XSS
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Formatear moneda en soles peruanos
 */
function formatCurrency($amount)
{
    if ($amount === null || $amount === '') {
        return 'S/ 0.00';
    }
    return 'S/ ' . number_format((float)$amount, 2, '.', ',');
}

/**
 * Formatear fecha
 */
function formatDate($date, $format = 'd/m/Y')
{
    if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
        return '-';
    }
    
    try {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        if ($timestamp === false) {
            return '-';
        }
        return date($format, $timestamp);
    } catch (Exception $e) {
        return '-';
    }
}

/**
 * Formatear fecha y hora
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i')
{
    return formatDate($datetime, $format);
}

/**
 * Calcular días entre dos fechas
 */
function daysBetween($startDate, $endDate)
{
    try {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $diff = $start->diff($end);
        return $diff->days;
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Obtener badge de estado de reserva
 */
function estadoReservaBadge($estado)
{
    $badges = [
        'pendiente' => '<span class="badge badge-warning">⏳ Pendiente</span>',
        'confirmada' => '<span class="badge badge-success">✅ Confirmada</span>',
        'cancelada' => '<span class="badge badge-danger">❌ Cancelada</span>',
        'completada' => '<span class="badge badge-info">🎉 Completada</span>',
        'en_proceso' => '<span class="badge badge-primary">🔄 En Proceso</span>'
    ];
    
    return $badges[strtolower($estado)] ?? '<span class="badge badge-secondary">' . e($estado) . '</span>';
}

/**
 * Obtener badge de estado de pago
 */
function estadoPagoBadge($estado)
{
    $badges = [
        'pendiente' => '<span class="badge badge-warning">⏳ Pendiente</span>',
        'pagado' => '<span class="badge badge-success">💰 Pagado</span>',
        'parcial' => '<span class="badge badge-info">📊 Parcial</span>',
        'reembolsado' => '<span class="badge badge-secondary">↩️ Reembolsado</span>',
        'cancelado' => '<span class="badge badge-danger">❌ Cancelado</span>'
    ];
    
    return $badges[strtolower($estado)] ?? '<span class="badge badge-secondary">' . e($estado) . '</span>';
}

/**
 * Obtener badge de estado de habitación
 */
function estadoHabitacionBadge($estado)
{
    $badges = [
        'disponible' => '<span class="badge badge-success">✅ Disponible</span>',
        'ocupada' => '<span class="badge badge-danger">🔴 Ocupada</span>',
        'mantenimiento' => '<span class="badge badge-warning">🔧 Mantenimiento</span>',
        'limpieza' => '<span class="badge badge-info">🧹 Limpieza</span>',
        'reservada' => '<span class="badge badge-primary">📅 Reservada</span>',
        'bloqueada' => '<span class="badge badge-secondary">🚫 Bloqueada</span>'
    ];
    
    return $badges[strtolower($estado)] ?? '<span class="badge badge-secondary">' . e($estado) . '</span>';
}

/**
 * Mostrar mensaje flash
 */
function flashMessage($type = null)
{
    if ($type === null) {
        // Mostrar todos los mensajes flash
        $messages = [];
        
        if (isset($_SESSION['success'])) {
            $messages[] = [
                'type' => 'success',
                'message' => $_SESSION['success'],
                'icon' => '✅'
            ];
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            $messages[] = [
                'type' => 'error',
                'message' => $_SESSION['error'],
                'icon' => '❌'
            ];
            unset($_SESSION['error']);
        }
        
        if (isset($_SESSION['warning'])) {
            $messages[] = [
                'type' => 'warning',
                'message' => $_SESSION['warning'],
                'icon' => '⚠️'
            ];
            unset($_SESSION['warning']);
        }
        
        if (isset($_SESSION['info'])) {
            $messages[] = [
                'type' => 'info',
                'message' => $_SESSION['info'],
                'icon' => 'ℹ️'
            ];
            unset($_SESSION['info']);
        }
        
        return $messages;
    }
    
    // Obtener mensaje flash específico
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    
    return null;
}

/**
 * Renderizar mensajes flash
 */
function renderFlashMessages()
{
    $messages = flashMessage();
    
    if (empty($messages)) {
        return '';
    }
    
    $html = '';
    foreach ($messages as $msg) {
        $html .= sprintf(
            '<div class="alert alert-%s" role="alert">
                <span class="alert-icon">%s</span>
                <span class="alert-message">%s</span>
            </div>',
            $msg['type'],
            $msg['icon'],
            e($msg['message'])
        );
    }
    
    return $html;
}

/**
 * Verificar si la ruta actual está activa
 */
function isActiveRoute($route, $exact = false)
{
    $currentUri = $_SERVER['REQUEST_URI'];
    
    // Limpiar la URI actual
    $currentUri = str_replace('/hotel-reservas/public', '', $currentUri);
    $currentUri = parse_url($currentUri, PHP_URL_PATH);
    
    // Si está vacía, es la raíz
    if (empty($currentUri) || $currentUri === '') {
        $currentUri = '/';
    }
    
    // Comparación exacta
    if ($exact) {
        return $currentUri === $route;
    }
    
    // Comparación por prefijo
    if ($route === '/') {
        return $currentUri === '/';
    }
    
    return strpos($currentUri, $route) === 0;
}

/**
 * Obtener clase CSS para ruta activa
 */
function activeClass($route, $class = 'active', $exact = false)
{
    return isActiveRoute($route, $exact) ? $class : '';
}

/**
 * Obtener mensaje flash
 */
function getFlash($key)
{
    if (isset($_SESSION['flash_' . $key])) {
        $message = $_SESSION['flash_' . $key];
        unset($_SESSION['flash_' . $key]);
        return $message;
    }
    return null;
}

/**
 * Establecer mensaje flash
 */
function setFlash($key, $message)
{
    $_SESSION['flash_' . $key] = $message;
}

/**
 * Establecer mensaje de éxito
 */
function setSuccess($message)
{
    $_SESSION['success'] = $message;
}

/**
 * Establecer mensaje de error
 */
function setError($message)
{
    $_SESSION['error'] = $message;
}

/**
 * Establecer mensaje de advertencia
 */
function setWarning($message)
{
    $_SESSION['warning'] = $message;
}

/**
 * Establecer mensaje de información
 */
function setInfo($message)
{
    $_SESSION['info'] = $message;
}

/**
 * Verificar si usuario está autenticado
 */
function isAuthenticated()
{
    return isset($_SESSION['usuario']);
}

/**
 * Obtener usuario actual
 */
function currentUser()
{
    return $_SESSION['usuario'] ?? null;
}

/**
 * Verificar si el usuario tiene un rol específico
 */
function hasRole($role)
{
    $user = currentUser();
    return $user && isset($user['rol']) && $user['rol'] === $role;
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin()
{
    return hasRole('administrador');
}

/**
 * Verificar si el usuario es recepcionista
 */
function isRecepcionista()
{
    return hasRole('recepcionista');
}

/**
 * Redirigir a una URL
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/**
 * Obtener URL base (CORREGIDA)
 */
function baseUrl($path = '')
{
    $base = '/hotel-reservas/public';
    
    // Si no hay path, devolver solo la base
    if (empty($path)) {
        return $base;
    }
    
    // Eliminar slash inicial si existe
    $path = ltrim($path, '/');
    
    // Devolver base + path
    return $base . '/' . $path;
}

/**
 * Generar URL de logout
 */
function logoutUrl()
{
    return '/hotel-reservas/public/logout';
}

/**
 * Generar URL de login
 */
function loginUrl()
{
    return '/hotel-reservas/public/login';
}

/**
 * Generar URL de dashboard
 */
function dashboardUrl()
{
    return '/hotel-reservas/public/dashboard';
}

/**
 * Generar token CSRF
 */
function csrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Campo CSRF para formularios
 */
function csrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Verificar token CSRF
 */
function verifyCsrf($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtener el estado de una reserva con estilo (alias de estadoReservaBadge)
 */
function getEstadoBadge($estado)
{
    return estadoReservaBadge($estado);
}

/**
 * Obtener el estado de un pago con estilo (alias de estadoPagoBadge)
 */
function getEstadoPagoBadge($estado)
{
    return estadoPagoBadge($estado);
}

/**
 * Validar email
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar fecha
 */
function isValidDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Truncar texto
 */
function truncate($text, $length = 100, $suffix = '...')
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generar opciones de select para tipos de habitación
 */
function tipoHabitacionOptions($selected = null)
{
    try {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id, nombre FROM tipos_habitacion ORDER BY nombre ASC");
        $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $html = '<option value="">Seleccione un tipo</option>';
        foreach ($tipos as $tipo) {
            $isSelected = $selected == $tipo['id'] ? 'selected' : '';
            $html .= sprintf(
                '<option value="%d" %s>%s</option>',
                $tipo['id'],
                $isSelected,
                e($tipo['nombre'])
            );
        }
        return $html;
    } catch (Exception $e) {
        return '<option value="">Error al cargar tipos</option>';
    }
}

/**
 * Debug - Imprimir variable de forma legible (solo en desarrollo)
 */
function dd($var)
{
    echo '<pre style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; margin: 20px; overflow: auto; font-family: monospace;">';
    var_dump($var);
    echo '</pre>';
    die();
}

/**
 * Obtener mensaje de error de sesión
 */
function getError()
{
    return flashMessage('error');
}

/**
 * Obtener mensaje de éxito de sesión
 */
function getSuccess()
{
    return flashMessage('success');
}

/**
 * Generar ID único
 */
function generateUniqueId($prefix = '')
{
    return $prefix . uniqid() . bin2hex(random_bytes(4));
}

/**
 * Sanitizar string
 */
function sanitize($string)
{
    return trim(strip_tags($string));
}

/**
 * Verificar si es solicitud POST
 */
function isPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verificar si es solicitud GET
 */
function isGet()
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Obtener valor de POST
 */
function post($key, $default = null)
{
    return $_POST[$key] ?? $default;
}

/**
 * Obtener valor de GET
 */
function get($key, $default = null)
{
    return $_GET[$key] ?? $default;
}

/**
 * Obtener todos los datos POST
 */
function postData()
{
    return $_POST;
}

/**
 * Obtener IP del cliente
 */
function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * Verificar si una fecha es hoy
 */
function esHoy($fecha)
{
    if (empty($fecha)) {
        return false;
    }
    
    try {
        $fecha = is_numeric($fecha) ? date('Y-m-d', $fecha) : date('Y-m-d', strtotime($fecha));
        $hoy = date('Y-m-d');
        return $fecha === $hoy;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Verificar si una fecha es mañana
 */
function esMañana($fecha)
{
    if (empty($fecha)) {
        return false;
    }
    
    try {
        $fecha = is_numeric($fecha) ? date('Y-m-d', $fecha) : date('Y-m-d', strtotime($fecha));
        $mañana = date('Y-m-d', strtotime('+1 day'));
        return $fecha === $mañana;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Verificar si una fecha es en el pasado
 */
function esPasado($fecha)
{
    if (empty($fecha)) {
        return false;
    }
    
    try {
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        return $timestamp < time();
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Verificar si una fecha es en el futuro
 */
function esFuturo($fecha)
{
    if (empty($fecha)) {
        return false;
    }
    
    try {
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        return $timestamp > time();
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Verificar si una reserva está activa (entre fechas de entrada y salida)
 */
function reservaActiva($fechaEntrada, $fechaSalida)
{
    try {
        $entrada = strtotime($fechaEntrada);
        $salida = strtotime($fechaSalida);
        $ahora = time();
        
        return $ahora >= $entrada && $ahora <= $salida;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Obtener días restantes hasta una fecha
 */
function diasRestantes($fecha)
{
    try {
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        $hoy = strtotime(date('Y-m-d'));
        $diff = $timestamp - $hoy;
        return floor($diff / (60 * 60 * 24));
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Obtener texto relativo de fecha (hace 2 días, en 3 días, etc.)
 */
function fechaRelativa($fecha)
{
    if (empty($fecha)) {
        return '-';
    }
    
    try {
        $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
        $diff = time() - $timestamp;
        $dias = floor($diff / (60 * 60 * 24));
        
        if ($dias == 0) {
            $horas = floor($diff / (60 * 60));
            if ($horas == 0) {
                $minutos = floor($diff / 60);
                if ($minutos == 0) {
                    return 'Ahora mismo';
                }
                return $minutos == 1 ? 'Hace 1 minuto' : "Hace $minutos minutos";
            }
            return $horas == 1 ? 'Hace 1 hora' : "Hace $horas horas";
        } elseif ($dias == 1) {
            return 'Ayer';
        } elseif ($dias < 7) {
            return "Hace $dias días";
        } elseif ($dias < 30) {
            $semanas = floor($dias / 7);
            return $semanas == 1 ? 'Hace 1 semana' : "Hace $semanas semanas";
        } elseif ($dias < 365) {
            $meses = floor($dias / 30);
            return $meses == 1 ? 'Hace 1 mes' : "Hace $meses meses";
        } else {
            $años = floor($dias / 365);
            return $años == 1 ? 'Hace 1 año' : "Hace $años años";
        }
    } catch (Exception $e) {
        return '-';
    }
}

/**
 * Verificar si una habitación está disponible en un rango de fechas
 */
function habitacionDisponible($habitacionId, $fechaEntrada, $fechaSalida, $reservaIdExcluir = null)
{
    try {
        $db = Database::getInstance();
        
        $sql = "SELECT COUNT(*) as total 
                FROM reservas 
                WHERE habitacion_id = :habitacion_id 
                AND estado NOT IN ('cancelada', 'completada')
                AND (
                    (fecha_entrada <= :fecha_entrada AND fecha_salida > :fecha_entrada)
                    OR (fecha_entrada < :fecha_salida AND fecha_salida >= :fecha_salida)
                    OR (fecha_entrada >= :fecha_entrada AND fecha_salida <= :fecha_salida)
                )";
        
        $params = [
            'habitacion_id' => $habitacionId,
            'fecha_entrada' => $fechaEntrada,
            'fecha_salida' => $fechaSalida
        ];
        
        if ($reservaIdExcluir) {
            $sql .= " AND id != :reserva_id";
            $params['reserva_id'] = $reservaIdExcluir;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] == 0;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Obtener color según el estado de la habitación
 */
function colorEstadoHabitacion($estado)
{
    $colores = [
        'disponible' => '#28a745',    // Verde
        'ocupada' => '#dc3545',        // Rojo
        'mantenimiento' => '#ffc107',  // Amarillo
        'limpieza' => '#17a2b8',       // Azul claro
        'reservada' => '#667eea',      // Azul
        'bloqueada' => '#6c757d'       // Gris
    ];
    
    return $colores[strtolower($estado)] ?? '#6c757d';
}

/**
 * Obtener icono según el estado de la habitación
 */
function iconoEstadoHabitacion($estado)
{
    $iconos = [
        'disponible' => '✅',
        'ocupada' => '🔴',
        'mantenimiento' => '🔧',
        'limpieza' => '🧹',
        'reservada' => '📅',
        'bloqueada' => '🚫'
    ];
    
    return $iconos[strtolower($estado)] ?? '❓';
}

/**
 * Calcular el porcentaje de ocupación
 */
function porcentajeOcupacion($habitacionesOcupadas, $habitacionesTotales)
{
    if ($habitacionesTotales == 0) {
        return 0;
    }
    
    return round(($habitacionesOcupadas / $habitacionesTotales) * 100, 2);
}

/**
 * Obtener badge de prioridad
 */
function prioridadBadge($prioridad)
{
    $badges = [
        'alta' => '<span class="badge badge-danger">🔥 Alta</span>',
        'media' => '<span class="badge badge-warning">⚡ Media</span>',
        'baja' => '<span class="badge badge-info">📊 Baja</span>'
    ];
    
    return $badges[strtolower($prioridad)] ?? '<span class="badge badge-secondary">' . e($prioridad) . '</span>';
}

/**
 * Formatear número de teléfono peruano
 */
function formatearTelefono($telefono)
{
    // Eliminar caracteres no numéricos
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    
    // Formato para celulares peruanos (9 dígitos)
    if (strlen($telefono) == 9) {
        return substr($telefono, 0, 3) . ' ' . substr($telefono, 3, 3) . ' ' . substr($telefono, 6, 3);
    }
    
    // Formato para teléfonos fijos con código de área
    if (strlen($telefono) == 7) {
        return substr($telefono, 0, 3) . '-' . substr($telefono, 3, 4);
    }
    
    return $telefono;
}

/**
 * Validar DNI peruano
 */
function validarDNI($dni)
{
    // Eliminar espacios
    $dni = trim($dni);
    
    // Verificar que tenga 8 dígitos
    if (!preg_match('/^[0-9]{8}$/', $dni)) {
        return false;
    }
    
    return true;
}

/**
 * Formatear DNI
 */
function formatearDNI($dni)
{
    $dni = preg_replace('/[^0-9]/', '', $dni);
    
    if (strlen($dni) == 8) {
        return substr($dni, 0, 4) . ' ' . substr($dni, 4, 4);
    }
    
    return $dni;
}

/**
 * Generar código de reserva único
 */
function generarCodigoReserva()
{
    $fecha = date('Ymd');
    $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
    return "RES-{$fecha}-{$random}";
}

/**
 * Calcular total de noches y precio
 */
function calcularReserva($fechaEntrada, $fechaSalida, $precioNoche)
{
    $noches = daysBetween($fechaEntrada, $fechaSalida);
    $subtotal = $noches * $precioNoche;
    $igv = $subtotal * 0.18; // 18% IGV en Perú
    $total = $subtotal + $igv;
    
    return [
        'noches' => $noches,
        'precio_noche' => $precioNoche,
        'subtotal' => $subtotal,
        'igv' => $igv,
        'total' => $total
    ];
}

/**
 * Obtener nombre del día de la semana en español
 */
function nombreDia($fecha)
{
    $dias = [
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    ];
    
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    $diaIngles = date('l', $timestamp);
    
    return $dias[$diaIngles] ?? $diaIngles;
}

/**
 * Obtener nombre del mes en español
 */
function nombreMes($mes)
{
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    return $meses[(int)$mes] ?? '';
}

/**
 * Formatear fecha en español completa
 */
function fechaCompletaEspañol($fecha)
{
    $timestamp = is_numeric($fecha) ? $fecha : strtotime($fecha);
    
    $dia = date('d', $timestamp);
    $mes = nombreMes(date('n', $timestamp));
    $año = date('Y', $timestamp);
    $diaSemana = nombreDia($fecha);
    
    return "$diaSemana, $dia de $mes de $año";
}

/**
 * Cerrar sesión completamente
 */
function logoutUser()
{
    // Limpiar todas las variables de sesión
    $_SESSION = array();
    
    // Eliminar la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // Destruir la sesión
    session_destroy();
}

/**
 * Verificar timeout de sesión (inactividad)
 */
function checkSessionTimeout($timeout = 3600)
{
    if (isset($_SESSION['LAST_ACTIVITY'])) {
        $inactive = time() - $_SESSION['LAST_ACTIVITY'];
        
        if ($inactive > $timeout) {
            // Sesión expirada por inactividad
            logoutUser();
            session_start();
            $_SESSION['error'] = 'Tu sesión ha expirado por inactividad';
            header('Location: /hotel-reservas/public/login');
            exit;
        }
    }
    
    // Actualizar tiempo de última actividad
    $_SESSION['LAST_ACTIVITY'] = time();
}

/**
 * Middleware de autenticación
 */
function requireAuth()
{
    if (!isAuthenticated()) {
        $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta página';
        header('Location: /hotel-reservas/public/login');
        exit;
    }
    
    // Verificar timeout de sesión
    checkSessionTimeout();
}

/**
 * Middleware de rol
 */
function requireRole($role)
{
    requireAuth();
    
    if (!hasRole($role)) {
        $_SESSION['error'] = 'No tienes permisos para acceder a esta página';
        header('Location: /hotel-reservas/public/dashboard');
        exit;
    }
}