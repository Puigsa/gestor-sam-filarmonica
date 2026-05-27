<?php
session_start();

require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

if(!isset($_GET['id_asignatura']) || empty($_GET['id_asignatura'])){
    header("Location: asignaturas.php");
    exit;
}

$conexion = conectar();

$id_profesor = $_SESSION['id_usuario'];
$id_asignatura = (int)$_GET['id_asignatura'];

// VERIFICAR QUE LA ASIGNATURA ES DEL PROFESOR
$verificar = $conexion->query("SELECT nombre FROM asignaturas WHERE id_asignatura = $id_asignatura AND id_profesor = $id_profesor");

if($verificar->num_rows == 0){
    header("Location: asignaturas.php");
    exit;
}

$datos_asignatura = $verificar->fetch_assoc();

// TOTAL ALUMNOS
$total_result = $conexion->query("SELECT COUNT(DISTINCT u.id_usuario) AS total FROM asignaturas a
                                    JOIN matriculas m ON a.id_curso = m.id_curso
                                    JOIN usuarios u ON m.id_alumno = u.id_usuario
                                    WHERE a.id_asignatura = $id_asignatura AND m.estado = 'activa' AND u.rol = 'alumno'");

$total = $total_result->fetch_assoc()['total'];

// PAGINACIÓN
$paginacion = paginar($total, 10);

// OBTENER ALUMNOS PAGINADOS
$alumnos = $conexion->query("SELECT DISTINCT u.id_usuario, u.nombre, u.email FROM asignaturas a
                                JOIN matriculas m ON a.id_curso = m.id_curso
                                JOIN usuarios u ON m.id_alumno = u.id_usuario
                                WHERE a.id_asignatura = $id_asignatura AND m.estado = 'activa' AND u.rol = 'alumno'
                                ORDER BY u.nombre ASC
                                LIMIT {$paginacion['offset']}, {$paginacion['limite']}");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>

    <h1>Alumnos - <?= $datos_asignatura['nombre'] ?></h1>

    <a href="exportar_alumnos_pdf.php?id_asignatura=<?= $id_asignatura ?>" class="btn-crear"> Exportar PDF </a>

    <?php if($total > 0){ ?>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while($alumno = $alumnos->fetch_assoc()){ ?>
                    <tr>
                        <td><?= $alumno['nombre'] ?></td>
                        <td><?= $alumno['email'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas'], [
            'id_asignatura' => $id_asignatura
        ]); ?>

    <?php } else { ?>
        <p>No hay alumnos matriculados.</p>
    <?php } ?>

</main>

<?php
desconectar($conexion);
include "../plantillas/footer_privado.php";
?>