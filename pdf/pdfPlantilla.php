<?php

require_once "fpdf.php";

function textoPDF($texto){

    return utf8_decode($texto);
}

class PDFBase extends FPDF {

    function Header() {

        $this->Image('../estaticos/img/logotipo.png', 10, 8, 25);

        $this->SetFont('Helvetica', 'B', 16);

        $this->Cell(0, 10, textoPDF('Escuela de Música La Filarmónica'), 0, 1, 'C');

        $this->SetFont('Helvetica', '', 10);

        $this->Cell(0, 6, date('d/m/Y'), 0, 1, 'R');

        $this->Ln(5);
    }


    function Footer() {

        $this->SetY(-15);

        $this->SetFont('Helvetica', 'I', 8);

        $this->Cell(
            0,
            10,
            textoPDF('Página ' . $this->PageNo()),
            0,
            0,
            'C'
        );
    }
}