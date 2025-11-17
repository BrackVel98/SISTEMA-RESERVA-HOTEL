# 🏨 Sistema de Gestión de Reservas de Hotel

Sistema web completo para la gestión integral de reservas, habitaciones, clientes y pagos de un hotel.

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://www.php.net/)
[![MySQL Version](https://img.shields.io/badge/MySQL-8.0%2B-orange)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## 📋 Características Principales

### ✨ Funcionalidades

- ✅ **Gestión de Clientes:** Registro, edición, búsqueda e historial
- ✅ **Gestión de Habitaciones:** Por tipos (Simple, Doble, Triple, Suite)
- ✅ **Gestión de Reservas:** Creación, edición, cancelación y estados
- ✅ **Gestión de Pagos:** Múltiples métodos, reembolsos y recibos
- ✅ **Búsqueda Avanzada:** Por fechas, tipo, capacidad y precio
- ✅ **Dashboard:** Estadísticas en tiempo real
- ✅ **Reportes:** Ocupación, ingresos y reservas

### 🎯 Tecnologías Utilizadas

**Backend:**
- PHP 8.0+ (POO)
- MySQL 8.0+
- PDO para base de datos
- Patrón MVC

**Frontend:**
- HTML5
- CSS3
- Bootstrap 5
- JavaScript (Vanilla)
- Font Awesome 6

**Servidor:**
- Apache 2.4+
- mod_rewrite

---

## 🚀 Instalación Rápida

### Requisitos Previos

- Apache 2.4+
- PHP 8.0+
- MySQL 8.0+
- Git (opcional)

### Pasos de Instalación

```bash
# 1. Clonar repositorio
git clone https://github.com/tu-usuario/hotel-reservas.git
cd hotel-reservas

# 2. Crear base de datos
mysql -u root -p
CREATE DATABASE hotel_reservas;
EXIT;

# 3. Importar schema
mysql -u root -p hotel_reservas < database/schema.sql
mysql -u root -p hotel_reservas < database/seeds.sql

# 4. Configurar conexión
cp config/database.example.php config/database.php
# Editar config/database.php con tus credenciales

# 5. Configurar permisos (Linux)
sudo chmod -R 755 public/
sudo chmod -R 775 logs/
sudo chmod -R 775 public/images/

# 6. Acceder al sistema
http://localhost/hotel-reservas/public/
```

**Credenciales por defecto:**
```
Email: admin@hotel.com
Contraseña: admin123
```

---

## 📁 Estructura del Proyecto

```
hotel-reservas/
│
├── config/              # Configuración
│   ├── app.php         # Config de aplicación
│   └── database.php    # Config de BD
│
├── controllers/         # Controladores MVC
│   ├── AuthController.php
│   ├── ClienteController.php
│   ├── HabitacionController.php
│   ├── ReservaController.php
│   └── PagoController.php
│
├── core/               # Núcleo del framework
│   ├── Controller.php  # Controlador base
│   ├── Model.php       # Modelo base
│   ├── Database.php    # Conexión PDO
│   ├── Router.php      # Enrutador
│   └── Session.php     # Manejo de sesiones
│
├── models/             # Modelos de datos
│   ├── Usuario.php
│   ├── Cliente.php
│   ├── Habitacion.php
│   ├── Reserva.php
│   └── Pago.php
│
├── views/              # Vistas (templates)
│   ├── layouts/        # Plantillas base
│   ├── auth/           # Login/registro
│   ├── dashboard/      # Panel principal
│   ├── clientes/       # CRUD clientes
│   ├── habitaciones/   # CRUD habitaciones
│   ├── reservas/       # CRUD reservas
│   └── pagos/          # CRUD pagos
│
├── public/             # Archivos públicos
│   ├── index.php       # Punto de entrada
│   ├── .htaccess       # Reescritura URLs
│   ├── css/            # Estilos
│   ├── js/             # Scripts
│   └── images/         # Imágenes
│
├── database/           # Base de datos
│   ├── schema.sql      # Estructura de tablas
│   └── seeds.sql       # Datos de prueba
│
├── docs/               # Documentación
│   ├── Manual_de_Usuario.md
│   ├── Requisitos_de_Software.md
│   ├── Guia_de_Instalacion.md
│   └── diagramas/      # UML
│
├── helpers/            # Funciones auxiliares
│   ├── constants.php   # Constantes
│   └── functions.php   # Utilidades
│
├── logs/               # Logs de errores
│
└── README.md           # Este archivo
```

---

## 🗄️ Base de Datos

### Diagrama ER Simplificado

```
┌─────────────┐       ┌──────────────┐       ┌─────────────┐
│  USUARIOS   │       │   CLIENTES   │       │  RESERVAS   │
├─────────────┤       ├──────────────┤       ├─────────────┤
│ id (PK)     │       │ id (PK)      │───┐   │ id (PK)     │
│ nombre      │       │ nombre       │   └───│ cliente_id  │
│ email       │   ┌───│ apellido     │       │ habitacion  │
│ password    │   │   │ documento    │       │ usuario_id  │
│ rol         │   │   │ email        │       │ precio      │
└─────────────┘   │   │ telefono     │       │ estado      │
                  │   └──────────────┘       └─────────────┘
                  │                                 │
                  │   ┌──────────────┐             │
                  │   │ HABITACIONES │             │
                  │   ├──────────────┤             │
                  └───│ id (PK)      │─────────────┘
                      │ numero       │
                      │ tipo_id (FK) │       ┌──────────┐
                      │ precio       │       │  PAGOS   │
                      │ estado       │       ├──────────┤
                      └──────────────┘       │ id (PK)  │
                             │               │ reserva  │
                      ┌──────────────┐       │ monto    │
                      │TIPOS_HABITAC │       │ metodo   │
                      ├──────────────┤       │ estado   │
                      │ id (PK)      │       └──────────┘
                      │ nombre       │
                      │ capacidad    │
                      │ precio_base  │
                      └──────────────┘
```

### Tablas Principales

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Usuarios del sistema (recepcionistas, admin) |
| `clientes` | Clientes/huéspedes del hotel |
| `tipos_habitacion` | Tipos: Simple, Doble, Triple, Suite |
| `habitaciones` | Habitaciones del hotel |
| `reservas` | Reservas de habitaciones |
| `pagos` | Pagos asociados a reservas |

---

## 🔐 Seguridad

- ✅ Contraseñas hasheadas con `password_hash()` (bcrypt)
- ✅ Protección contra SQL Injection (PDO Prepared Statements)
- ✅ Validación de datos de entrada
- ✅ Sesiones seguras
- ✅ Protección de archivos sensibles (.htaccess)

---

## 📖 Documentación

Consulte la carpeta [`docs/`](docs/) para:

- 📄 [Manual de Usuario](docs/Manual_de_Usuario.md)
- 📋 [Requisitos de Software](docs/Requisitos_de_Software.md)
- 🚀 [Guía de Instalación](docs/Guia_de_Instalacion.md)
- 🏗️ [Diagramas UML](docs/diagramas/)
  - Casos de Uso
  - Diagrama de Clases
  - Diagramas de Secuencia
  - Diagrama de Despliegue

---

## 🧪 Testing

### Test Manual

```bash
# Acceder a la página de tests
http://localhost/hotel-reservas/public/test_db.php
```

### Tests Incluidos

- ✅ Conexión a base de datos
- ✅ Creación de reservas
- ✅ Registro de pagos
- ✅ Búsqueda de habitaciones
- ✅ Validación de disponibilidad

---

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crear branch (`git checkout -b feature/NuevaCaracteristica`)
3. Commit cambios (`git commit -m 'Agregar nueva característica'`)
4. Push al branch (`git push origin feature/NuevaCaracteristica`)
5. Abrir Pull Request

---

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 👥 Autores

- **Desarrollador Principal:** GRUPO 1 FISI UNAP
- **Email:** brackbaro@gmail.com
- **GitHub:** BrackVel98(https://github.com/BrackVel98)

---

## 📞 Soporte

- 📧 Email: soporte@hotel.com
- 📱 Teléfono: +51 923 342 293
- 💬 GitHub Issues: [Reportar problema](https://github.com/BrackVel98/SISTEMA-RESERVA-HOTEL/issues)

---

## 📅 Changelog

### Versión 1.0 (Noviembre 2025)
- ✨ Lanzamiento inicial
- ✅ CRUD completo de clientes, habitaciones, reservas y pagos
- ✅ Sistema de búsqueda avanzada
- ✅ Dashboard con estadísticas
- ✅ Generación de recibos
- ✅ Sistema de reembolsos

---

## 🔮 Roadmap (Futuras Mejoras)

- [ ] Sistema de notificaciones por email
- [ ] Integración con pasarelas de pago
- [ ] App móvil (Android/iOS)
- [ ] Sistema de check-in/check-out automático
- [ ] Chat en vivo para soporte
- [ ] Multi-idioma (i18n)
- [ ] Reportes avanzados con gráficos
- [ ] API REST para integraciones
- [ ] Sistema de fidelización de clientes

---

## ⭐ Si te gustó este proyecto

Dale una estrella ⭐ en GitHub y compártelo con otros desarrolladores.

---

**Desarrollado con ❤️ y ☕**


---
