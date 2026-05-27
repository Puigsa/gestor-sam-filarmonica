
<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol('admin');

$conexion = conectar();
$mensaje = "";
$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header("Location: pagos.php");
    exit;
}

$pago = $conexion->query("SELECT * FROM pagos WHERE id_pago=$id");
if ($pago->num_rows == 0) {
    header("Location: pagos.php");
    exit;
}

$datos = $pago->fetch_assoc();

if (isset($_POST['editar'])) {
    $estado = $_POST['estado'];
    $metodo = trim($_POST['metodo']);
    $concepto = trim($_POST['concepto']);

    $sql = "UPDATE pagos SET estado='$estado', metodo='$metodo', concepto='$concepto' WHERE id_pago=$id";
    
    if ($conexion->query($sql)) {
        $_SESSION['mensaje'] = "<p class='mensaje_exito'>Pago actualizado correctamente</p>";
        header("Location: pagos.php");
        exit;
    } else {
        $mensaje_error = "<p class='mensaje_error'>Error al actualizar el pago.</p>" . $conexion->error;
    }
}

desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Editar pago</h1>
    
    <form method="POST">
        <select name="estado" required>
            <option value="pendiente" <?= $datos['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
            <option value="pagado" <?= $datos['estado'] == 'pagado' ? 'selected' : '' ?>>Pagado</option>
            <option value="vencido" <?= $datos['estado'] == 'vencido' ? 'selected' : '' ?>>Vencido</option>
        </select>
        
        <input type="text" name="metodo" value="<?= $datos['metodo'] ?>" placeholder="Método de pago">
        <textarea name="concepto" rows="4" placeholder="Concepto"><?= $datos['concepto'] ?></textarea>
        
        <button type="submit" name="editar">Actualizar pago</button>
    </form>
    
    <?php if (!empty($mensaje_error)) { ?>
        <?= $mensaje_error ?>
    <?php } ?>
</main>

<?php include "../plantillas/footer_privado.php"; ?>
