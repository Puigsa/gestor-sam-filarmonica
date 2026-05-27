<?php

// Comprueba si existe una sesión iniciada
// Devuelve true si el usuario está logueado
function sesionIniciada()
{
    return isset($_SESSION['id_usuario']);
}

// Controla que solo puedan acceder usuarios con sesión iniciada
// Si no hay sesión, redirige al login
function comprobarAcceso()
{
    if (!sesionIniciada()) {
        header("Location: ../login.php");
        exit;
    }
}

// Comprueba que el usuario tenga el rol permitido
// Si el rol no coincide, lo redirige a la página principal según su rol
function comprobarRol($rolPermitido)
{
    if ($_SESSION['rol'] != $rolPermitido) {
        redirigirSegunRol();
        exit;
    }
}

// Si el usuario ya ha iniciado sesión,
// lo redirige automáticamente a su panel según su rol
// Se usa principalmente en login.php
function redirigirSegunRol()
{
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

// Validar DNI español o NIE
function validarDNI($dni)
{
    $dni = strtoupper(str_replace(' ', '', $dni));

    // DNI español: 8 números + 1 letra
    if (preg_match('/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $dni)) {
        return true;
    }

    // NIE: X/Y/Z + 7 números + 1 letra
    if (preg_match('/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $dni)) {
        return true;
    }

    return false;
}

// Función nativa PHP para validar que el email tenga formato correcto (usuario@dominio.com)
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


// Validar que el teléfono sea 9 dígitos
function validarTelefono($telefono) {
    return preg_match('/^[0-9]{9}$/', $telefono);
}


// Paginación
function paginar($total_registros, $registros_por_pagina){

    $pagina = isset($_GET['pagina']) 
        ? (int)$_GET['pagina'] 
        : 1;

    if($pagina < 1){
        $pagina = 1;
    }

    $total_paginas = ceil($total_registros / $registros_por_pagina);

    $offset = ($pagina - 1) * $registros_por_pagina;

    return [
        'pagina' => $pagina,
        'total_paginas' => $total_paginas,
        'offset' => $offset,
        'limite' => $registros_por_pagina
    ];
}

function mostrarPaginacion($pagina_actual, $total_paginas, $params_extra = []) {

    if ($total_paginas <= 1) return;

    echo "<div class='paginacion'>";

    for ($i = 1; $i <= $total_paginas; $i++) {

        $params = array_merge($params_extra, ['pagina' => $i]);
        $url    = '?' . http_build_query($params);
        $clase  = ($i == $pagina_actual) ? "pagina-activa" : "";

        echo "<a href='$url' class='$clase'>$i</a>";
    }

    echo "</div>";
}

function botonVolver($ruta = null){

    if($ruta){
        echo "<a href='$ruta' class='btn-volver'>← Volver</a>";
    } else {
        echo "<a href='javascript:history.back()' class='btn-volver'>← Volver</a>";
    }
}
