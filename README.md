# Oris

Oris es una aplicación web desarrollada con **PHP** y **Laravel** para
la gestión integral de una IPS. Permite manejar consultas médicas
(agendamiento, cancelación, visualización y modificación), gestión de
usuarios (pacientes, médicos y personal administrativo) y visualizar
estadísticas relacionadas con la operación de la clínica. La aplicación
fue creada para solucionar la problemática presente en varias IPS de
Bucaramanga, Colombia, permitiendo **centralizar, digitalizar y
optimizar** la gestión de citas médicas y usuarios (especialmente empresariales).

## 📚 Características principales

-    Gestión de usuarios (roles, autenticación y administración).
-    Agendamiento, modificación y cancelación de citas médicas.
-    Visualización de agenda diaria e historial de citas.
-    Estadísticas operativas de la IPS.
-    Envío de correos mediante Mailtrap.
-    Integración con Laravel Telescope (modo desarrollo).

## 🧰 Requisitos

-   PHP \>= 8.x
-   Composer
-   Node.js
-   NPM
-   XAMPP
-   Git
-   Cuenta en Mailtrap (para pruebas de correo)

## ⚙️ Instalación y Configuración

### 1️⃣ Clonar el repositorio

``` bash
git clone https://github.com/jgesc-05/oris.git
cd oris
```

### 2️⃣ Instalar dependencias

``` bash
composer install
npm install
```

### 3️⃣ Crear y configurar el archivo `.env`

``` bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configurar BD en el `.env`

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=oris_db
    DB_USERNAME=root

**Importante**: Se debe crear, localmente en MySQL, una bd llamada oris_bd para el correcto funcionamiento, además de verificar los puertos (que sean correctos).

### 5️⃣ Configurar Mailtrap en el `.env`

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=25
    MAIL_USERNAME=tu_usuario_mailtrap
    MAIL_PASSWORD=tu_password_mailtrap
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

**Importante**: Los parámetros de "tu_usuario_mailtrap" y "tu_password_mailtrap" se obtienen creando una cuenta en https://mailtrap.io/home, utilizando las credenciales otorgadas por el servicio SMTP.

### 6️⃣ Ejecutar migraciones y seeders

``` bash
php artisan migrate:fresh --seed
```

### 7️⃣ Iniciar el servidor local

``` bash
php artisan serve
npm run dev
```

### ⚠️ Ejecución de correos electrónicos automáticos
Para enviar los correos a los usuarios con citas en las próximas 72 horas, es necesario ejecutar un comando para este fin. Adicionalmente, permite únicamente un correo y se debe cortar con Ctrl + C 
``` bash
php artisan citas:loop
```

## 📂 Estructura del proyecto

    app/
    database/
    resources/
    routes/
    public/

-   **app/** → Lógica principal (Models, Controllers, Policies, Console, etc.)
-   **resources/** → Vistas Blade y assets frontend
-   **database/** → Migraciones y seeders
-   **routes/** → Rutas web
-   **public/** → Archivos accesibles públicamente

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Haz un fork del proyecto, crea una
rama y envía un pull request.


## 👨‍💻 Autores

Desarrollado por **Leydy Macareo y Juan Escobar**.

