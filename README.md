# VIP2Cars Backend API

API REST desarrollada para la gestión de **clientes y
vehículos**.

El sistema permite:

- CRUD de **clientes**
- CRUD de **vehículos asociados a clientes**
- Control de concurrencia mediante `updated_at`
- Soft deletes y reactivación de registros
- Validaciones de negocio

---

# Requisitos del entorno

El proyecto fue desarrollado con:

- PHP **8.3+**
- Laravel **12.x**
- MySQL **8.x**
- Composer **2.x**
- Apache / Nginx

Extensiones de PHP necesarias:

- bcmath
- ctype
- curl
- fileinfo
- gd
- intl
- json
- mbstring
- openssl
- pdo_mysql
- tokenizer
- xml
- zip

---

# Instalación y configuración

## 1. Clonar el repositorio

```bash
git clone https://github.com/bartowindows20/vip2cars_back.git
cd vip2cars_back
```

## 2. Instalar dependencias

```bash
composer install
```

## 3. Crear archivo de entorno

```bash
cp .env.example .env
```

## 4. Configurar variables de entorno

Editar `.env`:

    APP_NAME=VIP2Cars
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=vip2cars_db
    DB_USERNAME=root
    DB_PASSWORD=

---

## 5. Generar clave de Laravel

```bash
php artisan key:generate
```

## 6. Ejecutar migraciones y seeders

```bash
php artisan migrate:fresh --seed
```

Esto creará todas las tablas necesarias e insertará los registros semilla.

---

# Puesta en marcha

Iniciar el servidor de desarrollo:

```bash
php artisan serve
```

La API quedará disponible en:

    http://127.0.0.1:8000

---

# Estructura de la Base de Datos

Las migraciones se encuentran en:

    database/migrations

### Tabla: clients

- id
- names
- document_type
- document_number
- phone
- email
- created_at
- updated_at
- deleted_at

### Tabla: cars

- id
- client_id
- plate
- car_model_id
- year
- created_at
- updated_at
- deleted_at

### Tabla: car_models

- id
- brand_id
- name
- created_at
- updated_at
- deleted_at

### Tabla: brands

- id
- name
- created_at
- updated_at
- deleted_at

---

# Endpoints principales

## Clientes

    GET /api/clients
    GET /api/clients/{id}
    POST /api/clients
    PUT /api/clients/{id}
    DELETE /api/clients/{id}

## Vehículos por cliente

    GET /api/clients/{client}/cars
    POST /api/clients/{client}/cars

## Vehículos

    GET /api/cars/{car}
    PUT /api/cars/{car}
    DELETE /api/cars/{car}

---

# Stack tecnológico

- Laravel
- MySQL
- Eloquent ORM
- REST API

---

## Documentación

Este repositorio incluye una **colección de Postman** para facilitar las pruebas de los endpoints de la API.

### 1. Importar la colección

1.  Abrir **Postman**.
2.  Ir a **Import**.
3.  Seleccionar el archivo incluido en el repositorio:
    /docs/VIP2CARS.postman_collection.json

---

## Autor

Proyecto desarrollado como parte de una **prueba técnica backend**.
