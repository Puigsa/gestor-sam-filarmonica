<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/funciones.php";
require_once "../pdf/pdfPlantilla.php";

comprobarAcceso();
comprobarRol("profesor");

if (!isset($_GET['id_asignatura']) || empty($_GET['id_asignatura'])) {
    header("Location: asignaturas.php");
    exit;
}

$conexion = conectar();
$id_profesor = $_SESSION['id_usuario'];
$id_asignatura = (int)$_GET['id_asignatura'];

// Verificar que la asignatura es del profesor
$verificar = $conexion->query("SELECT nombre FROM asignaturas WHERE id_asignatura = $id_asignatura AND id_profesor = $id_profesor");

if ($verificar->num_rows == 0) {
    header("Location: asignaturas.php");
    exit;
}

$datos_asignatura = $verificar->fetch_assoc();

// Obtener todos los alumnos con todos los campos
$alumnos = $conexion->query("SELECT DISTINCT
                                u.nombre, u.apellidos, u.dni, u.email, u.telefono, u.fecha_nacimiento,
                                m.tutor_nombre, m.tutor_dni, m.tutor_email, m.tutor_telefono
                                FROM asignaturas a
                                JOIN matriculas m ON a.id_curso = m.id_curso
                                JOIN usuarios u ON m.id_alumno = u.id_usuario
                                WHERE a.id_asignatura = $id_asignatura AND m.estado = 'activa' AND u.rol = 'alumno' AND u.activo = 1
                                ORDER BY u.nombre ASC");

desconectar($conexion);

// GENERAR PDF
$pdf = new PDFBase('L'); // Horizontal para que quepan todos los campos
$pdf->AddPage();

// Título
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, textoPDF('Listado de alumnos - ' . $datos_asignatura['nombre']), 0, 1, 'C');
$pdf->Ln(5);

// Cabecera tabla
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(50, 8, textoPDF('Nombre'),      1, 0, 'C', true);
$pdf->Cell(50, 8, textoPDF('Apellidos'),   1, 0, 'C', true);
$pdf->Cell(30, 8, textoPDF('DNI'),         1, 0, 'C', true);
$pdf->Cell(70, 8, textoPDF('Email'),       1, 0, 'C', true);
$pdf->Cell(30, 8, textoPDF('Teléfono'),    1, 0, 'C', true);
$pdf->Cell(30, 8, textoPDF('Nacimiento'),  1, 1, 'C', true);

// Filas
$pdf->SetFont('Helvetica', '', 9);

while ($alumno = $alumnos->fetch_assoc()) {

    // Fila alumno
    $pdf->Cell(50, 8, textoPDF($alumno['nombre']), 1, 0);
    $pdf->Cell(50, 8, textoPDF($alumno['apellidos']), 1, 0);
    $pdf->Cell(30, 8, textoPDF($alumno['dni']), 1, 0, 'C');
    $pdf->Cell(70, 8, textoPDF($alumno['email']), 1, 0);
    $pdf->Cell(30, 8, textoPDF($alumno['telefono']), 1, 0, 'C');
    $pdf->Cell(30, 8, date('d/m/Y', strtotime($alumno['fecha_nacimiento'])), 1, 1, 'C');

    // Si tiene tutor mostrar fila adicional en amarillo
    if (!empty($alumno['tutor_nombre'])) {
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetFillColor(255, 255, 200);
        $pdf->Cell(20, 7, textoPDF('Tutor:'), 1, 0, 'C', true);
        $pdf->Cell(50, 7, textoPDF($alumno['tutor_nombre']), 1, 0, '', true);
        $pdf->Cell(30, 7, textoPDF($alumno['tutor_dni']), 1, 0, 'C', true);
        $pdf->Cell(70, 7, textoPDF($alumno['tutor_email']), 1, 0, '', true);
        $pdf->Cell(90, 7, textoPDF($alumno['tutor_telefono']), 1, 1, 'C', true);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetFillColor(230, 230, 230);
    }
}

$pdf->Output('D', 'alumnos_' . textoPDF($datos_asignatura['nombre']) . '.pdf');