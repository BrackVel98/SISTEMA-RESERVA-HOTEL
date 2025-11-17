# 📋 REQUISITOS DE SOFTWARE
## Sistema de Gestión de Reservas de Hotel

### Versión 1.0 | Noviembre 2025

---

## 1. REQUISITOS FUNCIONALES

### RF01: Gestión de Usuarios
- **RF01.1** - El sistema debe permitir el inicio de sesión con email y contraseña
- **RF01.2** - El sistema debe validar las credenciales contra la base de datos
- **RF01.3** - El sistema debe mantener la sesión del usuario activa
- **RF01.4** - El sistema debe permitir cerrar sesión
- **RF01.5** - El sistema debe permitir recuperar contraseña mediante email

### RF02: Gestión de Clientes
- **RF02.1** - El sistema debe permitir registrar nuevos clientes con datos obligatorios
- **RF02.2** - El sistema debe validar que el email sea único
- **RF02.3** - El sistema debe validar que el documento sea único
- **RF02.4** - El sistema debe permitir buscar clientes por nombre, documento o email
- **RF02.5** - El sistema debe permitir editar información de clientes
- **RF02.6** - El sistema debe permitir eliminar clientes sin reservas activas
- **RF02.7** - El sistema debe mostrar el historial de reservas por cliente

### RF03: Gestión de Habitaciones
- **RF03.1** - El sistema debe clasificar habitaciones por tipo (Simple, Doble, Triple, Suite)
- **RF03.2** - El sistema debe registrar habitaciones con número único
- **RF03.3** - El sistema debe permitir establecer precio por noche
- **RF03.4** - El sistema debe gestionar estados: Disponible, Ocupada, Mantenimiento
- **RF03.5** - El sistema debe permitir subir imágenes de habitaciones
- **RF03.6** - El sistema debe permitir editar información de habitaciones
- **RF03.7** - El sistema debe impedir eliminar habitaciones con reservas futuras

### RF04: Gestión de Reservas
- **RF04.1** - El sistema debe generar código único para cada reserva (ej: RES019778)
- **RF04.2** - El sistema debe validar disponibilidad antes de crear reserva
- **RF04.3** - El sistema debe calcular precio total (días × precio_noche)
- **RF04.4** - El sistema debe gestionar estados:
  - Pendiente (sin pagos o pago < 50%)
  - Confirmada (pago >= 50%)
  - En Curso (check-in realizado)
  - Completada (check-out realizado)
  - Cancelada (anulada por usuario)
- **RF04.5** - El sistema debe permitir editar fechas si no hay conflictos
- **RF04.6** - El sistema debe permitir cancelar reservas
- **RF04.7** - El sistema debe mostrar reservas por cliente
- **RF04.8** - El sistema debe mostrar reservas por habitación
- **RF04.9** - El sistema debe mostrar calendario de reservas

### RF05: Gestión de Pagos
- **RF05.1** - El sistema debe registrar pagos asociados a reservas
- **RF05.2** - El sistema debe validar que el monto no exceda el saldo pendiente
- **RF05.3** - El sistema debe soportar métodos: Efectivo, Tarjeta, Transferencia, Otro
- **RF05.4** - El sistema debe actualizar automáticamente el saldo pendiente
- **RF05.5** - El sistema debe cambiar estado de reserva a "Confirmada" al pagar >= 50%
- **RF05.6** - El sistema debe permitir reembolsar pagos completados
- **RF05.7** - El sistema debe generar recibos imprimibles en PDF
- **RF05.8** - El sistema debe mostrar historial de pagos por reserva

### RF06: Búsqueda de Habitaciones
- **RF06.1** - El sistema debe permitir buscar por rango de fechas
- **RF06.2** - El sistema debe filtrar por tipo de habitación
- **RF06.3** - El sistema debe filtrar por capacidad (número de personas)
- **RF06.4** - El sistema debe filtrar por precio máximo
- **RF06.5** - El sistema debe mostrar solo habitaciones disponibles en las fechas
- **RF06.6** - El sistema debe calcular precio total según duración
- **RF06.7** - El sistema debe permitir reservar desde resultados de búsqueda

### RF07: Reportes y Estadísticas
- **RF07.1** - El sistema debe mostrar dashboard con:
  - Total de reservas
  - Habitaciones ocupadas
  - Ingresos del mes
  - Reservas de hoy (check-in/check-out)
- **RF07.2** - El sistema debe generar reporte de ocupación
- **RF07.3** - El sistema debe generar reporte de ingresos
- **RF07.4** - El sistema debe generar reporte de reservas por período
- **RF07.5** - El sistema debe permitir exportar reportes (PDF/Excel)

---

## 2. REQUISITOS NO FUNCIONALES

### RNF01: Rendimiento
- **RNF01.1** - El tiempo de respuesta debe ser menor a 2 segundos
- **RNF01.2** - El sistema debe soportar 50 usuarios concurrentes
- **RNF01.3** - Las consultas a base de datos deben optimizarse con índices

### RNF02: Seguridad
- **RNF02.1** - Las contraseñas deben almacenarse hasheadas (bcrypt)
- **RNF02.2** - El sistema debe prevenir inyección SQL usando PDO prepared statements
- **RNF02.3** - El sistema debe validar todos los datos de entrada
- **RNF02.4** - El sistema debe proteger contra ataques CSRF
- **RNF02.5** - El sistema debe usar HTTPS en producción
- **RNF02.6** - Las sesiones deben expirar después de 30 minutos de inactividad

### RNF03: Usabilidad
- **RNF03.1** - La interfaz debe ser responsiva (mobile-first)
- **RNF03.2** - La interfaz debe usar iconos intuitivos (FontAwesome)
- **RNF03.3** - El sistema debe mostrar mensajes de éxito/error claros
- **RNF03.4** - Los formularios deben validar en tiempo real
- **RNF03.5** - El sistema debe ser compatible con navegadores modernos

### RNF04: Mantenibilidad
- **RNF04.1** - El código debe seguir patrón MVC
- **RNF04.2** - El código debe estar documentado con comentarios
- **RNF04.3** - Los logs de errores deben guardarse en archivos
- **RNF04.4** - La base de datos debe tener respaldos automáticos

### RNF05: Disponibilidad
- **RNF05.1** - El sistema debe tener 99% de uptime
- **RNF05.2** - El sistema debe tener plan de recuperación ante desastres
- **RNF05.3** - Los backups deben realizarse diariamente

### RNF06: Escalabilidad
- **RNF06.1** - La base de datos debe soportar 10,000 registros sin degradación
- **RNF06.2** - El sistema debe permitir agregar nuevos módulos sin afectar existentes

### RNF07: Portabilidad
- **RNF07.1** - El sistema debe funcionar en Windows/Linux
- **RNF07.2** - El sistema debe funcionar con Apache/Nginx
- **RNF07.3** - El sistema debe funcionar con MySQL/MariaDB

---

## 3. REQUISITOS DE SISTEMA

### Hardware Mínimo (Servidor)
- **Procesador:** Intel Core i3 o equivalente
- **RAM:** 4 GB
- **Disco:** 20 GB SSD
- **Red:** 100 Mbps

### Hardware Recomendado (Servidor)
- **Procesador:** Intel Core i5 o superior
- **RAM:** 8 GB o más
- **Disco:** 50 GB SSD
- **Red:** 1 Gbps

### Software (Servidor)
- **Sistema Operativo:** 
  - Windows Server 2016+ / Windows 10+
  - Linux (Ubuntu 20.04+, CentOS 8+)
- **Servidor Web:** Apache 2.4.x o Nginx 1.18+
- **PHP:** 8.0 o superior con extensiones:
  - PDO
  - PDO_MySQL
  - mbstring
  - json
  - openssl
- **Base de Datos:** MySQL 8.0+ o MariaDB 10.5+

### Software (Cliente)
- **Navegador Web:**
  - Google Chrome 90+
  - Mozilla Firefox 88+
  - Microsoft Edge 90+
  - Safari 14+
- **Resolución:** Mínimo 1280x720 px
- **JavaScript:** Habilitado
- **Cookies:** Habilitadas

---

## 4. RESTRICCIONES Y LIMITACIONES

### Restricciones Técnicas
- El sistema requiere conexión a Internet
- Las imágenes de habitaciones tienen límite de 5 MB
- Los reportes en PDF se generan desde el navegador
- No hay notificaciones push (solo en dashboard)

### Restricciones de Negocio
- Una habitación solo puede tener una reserva activa a la vez
- No se pueden eliminar clientes con reservas
- No se pueden eliminar habitaciones con reservas futuras
- Los pagos reembolsados no se pueden revertir
- Las fechas de reserva no pueden ser en el pasado

### Limitaciones Conocidas
- No hay integración con pasarelas de pago en línea
- No hay envío automático de emails
- No hay app móvil nativa
- No hay sistema de notificaciones por SMS

---

## 5. CASOS DE USO PRIORITARIOS

### Alta Prioridad
1. UC14: Crear Reserva
2. UC21: Registrar Pago
3. UC15: Buscar Habitación Disponible
4. UC01: Iniciar Sesión

### Media Prioridad
5. UC04: Registrar Cliente
6. UC18: Ver Detalle Reserva
7. UC26: Ver Dashboard
8. UC09: Registrar Habitación

### Baja Prioridad
9. UC27-30: Generar Reportes
10. UC23: Reembolsar Pago
11. UC07: Eliminar Cliente
12. UC11: Eliminar Habitación

---

**Aprobado por:** [Nombre del Responsable]  
**Fecha:** Noviembre 2025  
**Versión:** 1.0

---