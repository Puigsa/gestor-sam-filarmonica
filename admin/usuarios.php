<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();

// DESACTIVAR / REACTIVAR
if (isset($_GET['desactivar'])) {

    $id = (int)$_GET['desactivar'];

    $usuario = $conexion->query("SELECT activo FROM usuarios WHERE id_usuario = $id");

    if ($usuario->num_rows > 0) {

        $datos = $usuario->fetch_assoc();
        $nuevo_estado = $datos['activo'] ? 0 : 1;

        if ($conexion->query("UPDATE usuarios SET activo = $nuevo_estado WHERE id_usuario = $id")) {
            $_SESSION['mensaje'] = "<p class='mensaje_exito'>Estado del usuario actualizado</p>";
        } else {
            $_SESSION['mensaje'] = "<p class='mensaje_error'>Error al actualizar estado</p>";
        }
    }

    $params = http_build_query([
        'rol'    => $_GET['rol']    ?? '',
        'orden'  => $_GET['orden']  ?? 'nombre',
        'dir'    => $_GET['dir']    ?? 'ASC',
        'pagina' => $_GET['pagina'] ?? 1,
    ]);

    header("Location: usuarios.php?$params");
    exit;
}

// FILTROS
$rol       = $_GET['rol']   ?? '';
$orden     = $_GET['orden'] ?? 'nombre';
$direccion = $_GET['dir']   ?? 'ASC';


// VALIDAR ORDEN
$campos_validos = ['nombre', 'apellidos', 'email', 'rol'];
if (!in_array($orden, $campos_validos)) $orden = 'nombre';

// VALIDAR DIRECCIÓN
if ($direccion !== 'ASC' && $direccion !== 'DESC') $direccion = 'ASC';

// WHERE DINÁMICO
$where = "WHERE 1=1";
if (!empty($rol)) {
    $rol_safe = $conexion->real_escape_string($rol);
    $where .= " AND rol = '$rol_safe'";
}

// TOTAL
$total_result = $conexion->query("SELECT COUNT(*) AS total FROM usuarios $where");
$total        = $total_result->fetch_assoc()['total'];

// PAGINACIÓN
$paginacion = paginar($total, 5);

// CONSULTA
$usuarios = $conexion->query("
    SELECT id_usuario, nombre, apellidos, email, rol, activo
    FROM usuarios
    $where
    ORDER BY $orden $direccion
    LIMIT {$paginacion['offset']}, {$paginacion['limite']}
");

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
$rol = $_GET['rol'] ?? '';
?>

<main class="main">

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    ?>

    <?php botonVolver("index.php"); ?>

    <h1>Gestión de Usuarios</h1>

    <a href="crear_usuario.php" class="btn-crear">+ Crear usuario</a>

    <form method="GET" class="filtro">



        <select name="rol">
            <option value="" <?= $rol == ''         ? 'selected' : '' ?>>Todos los roles</option>
            <option value="admin" <?= $rol == 'admin'    ? 'selected' : '' ?>>Admin</option>
            <option value="profesor" <?= $rol == 'profesor' ? 'selected' : '' ?>>Profesor</option>
            <option value="alumno" <?= $rol == 'alumno'   ? 'selected' : '' ?>>Alumno</option>
        </select>

        <input type="hidden" name="orden" value="<?= $orden ?>">
        <input type="hidden" name="dir" value="<?= $direccion ?>">

        <button type="submit">Filtrar</button>

    </form>

    <table class="tabla-usuarios">

        <thead>
            <tr>
                <th>
                    <a href="?rol=<?= $rol ?>&orden=nombre&dir=<?= $orden == 'nombre' && $direccion == 'ASC' ? 'DESC' : 'ASC' ?>&pagina=1">
                        Nombre <?= $orden == 'nombre' ? ($direccion == 'ASC' ? '▲' : '▼') : '' ?>
                    </a>
                </th>
                <th>
                    <a href="?rol=<?= $rol ?>&orden=apellidos&dir=<?= $orden == 'apellidos' && $direccion == 'ASC' ? 'DESC' : 'ASC' ?>&pagina=1">
                        Apellidos <?= $orden == 'apellidos' ? ($direccion == 'ASC' ? '▲' : '▼') : '' ?>
                    </a>
                </th>
                <th>
                    <a href="?rol=<?= $rol ?>&orden=email&dir=<?= $orden == 'email' && $direccion == 'ASC' ? 'DESC' : 'ASC' ?>&pagina=1">
                        Email <?= $orden == 'email' ? ($direccion == 'ASC' ? '▲' : '▼') : '' ?>
                    </a>
                </th>
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

                        <?php if ($usuario['activo']) { ?>
                            <a href="usuarios.php?desactivar=<?= $usuario['id_usuario'] ?>&rol=<?= $rol ?>&orden=<?= $orden ?>&dir=<?= $direccion ?>&pagina=<?= $paginacion['pagina'] ?>"
                                class="btn-eliminar"
                                onclick="return confirm('¿Desactivar usuario?')">Desactivar</a>
                        <?php } else { ?>
                            <a href="usuarios.php?desactivar=<?= $usuario['id_usuario'] ?>&rol=<?= $rol ?>&orden=<?= $orden ?>&dir=<?= $direccion ?>&pagina=<?= $paginacion['pagina'] ?>"
                                class="btn-aprobar">Reactivar</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php mostrarPaginacion($paginacion['pagina'], $paginacion['total_paginas'], [
        'rol'   => $rol,
        'orden' => $orden,
        'dir'   => $direccion,
    ]); ?>

</main>

<?php include "../plantillas/footer_privado.php"; ?>