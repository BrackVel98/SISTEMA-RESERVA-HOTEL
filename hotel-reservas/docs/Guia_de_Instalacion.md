# 🚀 GUÍA DE INSTALACIÓN Y CONFIGURACIÓN
## Sistema de Gestión de Reservas de Hotel

### Versión 1.0 | Noviembre 2025

---

## 📋 TABLA DE CONTENIDOS

1. [Requisitos Previos](#requisitos-previos)
2. [Instalación del Servidor](#instalación-del-servidor)
3. [Instalación de la Base de Datos](#instalación-de-la-base-de-datos)
4. [Instalación de la Aplicación](#instalación-de-la-aplicación)
5. [Configuración](#configuración)
6. [Verificación](#verificación)
7. [Solución de Problemas](#solución-de-problemas)

---

## 1️⃣ REQUISITOS PREVIOS

### Software Necesario

**Windows:**
- ✅ Apache 2.4+ (Standalone)
- ✅ PHP 8.0+ (Standalone)
- ✅ MySQL 8.0+ (Standalone)
- ✅ Git (opcional, para clonar repositorio)
- ✅ Editor de texto (VSCode recomendado)

**Linux:**
- ✅ Apache 2.4+
- ✅ PHP 8.0+
- ✅ MySQL 8.0+
- ✅ Git

### Verificar Instalaciones

```bash
# Verificar PHP
php -v
# Debe mostrar: PHP 8.x.x

# Verificar MySQL
mysql --version
# Debe mostrar: mysql Ver 8.x.x

# Verificar Apache (Windows)
httpd -v
# Debe mostrar: Apache/2.4.x
```

---

## 2️⃣ INSTALACIÓN DEL SERVIDOR

### Opción A: Windows con Apache + PHP Standalone

#### Paso 1: Instalar Apache

**Descargar Apache:**
1. Ir a: https://www.apachelounge.com/download/
2. Descargar: `httpd-2.4.xx-win64-VS17.zip`
3. Extraer en: `D:\Software\Apache24\`

**Instalar como servicio:**
```bash
# Abrir CMD como Administrador
cd D:\Software\Apache24\bin

# Instalar servicio
httpd.exe -k install

# Iniciar Apache
httpd.exe -k start

# Verificar
http://localhost
# Debe mostrar "It works!"
```

#### Paso 2: Instalar PHP

**Descargar PHP:**
1. Ir a: https://windows.php.net/download/
2. Descargar: `PHP 8.x Thread Safe (x64)`
3. Extraer en: `D:\Software\php\`

**Configurar PHP:**
```bash
# Copiar php.ini
cd D:\Software\php
copy php.ini-development php.ini

# Editar php.ini con Notepad++
notepad php.ini
```

**Modificar en php.ini:**
```ini
; Habilitar extensiones (remover punto y coma)
extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=mysqli
extension=pdo_mysql
extension=openssl

; Configurar ruta de extensiones
extension_dir = "D:/Software/php/ext"

; Configurar sesiones
session.save_path = "D:/Software/php/tmp"

; Configurar uploads
upload_tmp_dir = "D:/Software/php/tmp"
upload_max_filesize = 10M
post_max_size = 10M

; Zona horaria
date.timezone = America/Lima

; Errores (desarrollo)
display_errors = On
error_reporting = E_ALL
```

**Crear carpeta tmp:**
```bash
mkdir D:\Software\php\tmp
```

#### Paso 3: Integrar PHP con Apache

**Editar httpd.conf:**
```bash
cd D:\Software\Apache24\conf
notepad httpd.conf
```

**Agregar al final del archivo:**
```apache
# PHP 8 Module
LoadModule php_module "D:/Software/php/php8apache2_4.dll"
AddHandler application/x-httpd-php .php
PHPIniDir "D:/Software/php"

# Index con PHP
<IfModule dir_module>
    DirectoryIndex index.php index.html
</IfModule>
```

**Verificar DocumentRoot:**
```apache
# Buscar y modificar:
DocumentRoot "D:/Software/Apache24/htdocs"
<Directory "D:/Software/Apache24/htdocs">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

#### Paso 4: Habilitar mod_rewrite

**En httpd.conf buscar y descomentar:**
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

#### Paso 5: Reiniciar Apache

```bash
# CMD como Administrador
cd D:\Software\Apache24\bin
httpd.exe -k restart
```

#### Paso 6: Verificar PHP

**Crear archivo test:**
```php
<!-- filepath: D:\Software\Apache24\htdocs\info.php -->
<?php
phpinfo();
?>
```

**Acceder a:** http://localhost/info.php

**Verificar que aparezca:**
- ✅ PHP Version 8.x.x
- ✅ Apache 2.4 Handler
- ✅ PDO drivers: mysql
- ✅ mysqli: enabled

**Eliminar archivo después:**
```bash
del D:\Software\Apache24\htdocs\info.php
```

#### Paso 7: Instalar MySQL

**Descargar MySQL:**
1. Ir a: https://dev.mysql.com/downloads/installer/
2. Descargar: `mysql-installer-community-8.x.x.msi`
3. Ejecutar instalador

**Configurar MySQL:**
- Tipo: Developer Default
- Contraseña root: (dejar vacío para desarrollo o establecer una)
- Puerto: 3306
- Iniciar MySQL como servicio: Sí

**Verificar instalación:**
```bash
# Abrir CMD
mysql -u root -p
# Si dejó contraseña vacía, presionar Enter

# Debe ver:
mysql>

# Salir:
EXIT;
```

### Opción B: Linux (Ubuntu/Debian)

```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Apache
sudo apt install apache2 -y

# Instalar MySQL
sudo apt install mysql-server -y

# Instalar PHP y extensiones
sudo apt install php8.1 php8.1-mysql php8.1-mbstring php8.1-json php8.1-curl php8.1-gd -y

# Habilitar mod_rewrite
sudo a2enmod rewrite

# Reiniciar Apache
sudo systemctl restart apache2

# Verificar servicios
sudo systemctl status apache2
sudo systemctl status mysql

# Verificar PHP
php -v
```

---

## 3️⃣ INSTALACIÓN DE LA BASE DE DATOS

### Paso 1: Acceder a MySQL

**Windows:**
```bash
# Abrir CMD
cd "C:\Program Files\MySQL\MySQL Server 8.0\bin"
mysql -u root -p
# Ingresar contraseña (o Enter si está vacía)
```

**Linux:**
```bash
sudo mysql -u root -p
```

### Paso 2: Crear Base de Datos

```sql
-- Crear base de datos
CREATE DATABASE hotel_reservas 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Verificar
SHOW DATABASES;

-- Debe aparecer:
-- +--------------------+
-- | Database           |
-- +--------------------+
-- | hotel_reservas     |
-- | information_schema |
-- | mysql              |
-- | performance_schema |
-- | sys                |
-- +--------------------+

-- Salir
EXIT;
```

### Paso 3: Importar Schema

**Método 1: Desde phpMyAdmin (si lo tiene instalado)**
1. Abrir: http://localhost/phpmyadmin
2. Click en "hotel_reservas"
3. Pestaña "Importar"
4. Click en "Elegir archivo"
5. Seleccionar: `hotel-reservas/database/schema.sql`
6. Click en "Continuar"

**Método 2: Desde Línea de Comandos (Recomendado)**

**Windows:**
```bash
cd "C:\Program Files\MySQL\MySQL Server 8.0\bin"
mysql -u root -p hotel_reservas < "D:\Software\Apache24\htdocs\hotel-reservas\database\schema.sql"
```

**Linux:**
```bash
mysql -u root -p hotel_reservas < /var/www/html/hotel-reservas/database/schema.sql
```

### Paso 4: Importar Datos de Prueba (Opcional)

```bash
# Windows
cd "C:\Program Files\MySQL\MySQL Server 8.0\bin"
mysql -u root -p hotel_reservas < "D:\Software\Apache24\htdocs\hotel-reservas\database\seeds.sql"

# Linux
mysql -u root -p hotel_reservas < /var/www/html/hotel-reservas/database/seeds.sql
```

### Paso 5: Verificar Tablas

```sql
-- Acceder a MySQL
mysql -u root -p

-- Usar base de datos
USE hotel_reservas;

-- Ver tablas
SHOW TABLES;

-- Debe mostrar:
-- +---------------------------+
-- | Tables_in_hotel_reservas  |
-- +---------------------------+
-- | clientes                  |
-- | habitaciones              |
-- | pagos                     |
-- | reservas                  |
-- | tipos_habitacion          |
-- | usuarios                  |
-- +---------------------------+

-- Ver estructura de una tabla
DESCRIBE usuarios;

-- Contar registros (si importó seeds.sql)
SELECT COUNT(*) FROM usuarios;
-- Debe mostrar: 1 o más

-- Verificar usuario de prueba
SELECT email FROM usuarios;
-- Debe mostrar: admin@hotel.com

-- Salir
EXIT;
```

---

## 4️⃣ INSTALACIÓN DE LA APLICACIÓN

### Opción A: Clonar desde Git (Recomendado)

```bash
# Ir a la carpeta htdocs
cd D:\Software\Apache24\htdocs

# Clonar repositorio (si está en GitHub)
git clone https://github.com/tu-usuario/hotel-reservas.git

# Entrar a la carpeta
cd hotel-reservas

# Verificar estructura
dir
```

### Opción B: Copiar Archivos Manualmente

1. Copiar la carpeta del proyecto completa
2. Pegar en: `D:\Software\Apache24\htdocs\`
3. Asegurar que la carpeta se llame: `hotel-reservas`

### Estructura de Carpetas Final

```
D:\Software\Apache24\htdocs\hotel-reservas\
│
├── config/
│   ├── app.php
│   └── database.php
│
├── controllers/
│   ├── AuthController.php
│   ├── ClienteController.php
│   ├── DashboardController.php
│   ├── HabitacionController.php
│   ├── PagoController.php
│   └── ReservaController.php
│
├── core/
│   ├── Controller.php
│   ├── Database.php
│   ├── Model.php
│   ├── Router.php
│   └── Session.php
│
├── database/
│   ├── schema.sql
│   └── seeds.sql
│
├── docs/
│   ├── Manual_de_Usuario.md
│   ├── Requisitos_de_Software.md
│   ├── Guia_de_Instalacion.md
│   └── diagramas/
│
├── helpers/
│   ├── constants.php
│   └── functions.php
│
├── logs/
│   └── (archivos de log)
│
├── models/
│   ├── Cliente.php
│   ├── Habitacion.php
│   ├── Pago.php
│   ├── Reserva.php
│   └── Usuario.php
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   │   ├── admin.css
│   │   └── style.css
│   ├── js/
│   │   ├── main.js
│   │   └── validaciones.js
│   └── images/
│       └── habitaciones/
│
├── views/
│   ├── layouts/
│   ├── auth/
│   ├── dashboard/
│   ├── clientes/
│   ├── habitaciones/
│   ├── reservas/
│   └── pagos/
│
├── .htaccess
└── README.md
```

---

## 5️⃣ CONFIGURACIÓN

### Paso 1: Configurar Base de Datos

**Editar archivo:** `config/database.php`

```php
<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\config\database.php

// Configuración de la base de datos
define('DB_HOST', 'localhost');        // Servidor MySQL
define('DB_NAME', 'hotel_reservas');   // Nombre de la BD
define('DB_USER', 'root');             // Usuario MySQL
define('DB_PASS', '');                 // Contraseña (vacío si no tiene)
define('DB_CHARSET', 'utf8mb4');       // Charset

// Para producción, cambiar:
// define('DB_PASS', 'tu_contraseña_segura');
```

**⚠️ IMPORTANTE:** Si estableció contraseña al instalar MySQL, cámbiela aquí.

### Paso 2: Configurar Aplicación

**Editar archivo:** `config/app.php`

```php
<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\config\app.php

// Nombre de la aplicación
define('APP_NAME', 'Hotel Reservas');

// URL base de la aplicación
define('APP_URL', 'http://localhost');

// Ruta base
define('BASE_URL', '/hotel-reservas/public');

// Zona horaria
date_default_timezone_set('America/Lima');

// Modo debug (desactivar en producción)
define('APP_DEBUG', true);

// Logs
define('LOG_ERRORS', true);
define('LOG_PATH', __DIR__ . '/../logs/');
```

### Paso 3: Verificar Permisos de Carpetas (Windows)

**Carpeta logs:**
1. Click derecho en `hotel-reservas\logs`
2. Propiedades → Seguridad
3. Editar → Agregar → "Todos"
4. Permisos: Control total → Aceptar

**Carpeta images:**
1. Click derecho en `hotel-reservas\public\images`
2. Propiedades → Seguridad
3. Editar → Agregar → "Todos"
4. Permisos: Control total → Aceptar

### Paso 4: Verificar .htaccess Principal

**Archivo:** `.htaccess` (raíz del proyecto)

```apache
# filepath: d:\Software\Apache24\htdocs\hotel-reservas\.htaccess

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/ [L]
    RewriteRule (.*) public/$1 [L]
</IfModule>
```

### Paso 5: Verificar .htaccess de Public

**Archivo:** `public/.htaccess`

```apache
# filepath: d:\Software\Apache24\htdocs\hotel-reservas\public\.htaccess

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /hotel-reservas/public/
    
    # Redirigir todo a index.php excepto archivos existentes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [L,QSA]
</IfModule>

# Seguridad adicional
<FilesMatch "\.(htaccess|htpasswd|ini|log|sh|sql)$">
    Require all denied
</FilesMatch>
```

---

## 6️⃣ VERIFICACIÓN

### Paso 1: Verificar Conexión a Base de Datos

**Crear archivo temporal:** `public/test_db.php`

```php
<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\public\test_db.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Database.php';

echo "<h2>🔍 Test de Conexión a Base de Datos</h2>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";

try {
    $pdo = Database::getConnection();
    echo "<p style='color: green;'>✅ <strong>Conexión exitosa a MySQL!</strong></p>";
    
    // Verificar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tablas encontradas (" . count($tables) . "):</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        // Contar registros
        $count_stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $count_stmt->fetchColumn();
        echo "<li><strong>$table</strong> → $count registros</li>";
    }
    echo "</ul>";
    
    // Verificar usuarios
    $stmt = $pdo->query("SELECT email, rol FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($usuarios) > 0) {
        echo "<h3>Usuarios del sistema:</h3>";
        echo "<ul>";
        foreach ($usuarios as $usuario) {
            echo "<li>{$usuario['email']} (Rol: {$usuario['rol']})</li>";
        }
        echo "</ul>";
    }
    
    echo "<hr>";
    echo "<p><a href='/hotel-reservas/public/login'>→ Ir al Login</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ <strong>Error de conexión:</strong></p>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<h3>Verificar:</h3>";
    echo "<ul>";
    echo "<li>MySQL está corriendo</li>";
    echo "<li>Credenciales en config/database.php son correctas</li>";
    echo "<li>Base de datos 'hotel_reservas' existe</li>";
    echo "</ul>";
}
?>
```

**Acceder a:** http://localhost/hotel-reservas/public/test_db.php

**Debe mostrar:**
```
✅ Conexión exitosa a MySQL!

Tablas encontradas (6):
• clientes → X registros
• habitaciones → X registros
• pagos → X registros
• reservas → X registros
• tipos_habitacion → X registros
• usuarios → 1 registros

Usuarios del sistema:
• admin@hotel.com (Rol: administrador)
```

**Eliminar después de verificar:**
```bash
del D:\Software\Apache24\htdocs\hotel-reservas\public\test_db.php
```

### Paso 2: Verificar Rutas

**Acceder a las siguientes URLs y verificar:**

```
✅ http://localhost/hotel-reservas/
   → Redirige automáticamente a /hotel-reservas/public/

✅ http://localhost/hotel-reservas/public/
   → Redirige a /login si no está autenticado

✅ http://localhost/hotel-reservas/public/login
   → Muestra formulario de login

✅ http://localhost/hotel-reservas/public/css/style.css
   → Descarga archivo CSS (ver en navegador)

✅ http://localhost/hotel-reservas/public/js/main.js
   → Muestra código JavaScript
```

### Paso 3: Probar Login

**Credenciales por defecto (si importó seeds.sql):**
```
Email: admin@hotel.com
Contraseña: admin123
```

**Pasos:**
1. Ir a: http://localhost/hotel-reservas/public/login
2. Ingresar credenciales
3. Click en "Iniciar Sesión"
4. ✅ Debe redirigir a: http://localhost/hotel-reservas/public/dashboard
5. ✅ Debe mostrar el Dashboard con estadísticas

### Paso 4: Verificar Servicios de Windows

```bash
# Abrir CMD como Administrador

# Verificar Apache
sc query Apache2.4
# Estado: RUNNING

# Verificar MySQL
sc query MySQL80
# Estado: RUNNING

# Si no están corriendo:
net start Apache2.4
net start MySQL80
```

---

## 7️⃣ SOLUCIÓN DE PROBLEMAS

### ❌ Error: "Apache no inicia"

**Solución 1: Puerto 80 ocupado**
```bash
# Verificar qué usa el puerto 80
netstat -ano | findstr :80

# Si algo lo está usando, cambiar puerto de Apache
# Editar: D:\Software\Apache24\conf\httpd.conf
Listen 8080

# Acceder a: http://localhost:8080/
```

**Solución 2: Verificar httpd.conf**
```bash
cd D:\Software\Apache24\bin
httpd.exe -t
# Debe mostrar: Syntax OK
```

### ❌ Error: "No se puede conectar a la base de datos"

**Solución:**
```bash
# 1. Verificar que MySQL esté corriendo
sc query MySQL80

# 2. Iniciar MySQL si está detenido
net start MySQL80

# 3. Probar conexión manual
mysql -u root -p
USE hotel_reservas;
SHOW TABLES;
EXIT;

# 4. Verificar credenciales en config/database.php
```

### ❌ Error 404: "Página no encontrada"

**Solución:**
```apache
# 1. Verificar mod_rewrite en httpd.conf
# Debe estar sin comentario (#):
LoadModule rewrite_module modules/mod_rewrite.so

# 2. Verificar AllowOverride All en httpd.conf:
<Directory "D:/Software/Apache24/htdocs">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

# 3. Reiniciar Apache
cd D:\Software\Apache24\bin
httpd.exe -k restart
```

### ❌ Error: "Call to undefined function mysqli_connect()"

**Solución:**
```ini
# Editar php.ini
cd D:\Software\php
notepad php.ini

# Descomentar (remover ;):
extension=mysqli
extension=pdo_mysql

# Reiniciar Apache
cd D:\Software\Apache24\bin
httpd.exe -k restart
```

### ❌ Error: "Class 'Database' not found"

**Solución:**
```php
// Verificar que el archivo existe:
// D:\Software\Apache24\htdocs\hotel-reservas\core\Database.php

// Verificar ruta en archivos que lo requieren:
require_once __DIR__ . '/../core/Database.php';
```

### ❌ Error: "Session not started"

**Solución:**
```ini
# Editar php.ini
session.save_path = "D:/Software/php/tmp"

# Crear carpeta si no existe:
mkdir D:\Software\php\tmp

# Reiniciar Apache
```

### ❌ Error: "Access denied for user 'root'@'localhost'"

**Solución:**
```sql
-- Acceder a MySQL como root
mysql -u root

-- Cambiar contraseña (dejar vacía para desarrollo)
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
FLUSH PRIVILEGES;
EXIT;

-- Actualizar config/database.php:
define('DB_PASS', '');
```

### ❌ Las imágenes no se suben

**Solución:**
```bash
# Windows: Dar permisos a carpeta
# Click derecho en: hotel-reservas\public\images\habitaciones
# Propiedades → Seguridad → Editar → Agregar "Todos"
# Permisos: Control total

# Verificar php.ini:
upload_max_filesize = 10M
post_max_size = 10M
```

### ❌ Error: "Headers already sent"

**Solución:**
```php
// Verificar que NO haya espacios antes de <?php
// Verificar codificación UTF-8 sin BOM

// En Notepad++:
// Codificación → Convertir a UTF-8 sin BOM
```

### ❌ PHP no se ejecuta (descarga archivos .php)

**Solución:**
```apache
# Editar httpd.conf
# Verificar que existan estas líneas:
LoadModule php_module "D:/Software/php/php8apache2_4.dll"
AddHandler application/x-httpd-php .php
PHPIniDir "D:/Software/php"

# Reiniciar Apache
cd D:\Software\Apache24\bin
httpd.exe -k restart
```

---

## 📊 CHECKLIST DE INSTALACIÓN

```
□ Apache 2.4 instalado
□ PHP 8.0+ instalado
□ MySQL 8.0+ instalado
□ Apache corriendo (http://localhost funciona)
□ MySQL corriendo (mysql -u root -p funciona)
□ PHP integrado con Apache (phpinfo() funciona)
□ mod_rewrite habilitado
□ Base de datos 'hotel_reservas' creada
□ Schema.sql importado correctamente
□ Seeds.sql importado (opcional)
□ Archivos del proyecto en hdocs/hotel-reservas
□ config/database.php configurado
□ config/app.php configurado
□ .htaccess configurados
□ Permisos de carpetas logs/ e images/ configurados
□ Test de conexión exitoso
□ Login funcionando
□ Dashboard accesible
```

---

## 🎓 SIGUIENTE PASO: CAPACITACIÓN

Una vez instalado correctamente, consultar:
- 📖 [Manual de Usuario](Manual_de_Usuario.md)
- 📋 [Requisitos de Software](Requisitos_de_Software.md)
- 🏗️ [Diagramas UML](diagramas/)

---

## 📞 SOPORTE

**Problemas técnicos:**
- 📧 Email: soporte@hotel.com
- 📱 Teléfono: +51 999 888 777
- 💬 GitHub Issues: [Reportar problema](https://github.com/tu-usuario/hotel-reservas/issues)

---

## 📝 NOTAS ADICIONALES

### Configuración de Desarrollo vs Producción

**Desarrollo (actual):**
```php
define('APP_DEBUG', true);
define('DB_PASS', '');
```

**Producción (cambiar):**
```php
define('APP_DEBUG', false);
define('DB_PASS', 'contraseña_segura_123');
```

### Comandos Útiles

```bash
# Iniciar Apache
net start Apache2.4

# Detener Apache
net stop Apache2.4

# Reiniciar Apache
cd D:\Software\Apache24\bin
httpd.exe -k restart

# Iniciar MySQL
net start MySQL80

# Detener MySQL
net stop MySQL80

# Ver logs de Apache
type D:\Software\Apache24\logs\error.log

# Ver logs de PHP
type D:\Software\php\logs\php_error.log
```

---

**Instalado por:** [Nombre del Técnico]  
**Fecha de instalación:** ____ / ____ / ____  
**Versión:** 1.0  
**Configuración:** Apache + PHP + MySQL Standalone

---