<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$eventos = $conexion->query("SELECT * FROM eventos ORDER BY fecha DESC");
desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Gestión de Eventos</h1>
    
    <a href="crear_evento.php" class="btn-crear">+ Crear evento</a>
    
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Título</th>
                <th>Fecha</th>
                <th>Hora</th>
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
                    <td><?= $evento['hora'] ?></td>
                    <td><?= $evento['lugar'] ?></td>
                    <td><?= $evento['publicado'] ? 'Sí' : 'No' ?></td>
                    <td>
                        <a href="editar_evento.php?id=<?= $evento['id_evento'] ?>" class="btn-editar">Editar</a>
                        <a href="eliminar_evento.php?id=<?= $evento['id_evento'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<?php include "../plantillas/footer_privado.php"; ?>