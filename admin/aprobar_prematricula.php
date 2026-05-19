<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: matriculas.php");
    exit;
}

$conexion = conectar();

// Obtener datos de la prematrícula
$sql = "SELECT * FROM prematriculas WHERE id_prematricula=$id";
$prematricula = $conexion->query($sql);
if (!$prematricula) {
    echo "Error SQL (prematricula): " . $conexion->error . " -- SQL: " . $sql;
    exit;
}
if ($prematricula->num_rows == 0) {
    header("Location: /gestor-sam-filarmonica/admin/matriculas.php");
    exit;
}

$datos = $prematricula->fetch_assoc();

// Crear usuario alumno
$nombre = $datos['nombre'];
$apellidos = $datos['apellidos'];
$email = $datos['email'];
$telefono = $datos['telefono'];
$dni = $datos['dni'];
$fecha_nacimiento = $datos['fecha_nacimiento'];
$direccion = $datos['direccion'];
$password = password_hash($dni, PASSWORD_DEFAULT);


// Comprobar si el usuario ya existe por DNI
$sql = "SELECT id_usuario FROM usuarios WHERE dni='$dni'";
$existe_usuario = $conexion->query($sql);
if (!$existe_usuario) {
    echo "Error SQL (check usuario por dni): " . $conexion->error . " -- SQL: " . $sql;
    exit;
}
if ($existe_usuario->num_rows > 0) {
    $alumno = $existe_usuario->fetch_assoc();
    $id_alumno = (int)$alumno['id_usuario'];
} else {
    // Si no hay por DNI, comprobar por email (caso de email repetido)
    $sql = "SELECT id_usuario FROM usuarios WHERE email='$email'";
    $existe_email = $conexion->query($sql);
    if (!$existe_email) {
        echo "Error SQL (check usuario por email): " . $conexion->error . " -- SQL: " . $sql;
        exit;
    }
    if ($existe_email->num_rows > 0) {
        // Usar usuario existente con ese email
        $alumno = $existe_email->fetch_assoc();
        $id_alumno = (int)$alumno['id_usuario'];
    } else {
        // Crear nuevo usuario
        $sql_usuario = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, fecha_nacimiento, rol, direccion) ";
        $sql_usuario .= "VALUES ('$nombre', '$apellidos', '$email', '$password', '$telefono', '$dni', '$fecha_nacimiento', 'alumno', '$direccion')";

        if ($conexion->query($sql_usuario)) {
            $id_alumno = (int)$conexion->insert_id;
        } else {
            echo "Error SQL (insert usuario): " . $conexion->error . " -- SQL: " . $sql_usuario;
            desconectar($conexion);
            exit;
        }
    }
}

// Crear matrícula 
$id_curso = (int)$datos['id_curso'];
$id_instrumento = (int)$datos['id_instrumento'];

$sql_matricula = "INSERT INTO matriculas (id_alumno, id_curso, id_instrumento, fecha_matricula, estado) ";
$sql_matricula .= "VALUES ($id_alumno, $id_curso, $id_instrumento, NOW(), 'activa')";

if ($conexion->query($sql_matricula)) {
    $id_matricula = (int)$conexion->insert_id;
    
    // Actualizar prematrícula a aprobada
    $sql_up = "UPDATE prematriculas SET estado='aprobada' WHERE id_prematricula=$id";
     
    if ($conexion->query($sql_up) === false) {
        echo "Error SQL (update prematricula): " . $conexion->error . " -- SQL: " . $sql_up;
        desconectar($conexion);
        exit;
    }

   
} else {
    echo "Error SQL (insert matricula): " . $conexion->error . " -- SQL: " . $sql_matricula;
    desconectar($conexion);
    exit;
}

$sql_precio = $conexion->query("SELECT precio FROM cursos WHERE id_curso=$id_curso");
$resultado = $sql_precio->fetch_assoc();
$precio = $resultado['precio'];

$sql_pago = "INSERT INTO pagos (id_alumno, id_matricula, importe, estado) VALUES ($id_alumno, $id_matricula, $precio, 'pendiente')";

$conexion->query($sql_pago);

if (!$conexion->query($sql_pago)) {
    echo "Error SQL (insert pago): " . $conexion->error . " -- SQL: " . $sql_pago;
    desconectar($conexion);
    exit;
}

desconectar($conexion);
header("Location: /gestor-sam-filarmonica/admin/matriculas.php");
exit;
