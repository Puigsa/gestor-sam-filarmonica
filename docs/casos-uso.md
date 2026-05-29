---
title: Casos de Uso
nav_order: 5
---

# 4. Casos de uso principales

Este capítulo recoge varios casos de uso representativos que complementan el flujo principal de negocio y muestran las funcionalidades más relevantes de la plataforma para administradores, profesorado y alumnado.

## Índice

* [CU-01: Gestión de pagos](#cu-01-gestión-de-pagos)
* [CU-02: Publicación de anuncios](#cu-02-publicación-de-anuncios)
* [CU-03: Gestión de recursos didácticos](#cu-03-gestión-de-recursos-didácticos)
* [CU-04: Eventos y agenda pública](#cu-04-eventos-y-agenda-pública)
* [CU-05: Exportación a PDF institucional](#cu-05-exportación-a-pdf-institucional)

## CU-01: Gestión de pagos

El sistema genera un pago con `estado = 'pendiente'` al aprobar cada prematrícula. El administrador lo localiza en el módulo de pagos y actualiza manualmente el estado, el método y la fecha de cobro una vez realizado el pago de forma presencial. No hay pasarela de pago integrada.

**Tablas:** `pagos`, `matriculas`, `usuarios`

<img src="img/pago.png" width="40%">
---

## CU-02: Publicación de anuncios

El profesor crea anuncios vinculados a una de sus asignaturas. El sistema los muestra automáticamente a los alumnos del curso correspondiente, filtrados por su matrícula activa. El administrador puede también crear y eliminar anuncios sin restricción de asignatura.

**Tablas:** `anuncios`, `asignaturas`, `usuarios`, `matriculas`

<img src="img/anuncios.png" width="40%">

---

## CU-03: Gestión de recursos didácticos

El profesor sube materiales (archivos o enlaces externos) vinculados a sus asignaturas. Los archivos se validan por extensión en servidor y se almacenan con nombre único para evitar colisiones. Los alumnos acceden a los recursos filtrados por su curso activo.

**Tablas:** `recursos`, `asignaturas`, `usuarios`, `matriculas`

<img src="img/recursos.png" width="30%">


---

## CU-04: Eventos y agenda pública

El administrador publica eventos (conciertos, audiciones) con cartel, fecha, hora y lugar. La agenda pública usa FullCalendar y carga los datos de forma asíncrona. Visible para cualquier visitante sin necesidad de autenticación.

**Tablas:** `eventos`

<img src="img/agenda.png" width="40%">


---

## CU-05: Exportación a PDF institucional

Dos flujos de exportación con cabecera corporativa (logotipo + nombre de la escuela):

| Exportación | Contenido |
|---|---|
| Listado de matrículas (admin) | Filtrado por estado · alumno / curso / instrumento / fecha |
| Listado de alumnos por asignatura (profesor) | Alumno / DNI / teléfono / instrumento / datos tutor si menor de edad |

**Tablas:** `matriculas`, `usuarios`, `cursos`, `instrumentos`, `asignaturas`

<img src="img/pdf.png" width="40%">

