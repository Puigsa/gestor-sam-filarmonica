<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// TOTAL USUARIOS
$total_usuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuarios");

$total = $total_usuarios->fetch_assoc()['total'];


// PAGINACIÓN
$paginacion = paginar($total, 5);


// USUARIOS PAGINADOS
$usuarios = $conexion->query("SELECT  id_usuario, nombre, apellidos, email, rol 
                                FROM usuarios ORDER BY nombre ASC  
                                LIMIT {$paginacion['limite']} OFFSET {$paginacion['offset']}");

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">

    <?php botonVolver("index.php"); ?>

    <h1>Gestión de Usuarios</h1>
    
    <a href="crear_usuario.php" class="btn-crear">+ Crear usuario</a>
    
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
                <tr>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellidos'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td><?= ucfirst($usuario['rol']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn-editar">Editar</a>
                        <a href="eliminar_usuario.php?id=<?= $usuario['id_usuario'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas']); ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>