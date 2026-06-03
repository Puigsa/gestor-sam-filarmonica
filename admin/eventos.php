<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// TOTAL
$total_result = $conexion->query("SELECT COUNT(*) AS total FROM eventos");
$total = $total_result->fetch_assoc()['total'];

// PAGINACIÓN
$paginacion = paginar($total, 10, 'pagina_eventos');

$eventos = $conexion->query("SELECT * FROM eventos ORDER BY fecha DESC LIMIT {$paginacion['offset']}, {$paginacion['limite']}");
desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    botonVolver(); ?>
    <h1>Gestión de Eventos</h1>
    
    <a href="crear_evento.php" class="btn-crear">+ Crear evento</a>
    
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Título</th>
                <th>Fecha</th>
                <th class="ocultar-mobile">Hora</th>
                <th>Lugar</th>
                <th>Publicado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($evento = $eventos->fetch_assoc()) { ?>
                <tr>
                    <td><?= $evento['titulo'] ?></td>
                    <td><?= date('d/m/Y', strtotime($evento['fecha'])) ?></td>
                    <td class="ocultar-mobile"><?= $evento['hora'] ?></td>
                    <td><?= $evento['lugar'] ?></td>
                    <td><?= $evento['publicado'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <a href="eliminar_evento.php?id=<?= $evento['id_evento'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas'], [], 'pagina_eventos'); ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>