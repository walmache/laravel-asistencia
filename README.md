# Sistema de Asistencia con Reconocimiento Facial

Sistema de gestión de asistencia a eventos con múltiples métodos de registro: reconocimiento facial, códigos QR, códigos de barras y registro manual.

## Características

- **Gestión de Organizaciones**: Administración de múltiples organizaciones
- **Gestión de Eventos**: Creación y administración de eventos con fechas de inicio y fin
- **Múltiples Métodos de Registro**:
  - Reconocimiento facial mediante API Python con FastAPI
  - Códigos QR
  - Códigos de barras
  - Registro manual
- **Gestión de Usuarios**: Sistema de usuarios con roles (admin, coordinator, user)
- **Registro de Asistencias**: Historial completo de asistencias con timestamps y métodos utilizados

## Arquitectura

El proyecto utiliza una arquitectura de microservicios:

- **Backend Laravel**: API REST y aplicación web principal
- **API Python**: Servicio de reconocimiento facial con FastAPI y face_recognition
- **Base de Datos MySQL**: Almacenamiento de datos
- **Redis**: Cache y sesiones
- **Nginx**: Servidor web y proxy reverso

## Requisitos

- Docker y Docker Compose
- PHP 8.2+
- Composer
- Node.js y npm (para assets frontend)

## Instalación

1. Clonar el repositorio:
```bash
git clone <repository-url>
cd laravel-asistencia
```

2. Configurar variables de entorno:
```bash
cp .env.example .env
php artisan key:generate
```

3. Iniciar los contenedores Docker:
```bash
docker compose up -d
```

4. Instalar dependencias de PHP:
```bash
docker compose exec app composer install
```

5. Ejecutar migraciones:
```bash
docker compose exec app php artisan migrate
```

6. Instalar dependencias de Node.js:
```bash
npm install
npm run build
```

## Servicios

- **Laravel App**: http://localhost:8000
- **Python API**: http://localhost:8001
- **MySQL**: localhost:3307
- **Redis**: localhost:6379

## Estructura del Proyecto

```
laravel-asistencia/
├── app/
│   ├── Http/Controllers/    # Controladores de la aplicación
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Servicios (FaceRecognitionService)
├── services/
│   └── python-api/          # API de reconocimiento facial
├── database/
│   └── migrations/          # Migraciones de base de datos
└── resources/
    └── views/               # Vistas (Twig templates)
```

## Modelos Principales

- **Organization**: Organizaciones que gestionan eventos
- **Event**: Eventos con fechas, códigos QR/barcode y configuración de reconocimiento facial
- **User**: Usuarios del sistema con roles y embeddings faciales
- **Attendance**: Registros de asistencia a eventos

## API de Reconocimiento Facial

La API Python expone dos endpoints principales:

- `POST /extract-embedding`: Extrae el embedding facial de una imagen
- `POST /verify-face`: Verifica si un rostro coincide con usuarios registrados en un evento

## Desarrollo

Para desarrollo local con hot-reload:

```bash
composer run dev
```

Para ejecutar tests:

```bash
composer run test
```

## Licencia

MIT License
