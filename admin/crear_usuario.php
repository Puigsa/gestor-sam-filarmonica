<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";

if (isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = trim($_POST['direccion']);
    $rol = $_POST['rol'];

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($_POST['password']) || empty($rol)) {
        $mensaje = "Rellena los campos obligatorios";
    } else {
        $existe = $conexion->query("SELECT id_usuario FROM usuarios WHERE email='$email'");
        if ($existe->num_rows > 0) {
            $mensaje = "El email ya existe";
        } else {
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, fecha_nacimiento, direccion, rol) 
                    VALUES ('$nombre', '$apellidos', '$email', '$password', '$telefono', '$dni', '$fecha_nacimiento', '$direccion', '$rol')";
            
            if ($conexion->query($sql)) {
                $mensaje = "Usuario creado correctamente";
                header("Location: usuarios.php");
                exit;
            } else {
                $mensaje = "Error al crear el usuario";
            }
        }
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Crear usuario</h1>
    
    <form method="POST" class="formulario-usuario">
        Nombre: <input type="text" name="nombre" required>
        Apellidos: <input type="text" name="apellidos" required>
        Email: <input type="email" name="email" required>
        Contraseña: <input type="password" name="password" required>
        Teléfono: <input type="tel" name="telefono" required>
        DNI: <input type="text" name="dni" required>
        Fecha de nacimiento: <input type="date" name="fecha_nacimiento" required>
        Dirección: <input type="text" name="direccion">
        
        <select name="rol" required>
            <option value="">Selecciona un rol</option>
            <option value="admin">Admin</option>
            <option value="profesor">Profesor</option>
            <option value="alumno">Alumno</option>
        </select>
        
        <button type="submit" name="crear">Crear usuario</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>