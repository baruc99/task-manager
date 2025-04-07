# ✅ Task Manager - Laravel 12 API

Una API RESTful desarrollada con Laravel 12 que permite a los usuarios registrar, iniciar sesión y gestionar sus propias tareas mediante un sistema de autenticación con Sanctum. Las tareas incluyen funcionalidades completas de CRUD y una asignación de prioridad automática basada en la fecha de vencimiento y el estado.

---

## 🚀 Características

- Registro e inicio de sesión de usuarios
- Autenticación con Laravel Sanctum
- CRUD de tareas (Crear, Leer, Actualizar, Eliminar)
- Cada usuario puede ver solo sus propias tareas
- Documentación de API con Postman

---

## 🧾 Requisitos

- PHP >= 8.2
- Composer
- MySQL o MariaDB
- Node.js y npm (solo si usarás frontend)
- Laravel 12

---

## ⚙️ Instalación

```bash
# Clonar el repositorio
git clone https://github.com/baruc99/task-manager.git
cd task-manager

# Instalar dependencias de PHP
composer install

# Copiar archivo de entorno y configurar
cp .env.example .env

# Generar la clave de la aplicación
php artisan key:generate

# Configura tu conexión a la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Ejecutar migraciones
php artisan migrate

# Levantar servidor
php artisan serve


## 📚 Documentación de la API

Puedes ver la documentación interactiva de la API usando Postman:

[Documentación de la API en Postman](https://documenter.getpostman.com/view/42866316/2sB2cVehDu)
