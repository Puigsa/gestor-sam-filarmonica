<?php 

// Comprueba si existe una sesión iniciada
// Devuelve true si el usuario está logueado
function sesionIniciada() {
    return isset($_SESSION['id_usuario']);
}

// Controla que solo puedan acceder usuarios con sesión iniciada
// Si no hay sesión, redirige al login
function comprobarAcceso() {
    if (!sesionIniciada()) {
        header("Location: ../login.php");
        exit;
    }
}

// Comprueba que el usuario tenga el rol permitido
// Si el rol no coincide, lo redirige a la página principal según su rol
function comprobarRol($rolPermitido) {
    if ($_SESSION['rol'] != $rolPermitido) {
        redirigirSegunRol();
        exit;
    }
}

// Si el usuario ya ha iniciado sesión,
// lo redirige automáticamente a su panel según su rol
// Se usa principalmente en login.php
function redirigirSegunRol() {
    if (sesionIniciada()) {
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

?>