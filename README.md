# Sistema de Gestión de Asistencias Multimodal

Sistema web completo para el registro y gestión de asistencias a eventos con soporte para cuatro métodos de registro: check manual, escaneo de QR, escaneo de código de barras y reconocimiento facial.

## 🚀 Despliegue con Docker

### Prerrequisitos

- Docker (versión 20.10 o superior)
- Docker Compose (versión 2.0 o superior)

### Pasos para desplegar

1. **Clonar el repositorio** (si aplica) o asegurarse de tener los archivos en el directorio actual

2. **Construir y levantar los servicios**

```bash
docker-compose up -d --build
```

3. **Instalar dependencias de Laravel**

```bash
docker-compose exec app composer install
```

4. **Generar clave de aplicación**

```bash
docker-compose exec app php artisan key:generate
```

5. **Configurar permisos de almacenamiento**

```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

6. **Ejecutar migraciones de la base de datos**

```bash
docker-compose exec app php artisan migrate --seed
```

7. **Acceder al sistema**

- Frontend: http://localhost:8080
- Backend Laravel: http://localhost:8080
- API Python: http://localhost:8000 (internamente en la red Docker)

## 📊 Datos de Prueba

### Usuarios de prueba

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@example.com | password | admin |
| coordinator@example.com | password | coordinator |
| user@example.com | password | user |

### Organizaciones de prueba

1. **Tech Events S.A.**
   - ID: 1
   - Descripción: Empresa dedicada a la organización de eventos tecnológicos

2. **Educación Superior Ltda.**
   - ID: 2
   - Descripción: Institución educativa para eventos académicos

### Eventos de prueba

1. **Conferencia de Desarrollo Web 2024**
   - ID: 1
   - Organización: Tech Events S.A.
   - Fecha: 2024-12-15 09:00:00
   - Estado: ongoing
   - Umbral facial: 0.6
   - Permite registro facial: Sí

2. **Taller de Seguridad Informática**
   - ID: 2
   - Organización: Tech Events S.A.
   - Fecha: 2024-12-20 14:00:00
   - Estado: scheduled
   - Umbral facial: 0.6
   - Permite registro facial: Sí

### Usuarios asignados a eventos

- Usuario admin@example.com está asignado al evento ID 1
- Usuario coordinator@example.com está asignado al evento ID 1
- Usuario user@example.com está asignado al evento ID 1

## 📋 Funcionalidades

- Registro de asistencia por 4 métodos: manual, QR, código de barras y reconocimiento facial
- Gestión de organizaciones y eventos
- Administración de usuarios y roles
- Reportes de asistencia
- Exportación a CSV/PDF
- Gestión de consentimiento para procesamiento facial

## 🔧 Configuración adicional

### Variables de entorno

El sistema utiliza las siguientes variables de entorno (definidas en `.env`):

```
DB_HOST=db
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=laravel
DB_PASSWORD=password
PYTHON_API_URL=http://python-api:8000
```

### Microservicio Python

El microservicio de reconocimiento facial expone los siguientes endpoints:

- `POST /extract-embedding`: Extrae embedding facial de una imagen
- `POST /verify-face`: Verifica coincidencia facial en un evento

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor, siga el flujo de trabajo estándar de Git y asegúrese de probar todos los cambios antes de enviar un pull request.

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT.
