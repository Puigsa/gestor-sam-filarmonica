<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/funciones.php";
require_once "../pdf/pdfPlantilla.php";

comprobarAcceso();
comprobarRol("admin");

$conexion = conectar();
$estado = $_GET['estado'] ?? 'activa';

// Validar estado
$estados_validos = ['activa', 'finalizada', 'cancelada'];
if (!in_array($estado, $estados_validos)) $estado = 'activa';

$matriculas = $conexion->query("SELECT DISTINCt
                                m.fecha_matricula, m.estado,
                                u.nombre, u.apellidos, u.dni, u.email, u.telefono, u.fecha_nacimiento,
                                c.nombre as curso_nombre,
                                i.nombre as instrumento_nombre,
                                p.estado AS estado_pago
                                FROM matriculas m 
                                JOIN usuarios u ON m.id_alumno = u.id_usuario 
                                JOIN cursos c ON m.id_curso = c.id_curso 
                                JOIN instrumentos i ON m.id_instrumento = i.id_instrumento 
                                LEFT JOIN pagos p ON m.id_matricula = p.id_matricula
                                WHERE m.estado='$estado' 
                                ORDER BY m.fecha_matricula DESC");
if (!$matriculas) {

    die($conexion->error);
}

desconectar($conexion);

// GENERAR PDF
$pdf = new PDFBase('L');

$pdf->AddPage();

// Título
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, textoPDF('Matrículas - ' . ucfirst($estado) . 's'), 0, 1, 'C');
$pdf->Ln(5);


// Cabecera tabla
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(20, 8, textoPDF('Nombre'),      1, 0, 'C', true);
$pdf->Cell(30, 8, textoPDF('Apellidos'),   1, 0, 'C', true);
$pdf->Cell(20, 8, textoPDF('DNI'),         1, 0, 'C', true);
$pdf->Cell(35, 8, textoPDF('Email'),       1, 0, 'C', true);
$pdf->Cell(20, 8, textoPDF('Teléfono'),    1, 0, 'C', true);
$pdf->Cell(25, 8, textoPDF('Nacimiento'),  1, 0, 'C', true);
$pdf->Cell(50, 8, textoPDF('Curso'),       1, 0, 'C', true);
$pdf->Cell(30, 8, textoPDF('Instrumento'), 1, 0, 'C', true);
$pdf->Cell(20, 8, textoPDF('F. Matrícula'), 1, 0, 'C', true);
$pdf->Cell(15, 8, textoPDF('Pago'), 1, 1, 'C', true);

// Filas
$pdf->SetFont('Helvetica', '', 8);

if ($matriculas->num_rows == 0) {

    $pdf->SetFont('Helvetica', 'B', 12);

    $pdf->Cell(
        265,
        12,
        textoPDF('No hay matrículas con estado: ' . $estado),
        1,
        1,
        'C'
    );
} else {
    while ($mat = $matriculas->fetch_assoc()) {

        // Fila alumno
        $pdf->Cell(20, 8, textoPDF($mat['nombre']), 1, 0);
        $pdf->Cell(30, 8, textoPDF($mat['apellidos']), 1, 0);
        $pdf->Cell(20, 8, textoPDF($mat['dni']), 1, 0, 'C');
        $pdf->Cell(35, 8, textoPDF($mat['email']), 1, 0);
        $pdf->Cell(20, 8, textoPDF($mat['telefono']), 1, 0, 'C');
        $pdf->Cell(25, 8, date('d/m/Y', strtotime(textoPDF($mat['fecha_nacimiento']))), 1, 0, 'C');
        $pdf->Cell(50, 8, textoPDF($mat['curso_nombre']), 1, 0);
        $pdf->Cell(30, 8, textoPDF($mat['instrumento_nombre']), 1, 0);
        $pdf->Cell(20, 8, date('d/m/Y', strtotime(textoPDF($mat['fecha_matricula']))), 1, 0, 'C');
        $pdf->Cell(15, 8, textoPDF($mat['estado_pago']), 1, 1, 'C');
    }
}
$pdf->Output('D', 'matriculas_' . $estado . '.pdf');
