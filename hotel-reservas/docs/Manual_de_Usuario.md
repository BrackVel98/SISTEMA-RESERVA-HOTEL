# 📖 MANUAL DE USUARIO
## Sistema de Gestión de Reservas de Hotel

### Versión 1.0 | Noviembre 2025

---

## 📑 ÍNDICE

1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel de Control (Dashboard)](#dashboard)
4. [Gestión de Clientes](#gestión-de-clientes)
5. [Gestión de Habitaciones](#gestión-de-habitaciones)
6. [Gestión de Reservas](#gestión-de-reservas)
7. [Gestión de Pagos](#gestión-de-pagos)
8. [Búsqueda de Habitaciones](#búsqueda)
9. [Reportes](#reportes)
10. [Preguntas Frecuentes](#faq)

---

## 1️⃣ INTRODUCCIÓN

### ¿Qué es el Sistema de Reservas?

Sistema web diseñado para la gestión integral de un hotel, permitiendo:

- ✅ Registro y gestión de clientes
- ✅ Administración de habitaciones por tipo
- ✅ Creación y seguimiento de reservas
- ✅ Control de pagos y saldo pendiente
- ✅ Búsqueda de disponibilidad
- ✅ Generación de reportes

### Requisitos del Sistema

- **Navegador:** Chrome, Firefox, Edge (versiones actuales)
- **Conexión:** Internet estable
- **Resolución:** Mínimo 1280x720 px
- **Usuario:** Credenciales proporcionadas por el administrador

---

## 2️⃣ ACCESO AL SISTEMA

### Inicio de Sesión

1. Abrir navegador web
2. Ingresar a: `http://localhost/hotel-reservas/public/login`
3. Introducir credenciales:
   - **Email:** su_email@ejemplo.com
   - **Contraseña:** su_contraseña
4. Click en **"Iniciar Sesión"**

![Login](../public/images/screenshots/login.png)

### Recuperar Contraseña

1. Click en **"¿Olvidaste tu contraseña?"**
2. Introducir email registrado
3. Revisar correo electrónico
4. Seguir instrucciones del email

### Cerrar Sesión

- Click en su nombre (esquina superior derecha)
- Seleccionar **"Cerrar Sesión"**

---

## 3️⃣ DASHBOARD (PANEL DE CONTROL)

### Vista General

Al iniciar sesión verá:

- **📊 Estadísticas Generales:**
  - Total de reservas
  - Ocupación actual
  - Ingresos del mes
  - Clientes registrados

- **📅 Calendario de Reservas:**
  - Código de reserva
  - Cliente
  - Habitación
  - Fechas de entrada/salida
  - Estado

- **🔔 Alertas:**
  - Check-ins de hoy
  - Check-outs de hoy
  - Pagos pendientes

### Navegación

**Menú Lateral (Sidebar):**

```
🏠 Dashboard
👥 Clientes
🛏️ Habitaciones
📅 Reservas
💰 Pagos
🔍 Buscar
📊 Reportes
```

---

## 4️⃣ GESTIÓN DE CLIENTES

### Listar Clientes

**Ruta:** `/clientes`

**Funciones:**
- Ver lista completa de clientes
- Buscar por nombre, documento o email
- Filtrar por estado
- Ver historial de reservas

### Registrar Nuevo Cliente

**Pasos:**

1. Click en **"+ Nuevo Cliente"**
2. Completar formulario:
   - **Nombre:** (requerido)
   - **Apellido:** (requerido)
   - **Documento:** DNI/Pasaporte (único)
   - **Tipo Documento:** DNI, Pasaporte, etc.
   - **Email:** (único, válido)
   - **Teléfono:** (requerido)
   - **Dirección:** (opcional)
3. Click en **"Guardar Cliente"**

**Validaciones:**
- ❌ Email debe ser único
- ❌ Documento debe ser único
- ❌ Teléfono: solo números

### Ver Detalles de Cliente

**Información visible:**
- Datos personales
- Historial de reservas
- Total de reservas
- Última reserva

**Acciones disponibles:**
- ✏️ Editar información
- 📅 Ver reservas
- 🗑️ Eliminar cliente (si no tiene reservas)

### Editar Cliente

1. Buscar cliente
2. Click en **"Editar"** (ícono lápiz)
3. Modificar datos
4. Click en **"Actualizar"**

### Eliminar Cliente

⚠️ **Solo se puede eliminar si:**
- No tiene reservas activas
- No tiene reservas pendientes

**Pasos:**
1. Click en **"Eliminar"** (ícono papelera)
2. Confirmar acción
3. Cliente eliminado

---

## 5️⃣ GESTIÓN DE HABITACIONES

### Tipos de Habitaciones

El sistema maneja:

| Tipo | Capacidad | Descripción |
|------|-----------|-------------|
| Simple | 1 persona | Cama individual |
| Doble | 2 personas | Cama matrimonial |
| Triple | 3 personas | 2 camas |
| Suite | 4 personas | Habitación premium |

### Listar Habitaciones

**Ruta:** `/habitaciones`

**Vista de Lista:**
- Número de habitación
- Tipo
- Precio por noche
- Estado (Disponible/Ocupada/Mantenimiento)
- Acciones

**Filtros:**
- Por tipo
- Por estado
- Por precio

### Registrar Nueva Habitación

1. Click en **"+ Nueva Habitación"**
2. Completar:
   - **Número:** Único (ej: 101, 102)
   - **Tipo:** Seleccionar de lista
   - **Precio:** Por noche
   - **Estado:** Disponible/Mantenimiento
   - **Descripción:** Opcional
   - **Imagen:** Subir foto (opcional)
3. Click en **"Guardar"**

### Estados de Habitación

```
🟢 Disponible: Lista para reservar
🔴 Ocupada: En uso actual
🟡 Mantenimiento: No disponible
```

### Editar Habitación

**Campos editables:**
- Número (si no tiene reservas)
- Precio por noche
- Estado
- Descripción
- Imagen

### Eliminar Habitación

⚠️ **Restricción:** No se puede eliminar si tiene reservas futuras

---

## 6️⃣ GESTIÓN DE RESERVAS

### Crear Nueva Reserva

**Método 1: Desde Menú Reservas**

1. Click en **"+ Nueva Reserva"**
2. Seleccionar o crear cliente
3. Elegir habitación disponible
4. Seleccionar fechas:
   - Fecha entrada
   - Fecha salida
5. Verificar precio total
6. Agregar observaciones (opcional)
7. Click en **"Crear Reserva"**

**Método 2: Desde Búsqueda**

1. Ir a **"Buscar Habitación"**
2. Seleccionar:
   - Fecha entrada
   - Fecha salida
   - Tipo habitación
   - Número de personas
3. Click en **"Buscar"**
4. Ver resultados disponibles
5. Click en **"Reservar"** en la habitación deseada
6. Completar datos del cliente
7. Confirmar reserva

### Estados de Reserva

```
🟡 Pendiente: Recién creada, sin confirmar
🔵 Confirmada: Pagado al menos 50%
🟢 En Curso: Check-in realizado
✅ Completada: Check-out realizado
🔴 Cancelada: Anulada por el usuario
```

### Ver Detalle de Reserva

**Información mostrada:**
- Código de reserva (ej: RES019778)
- Datos del cliente
- Habitación asignada
- Fechas y duración
- Precio total
- Historial de pagos
- Saldo pendiente
- Estado actual

**Acciones disponibles:**
- 💰 Registrar pago
- 📄 Imprimir voucher
- ✏️ Editar reserva
- ❌ Cancelar reserva

### Editar Reserva

**Campos editables:**
- Fechas (si no hay conflictos)
- Habitación (si está disponible)
- Observaciones

⚠️ **No se puede editar:**
- Cliente (crear nueva reserva)
- Reservas completadas o canceladas

### Cancelar Reserva

**Pasos:**
1. Ver detalle de reserva
2. Click en **"Cancelar Reserva"**
3. Escribir motivo
4. Confirmar acción

⚠️ **Efectos:**
- Estado cambia a "Cancelada"
- Habitación queda disponible
- Se conserva el historial

---

## 7️⃣ GESTIÓN DE PAGOS

### Registrar Nuevo Pago

**Desde Reserva:**
1. Ver detalle de reserva
2. Click en **"Registrar Pago"**
3. Verificar saldo pendiente
4. Completar:
   - **Monto:** Cantidad a pagar
   - **Método:** Efectivo/Tarjeta/Transferencia
   - **Fecha:** Fecha del pago
   - **Referencia:** Número de transacción (opcional)
   - **Observaciones:** Notas adicionales
5. Click en **"Guardar Pago"**

**Desde Menú Pagos:**
1. Click en **"+ Nuevo Pago"**
2. Seleccionar reserva con saldo pendiente
3. Completar datos del pago
4. Confirmar

### Métodos de Pago

```
💵 Efectivo
💳 Tarjeta (débito/crédito)
🏦 Transferencia bancaria
📱 Otro (especificar en observaciones)
```

### Estados de Pago

```
✅ Completado: Pago recibido exitosamente
🟡 Pendiente: En proceso
```

### Ver Detalle de Pago

**Información:**
- Monto pagado
- Método de pago
- Fecha y hora
- Referencia
- Estado
- Datos de la reserva asociada
- Saldo pendiente actual

### Imprimir Recibo

1. Ver detalle de pago
2. Click en **"Imprimir Recibo"**
3. Se abre vista de impresión
4. Imprimir o guardar como PDF

**Recibo incluye:**
- Datos del hotel
- Datos del cliente
- Detalle del pago
- Firma y sello
---

## 8️⃣ BÚSQUEDA DE HABITACIONES

### Búsqueda Avanzada

**Ruta:** `/buscar`

**Filtros disponibles:**

1. **Fecha de Entrada:** (requerido)
2. **Fecha de Salida:** (requerido)
3. **Tipo de Habitación:** Simple/Doble/Triple/Suite
4. **Número de Personas:** 1-4
5. **Precio Máximo:** Rango de presupuesto

### Resultados de Búsqueda

**Cada habitación muestra:**
- Número y tipo
- Precio por noche
- Precio total (según fechas)
- Descripción
- Imagen
- Botón **"Reservar"**

**Estados:**
- 🟢 Disponible: Puede reservarse
- 🔴 No disponible: Ocupada en esas fechas

### Reservar desde Búsqueda

1. Click en **"Reservar"**
2. Seleccionar cliente o crear nuevo
3. Revisar fechas y precio
4. Confirmar reserva
5. Redirige a detalle de reserva
6. Registrar primer pago (opcional)

---

## 9️⃣ REPORTES

### Tipos de Reportes

**1. Reporte de Ocupación**
- Habitaciones ocupadas vs disponibles
- Porcentaje de ocupación
- Por período

**2. Reporte de Ingresos**
- Total de ingresos
- Desglose por método de pago
- Comparativa mensual

**3. Reporte de Reservas**
- Total de reservas por estado
- Reservas por mes
- Clientes frecuentes

**4. Reporte de Pagos**
- Pagos completados
- Pagos pendientes
- Reembolsos realizados

### Generar Reporte

1. Ir a **"Reportes"**
2. Seleccionar tipo de reporte
3. Elegir rango de fechas
4. Click en **"Generar"**
5. Ver resultados
6. Exportar (PDF/Excel)

---

## 🔟 PREGUNTAS FRECUENTES (FAQ)

### ❓ ¿Cómo sé si una habitación está disponible?

**R:** Use la búsqueda avanzada con las fechas deseadas. Solo aparecerán habitaciones disponibles.

### ❓ ¿Puedo editar una reserva confirmada?

**R:** Sí, puede editar fechas y habitación si no hay conflictos. No puede cambiar el cliente.

### ❓ ¿Qué pasa si cancelo una reserva con pagos?

**R:** La reserva se marca como cancelada pero los pagos se conservan en el historial. Debe procesar reembolsos manualmente.

### ❓ ¿Cuándo cambia el estado de una reserva?

**Estados automáticos:**
- Pendiente → Confirmada (al pagar 50% o más)
- Confirmada → En Curso (en fecha de entrada)
- En Curso → Completada (en fecha de salida)

### ❓ ¿Cómo imprimo un recibo?

**R:** Entre al detalle del pago y click en "Imprimir Recibo". Se abre una ventana de impresión.

### ❓ ¿Qué hago si un cliente no aparece?

**R:** Primero busque por nombre, documento o email. Si no existe, créelo desde "Nuevo Cliente".

### ❓ ¿Puedo eliminar una habitación?

**R:** Solo si no tiene reservas futuras. Habitaciones con historial no pueden eliminarse.

### ❓ ¿Cómo veo el saldo pendiente de una reserva?

**R:** Entre al detalle de la reserva. En la columna derecha verá el resumen de cuenta con el saldo pendiente.

### ❓ ¿El sistema envía correos automáticos?

**R:** Por el momento no. Los correos deben enviarse manualmente.

---

## 📞 SOPORTE TÉCNICO

**Contacto:**
- 📧 Email: soporte@hotel.com
- 📱 Teléfono: +51 932 423 124
- ⏰ Horario: Lunes a Viernes 9:00 - 18:00

**Problemas comunes:**
- Olvidé mi contraseña → Usar "Recuperar Contraseña"
- Error al guardar → Verificar conexión a Internet
- No aparecen datos → Recargar página (F5)

---

## 📝 NOTAS IMPORTANTES

⚠️ **Seguridad:**
- Nunca comparta su contraseña
- Cierre sesión al terminar
- Use contraseñas fuertes

⚠️ **Datos:**
- Los datos se guardan automáticamente
- No hay "deshacer" en eliminaciones
- Haga respaldo de información crítica

⚠️ **Permisos:**
- Algunos usuarios tienen permisos limitados
- Consulte con su administrador

---

## 🎓 CAPACITACIÓN

**Videos tutoriales:** [Próximamente]
**Manual PDF:** [Descargar]
**Guía rápida:** [Imprimir]

---

**Versión:** 1.0
**Última actualización:** Noviembre 2025
**Desarrollado por:** ESTUDIANTES UNAP

---