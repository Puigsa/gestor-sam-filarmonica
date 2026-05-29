---
title: Conclusiones
nav_order: 7
---

# Conclusiones

## Resultado del proyecto

El Gestor SAM Filarmónica cumple los objetivos definidos en la propuesta formal: digitaliza la gestión académica y administrativa de la Escuela de Música La Filarmónica, sustituyendo los procesos manuales por una aplicación web con tres perfiles de usuario diferenciados, diseño responsive y acceso desde cualquier dispositivo.

Todas las funcionalidades del alcance están implementadas y verificadas: el flujo completo de prematrícula → aprobación → matrícula → pago funciona correctamente, los tres roles tienen sus áreas securizadas, y la parte pública muestra los eventos gestionados desde el admin en tiempo real.

---

## Requisitos del proyecto cumplidos

**Diseño de Interfaces Web**
- Guía de estilo web elaborada
- Prototipos de vistas móvil, tablet y escritorio (incluidos en la propuesta)
- HTML5 para layout y formularios
- Diseño responsive mobile-first con media queries

**Despliegue de Aplicaciones Web**
- AWS como plataforma de hosting
- GitHubPages para la presentación del proyecto
- Git y GitHub como control de versiones con ramas `main` y `develop`
- Documentación en Markdown publicada con GitHub Pages

**Desarrollo Web Entorno Servidor**
- PHP con lógica modular por roles
- CRUD completo sobre las entidades (usuarios, cursos, matrículas, pagos, eventos, recursos, anuncios)
- Sesiones con control de acceso por rol
- Listados con filtrado y paginación
- Subida de archivos (carteles de eventos y recursos didácticos)
- Plantillas reutilizables

**Desarrollo Web Entorno Cliente**
- JavaScript con sintaxis clara y comentado
- Validación de formularios con expresiones regulares
- Uso de eventos del DOM (click, change, submit)
- Manipulación del DOM: mostrar/ocultar campos de tutor, mensajes de error, carga dinámica de contenido
- jQuery 3.7.1 + jQuery UI 1.13.2 para interacciones y efectos
- Slideshow de imágenes en la página principal
- Uso del objeto `Date` para calcular la edad y mostrar el tutor
- AJAX (`fetch`) para la carga dinámica de asignaturas y eventos

---

## Limitaciones conocidas

- El formulario de `login.php` usa una consulta sin prepared statement, lo que lo hace vulnerable a SQL injection. Pendiente de refactorizar.
- El plazo de prematrícula está forzado a `true` en el código.
- No hay notificaciones por email al aprobar/rechazar prematrículas.

---

## Mejoras futuras

- **Notificaciones por email** al aprobar/rechazar prematrículas y al publicar nuevos anuncios
- **Recuperación de contraseña** por email con enlace de un solo uso
- **Gestión de calificaciones** por asignatura y alumno
- **Pasarela de pago** integrada (Stripe o Redsys) para formalizar el pago online
- **Sistema de mensajería interna** entre usuarios