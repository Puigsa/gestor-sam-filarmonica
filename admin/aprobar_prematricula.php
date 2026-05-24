<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$id = $_GET['id'] ?? null;

// Verificar que existe el ID en la URL
if (!$id) {
    header("Location: matriculas.php");
    exit;
}

// Obtener datos de la prematrícula
$prematricula = $conexion->query("SELECT * FROM prematriculas WHERE id_prematricula=$id");
if ($prematricula->num_rows == 0) {
    header("Location: matriculas.php");
    exit;
}

$datos = $prematricula->fetch_assoc();
$mensaje = "";

if (isset($_POST['aprobar'])) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = trim($_POST['direccion']);
    $id_curso = $_POST['id_curso'];
    $id_instrumento = $_POST['id_instrumento'];

    if (empty($nombre) || empty($apellidos) || empty($email) || empty($dni) || empty($direccion)) {
        $mensaje = "Rellena todos los campos obligatorios";
    } else {
        // Verificar si DNI ya existe
        $existe_dni = $conexion->query("SELECT id_usuario FROM usuarios WHERE dni='$dni'");
        
        if ($existe_dni->num_rows > 0) {
            $id_alumno = $existe_dni->fetch_assoc()['id_usuario'];
        } else {
            $password = password_hash($dni, PASSWORD_DEFAULT);
            $sql_usuario = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, dni, fecha_nacimiento, direccion, rol) 
                            VALUES ('$nombre', '$apellidos', '$email', '$password', '$telefono', '$dni', '$fecha_nacimiento', '$direccion', 'alumno')";
            
            if ($conexion->query($sql_usuario)) {
                $id_alumno = $conexion->insert_id;
            } else {
                $mensaje = "Error al crear usuario";
                desconectar($conexion);
                include "../plantillas/header_privado.php";
                include "../plantillas/navbar_privado.php";
                ?>
                <main class="main">
                    <h1>Aprobar prematrícula</h1>
                    <p class="mensaje"><?= $mensaje ?></p>
                </main>
                <?php
                include "../plantillas/footer_privado.php";
                exit;
            }
        }

        // Crear matrícula
        $sql_matricula = "INSERT INTO matriculas (id_alumno, id_curso, id_instrumento, fecha_matricula, estado) 
                          VALUES ($id_alumno, $id_curso, $id_instrumento, NOW(), 'activa')";
        
        if ($conexion->query($sql_matricula)) {
            $id_matricula = $conexion->insert_id;
            
            // Crear pago
            $precio = $conexion->query("SELECT precio FROM cursos WHERE id=$id_curso")->fetch_assoc()['precio'];
            $sql_pago = "INSERT INTO pagos (id_alumno, id_matricula, importe, estado) 
                         VALUES ($id_alumno, $id_matricula, $precio, 'pendiente')";
            $conexion->query($sql_pago);
            
            // Actualizar prematrícula
            $conexion->query("UPDATE prematriculas SET estado='aprobada' WHERE id_prematricula=$id");
            
            desconectar($conexion);
            header("Location: matriculas.php");
            exit;
        } else {
            $mensaje = "Error al crear matrícula";
        }
    }
}

$cursos = $conexion->query("SELECT id, nombre FROM cursos");
$instrumentos = $conexion->query("SELECT id, nombre FROM instrumentos");

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <h1>Aprobar prematrícula</h1>
    
    <form method="POST">
        <h3>Datos personales</h3>
        <input type="text" name="nombre" value="<?= $datos['nombre'] ?>" required>
        <input type="text" name="apellidos" value="<?= $datos['apellidos'] ?>" required>
        <input type="email" name="email" value="<?= $datos['email'] ?>" required>
        <input type="tel" name="telefono" value="<?= $datos['telefono'] ?>">
        <input type="text" name="dni" value="<?= $datos['dni'] ?>" required>
        <input type="date" name="fecha_nacimiento" value="<?= $datos['fecha_nacimiento'] ?>">
        <input type="text" name="direccion" value="<?= $datos['direccion'] ?>" placeholder="Dirección" required>
        
        <h3>Datos académicos</h3>
        <select name="id_curso" required>
            <option value="">Selecciona curso</option>
            <?php while ($curso = $cursos->fetch_assoc()) { ?>
                <option value="<?= $curso['id'] ?>" <?= $curso['id'] == $datos['id_curso'] ? 'selected' : '' ?>>
                    <?= $curso['nombre'] ?>
                </option>
            <?php } ?>
        </select>
        
        <select name="id_instrumento" required>
            <option value="">Selecciona instrumento</option>
            <?php while ($inst = $instrumentos->fetch_assoc()) { ?>
                <option value="<?= $inst['id'] ?>" <?= $inst['id'] == $datos['id_instrumento'] ? 'selected' : '' ?>>
                    <?= $inst['nombre'] ?>
                </option>
            <?php } ?>
        </select>
        
        <button type="submit" name="aprobar">Aprobar y formalizar matrícula</button>
    </form>
    
    <?php if (!empty($mensaje)) { ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>
