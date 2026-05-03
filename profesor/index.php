<?php
require_once "../includes/config.php";
require_once "../includes/funciones.php";

comprobarAcceso();
comprobarRol("profesor");

include "../plantillas/header_privado.php";
include "../plantillas/navbar_privado.php";

include "../plantillas/footer_privado.php";


?>