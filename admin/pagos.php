<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

$pagos = $conexion->query("SELECT p.*, u.nombre, u.apellidos, m.id_matricula
                           FROM pagos p 
                           JOIN usuarios u ON p.id_alumno = u.id_usuario 
                           JOIN matriculas m ON p.id_matricula = m.id_matricula 
                           ORDER BY p.fecha_pago DESC");


include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Gestión de Pagos</h1>

    <table class="tabla-pagos">
        <thead>
            <tr>
                <th>Alumno</th>
                <th class="ocultar-mobile">Matrícula</th>
                <th>Concepto</th>
                <th>Importe</th>
                <th>Estado</th>
                <th class="ocultar-mobile">Fecha pago</th>
                <th class="ocultar-mobile">Método</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (!$pagos) {
                echo "<tr><td colspan='8'>Error al cargar pagos: " . $conexion->error . "</td></tr>";
            } else {
                if ($pagos->num_rows == 0) {
                    echo "<tr><td colspan='8'>No hay pagos registrados</td></tr>";
                } else {
                    while ($pago = $pagos->fetch_assoc()) {
                       $sql_usuario = $conexion->query("SELECT nombre, apellidos FROM usuarios WHERE id_usuario=" . $pago['id_alumno']);
                       $usuario = $sql_usuario->fetch_assoc(); 
                       $nombre = $usuario['nombre'];
                       $apellidos = $usuario['apellidos'];
                        ?>
                        <tr>
                            <td><?= $nombre. ' ' . $apellidos ?></td>
                            <td class="ocultar-mobile"><?= $pago['id_matricula'] ?></td>
                            <td><?= $pago['concepto'] ?></td>
                            <td><?= $pago['importe'] ?>€</td>
                            <td><?= ucfirst($pago['estado']) ?></td>
                            <td class="ocultar-mobile"><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                            <td class="ocultar-mobile"><?= $pago['metodo'] ?></td>
                            <td>
                                <a href="editar_pago.php?id=<?= $pago['id_pago'] ?>" class="btn-editar">Editar</a>
                            </td>
                        </tr>
            <?php }
                }
            } ?>
        </tbody>
    </table>
</main>

<?php include "../plantillas/footer_privado.php"; ?>