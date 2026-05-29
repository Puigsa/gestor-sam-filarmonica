---
title: Despliegue
nav_order: 6
---

# Despliegue
## Índice

* [Requisitos](#requisitos)
* [Instalación en local (XAMPP)](#instalación-en-local-xampp)
  * [1. Clonar el repositorio](#1-clonar-el-repositorio)
  * [2. Importar la base de datos](#2-importar-la-base-de-datos)
  * [3. Verificar la configuración](#3-verificar-la-configuración)
  * [4. Carpetas de subidas](#4-carpetas-de-subidas)
  * [5. Acceder a la aplicación](#5-acceder-a-la-aplicación)
* [Credenciales de demostración](#credenciales-de-demostración)
* [Despliegue en AWS](#despliegue-en-aws)
* [Notas para producción](#notas-para-producción)
* [Funcionalidades recomendadas para la evaluación](#funcionalidades-recomendadas-para-la-evaluación)
* [Documentación en GitHub Pages](#documentación-en-github-pages)
* [Control de versiones](#control-de-versiones)

---

## Requisitos

| Componente | Versión mínima |
|---|---|
| PHP | 8.0 |
| MySQL / MariaDB | 10.4 |
| Apache | 2.4 |
| Extensión PHP MySQLi | Habilitada |

El entorno de desarrollo utilizado es **XAMPP** (Apache + MariaDB + PHP). El entorno de producción es **AWS**.

---

## Instalación en local (XAMPP)

### 1. Clonar el repositorio

```bash
git clone https://github.com/Puigsa/gestor-sam-filarmonica.git
```

Colocar la carpeta dentro de `htdocs`:

```
C:/xampp/htdocs/gestor-sam-filarmonica/
```

### 2. Importar la base de datos

1. Abrir **phpMyAdmin** en `http://localhost/phpmyadmin`
2. Crear una base de datos llamada `escuela_filarmonica`
3. Importar el archivo `escuela_filarmonica.sql` incluido en el proyecto

### 3. Verificar la configuración

`includes/config.php` tiene los valores por defecto de XAMPP:

```php
$host    = "localhost";
$usuario = "root";
$pass    = "";          // Sin contraseña en XAMPP por defecto
$db      = "escuela_filarmonica";
```

### 4. Carpetas de subidas

Verificar que existen y tienen permisos de escritura:

```
gestor-sam-filarmonica/subidas/eventos/
gestor-sam-filarmonica/subidas/recursos/
```

El código las crea automáticamente con `mkdir()` si no existen, pero en producción es recomendable crearlas manualmente con permisos `755`.

### 5. Acceder a la aplicación

```
http://localhost/gestor-sam-filarmonica/
```

---

## Credenciales de demostración

Para facilitar la evaluación de la aplicación se incluyen cuentas de prueba correspondientes a los distintos roles del sistema.

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `admin@email.com` | `123456` |
| Profesor | `profesor@email.com` | `123456` |
| Alumno | `alumno@email.com` | `123456` |

Estas cuentas han sido creadas exclusivamente para pruebas y demostración de funcionalidades. En un entorno de producción se recomienda sustituirlas por credenciales seguras y personalizadas.

El archivo `escuela_filarmonica.sql` incluye datos de ejemplo para facilitar la evaluación de la aplicación. Tras la importación estarán disponibles usuarios, cursos, asignaturas, matrículas, pagos, anuncios, recursos y eventos de prueba, permitiendo comprobar el funcionamiento de todos los módulos sin necesidad de introducir información adicional.

---

## Despliegue en AWS

El proyecto se despliega en **AWS** como plataforma de hosting. Pasos generales:

1. Lanzar una instancia EC2 con Amazon Linux / Ubuntu
2. Instalar Apache, PHP 8.0 y MySQL/MariaDB
3. Subir el proyecto vía SSH/SFTP o clonar desde GitHub
4. Crear la base de datos e importar el SQL
5. Configurar `includes/config.php` con las credenciales del servidor
6. Asignar permisos `755` a las carpetas `subidas/eventos/` y `subidas/recursos/`
7. Configurar el grupo de seguridad para permitir tráfico HTTP/HTTPS (puertos 80/443)

---

## Notas para producción

**Seguridad de `config.php`**  
Cambiar las credenciales de base de datos. Proteger el archivo con `.htaccess` si está en el directorio público.

**Contraseñas**  
Cambiar las contraseñas de los usuarios de prueba antes de publicar.

**Zona horaria**  
Configurada en `config.php`:
```php
date_default_timezone_set("Europe/Madrid");
```

**Plazo de prematrícula**  
En `matricula.php` está forzado a `true` para pruebas. Activar la validación de fechas para producción:
```php
$plazo_abierto = ($hoy >= $inicio_plazo && $hoy <= $fin_plazo);
```

---

## Funcionalidades recomendadas para la evaluación

- Administrador: gestión de usuarios, matrículas, pagos, asignaturas y eventos.
- Profesor: consulta de alumnado, publicación de anuncios y subida de recursos.
- Alumno: consulta de asignaturas, anuncios y recursos didácticos.

---

## Documentación en GitHub Pages

La documentación está publicada mediante **GitHub Pages** usando los archivos Markdown de la carpeta `docs/`. La configuración se encuentra en `docs/docs/_config.yml`.

## Control de versiones

| Rama | Uso |
|---|---|
| `main` | Código estable |
| `develop` | Desarrollo activo |
