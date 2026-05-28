<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

// Redirige al login si no hay sesión activa
comprobarAcceso();
// Redirige si el usuario no tiene rol de administrador
comprobarRol('admin');

$conexion     = conectar();
$mensaje      = "";
$tipo_mensaje = "";

if (isset($_POST['crear'])) {

    // Sanitizar entradas de texto
    $titulo      = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha       = $_POST['fecha'];
    $hora        = $_POST['hora'];
    $lugar       = trim($_POST['lugar']);
    // Si el checkbox no viene en POST, se considera 0 (no publicado)
    $publicado   = isset($_POST['publicado']) ? 1 : 0;
    $cartel      = ""; // Se rellenará si se sube una imagen

    // Validar campos obligatorios
    if (empty($titulo) || empty($fecha) || empty($hora) || empty($lugar)) {
        $mensaje      = "Rellena los campos obligatorios.";
        $tipo_mensaje = "error";
    } else {

        // --- Subida de imagen (opcional) ---
        if (isset($_FILES['cartel']) && $_FILES['cartel']['error'] === UPLOAD_ERR_OK) {
            $archivo    = $_FILES['cartel'];
            $extension  = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            // Comprobar que la extensión está en la lista blanca
            if (!in_array($extension, $permitidas)) {
                $mensaje      = "Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.";
                $tipo_mensaje = "error";

            // Comprobar que no supera los 2 MB
            } elseif ($archivo['size'] > 2 * 1024 * 1024) {
                $mensaje      = "La imagen no puede superar los 2 MB.";
                $tipo_mensaje = "error";

            } else {
                $carpeta = "../subidas/eventos/";

                // Crear la carpeta de destino si todavía no existe
                if (!is_dir($carpeta)) {
                    mkdir($carpeta, 0755, true);
                }

                // Generar nombre único para evitar sobreescrituras
                $nombre_archivo = uniqid('evento_') . '.' . $extension;
                $ruta_destino   = $carpeta . $nombre_archivo;

                // Mover el archivo desde la carpeta temporal de PHP al destino final
                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                    // Guardar la ruta relativa (no absoluta) para mayor portabilidad
                    $cartel = "subidas/eventos/" . $nombre_archivo;
                } else {
                    $mensaje      = "Error al guardar la imagen.";
                    $tipo_mensaje = "error";
                }
            }
        }

        // Solo insertar en BD si no hubo ningún error previo (validación o imagen)
        if (empty($mensaje)) {

            // Prepared statement para prevenir SQL injection
            $sql  = "INSERT INTO eventos (titulo, descripcion, fecha, hora, lugar, publicado, cartel) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            // s = string, i = integer
            $stmt->bind_param("sssssis", $titulo, $descripcion, $fecha, $hora, $lugar, $publicado, $cartel);

            if ($stmt->execute()) {
                desconectar($conexion);
                // Redirigir al listado con parámetro de éxito para mostrar aviso
                header("Location: eventos.php?ok=1");
                exit;
            } else {
                $mensaje      = "Error al crear el evento: " . $conexion->error;
                $tipo_mensaje = "error";
            }
            $stmt->close();
        }
    }
}

// Cerrar conexión antes de cargar las plantillas
desconectar($conexion);

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";
?>

<main class="main">
    <?php botonVolver(); ?>
    <h1>Crear evento</h1>

    <?php if (!empty($mensaje)) : ?>
        <p class="mensaje <?= $tipo_mensaje ?>"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- enctype="multipart/form-data" es obligatorio para que $_FILES funcione -->
    <form method="POST" enctype="multipart/form-data" class="form-crear">

        <div class="form-grupo">
            <label for="titulo">Título <span class="obligatorio">*</span></label>
            <!-- value preserva lo escrito si el formulario se recarga por error -->
            <input type="text" id="titulo" name="titulo"
                   value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>" required>
        </div>

        <div class="form-grupo">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
        </div>

        <!-- Fecha y hora en la misma fila mediante clase CSS -->
        <div class="form-grupo form-grupo--doble">
            <div>
                <label for="fecha">Fecha <span class="obligatorio">*</span></label>
                <input type="date" id="fecha" name="fecha"
                       value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>
            </div>
            <div>
                <label for="hora">Hora <span class="obligatorio">*</span></label>
                <input type="time" id="hora" name="hora"
                       value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-grupo">
            <label for="lugar">Lugar <span class="obligatorio">*</span></label>
            <input type="text" id="lugar" name="lugar"
                   value="<?= htmlspecialchars($_POST['lugar'] ?? '') ?>" required>
        </div>

        <div class="form-grupo">
            <label for="cartel">Cartel del evento</label>
            <!-- accept filtra en el explorador de archivos del SO, la validación real es en PHP -->
            <input type="file" id="cartel" name="cartel"
                   accept=".jpg,.jpeg,.png,.webp,.gif">
            <small>Formatos permitidos: JPG, PNG, WEBP, GIF — Máximo 2 MB</small>
        </div>

        <div class="form-grupo form-grupo--check">
            <label>
                <!-- checked se mantiene si el formulario se recarga por error -->
                <input type="checkbox" name="publicado"
                       <?= isset($_POST['publicado']) ? 'checked' : '' ?>>
                Publicar evento
            </label>
        </div>

        <button type="submit" name="crear" class="btn btn-primary">Crear evento</button>
    </form>
</main>

<?php include "../plantillas/footer_privado.php"; ?>