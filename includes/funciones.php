<?php function redirigirSegunRol() {
    if (isset($_SESSION['user'])) {
        if ($_SESSION['rol'] == "admin") {
            header("Location: admin/index.php");
        } elseif ($_SESSION['rol'] == "profesor") {
            header("Location: profesor/index.php");
        } elseif ($_SESSION['rol'] == "alumno") {
            header("Location: alumno/index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }
}

function comprobarRol($rol) {
    if (!isset($_SESSION['user']) || $_SESSION['rol'] != $rol) {
        header("Location: ../login.php");
        exit;
    }
}
?>