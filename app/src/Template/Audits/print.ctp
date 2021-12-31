<?php

class AuditPDF extends FPDF {

    function Header() {
        if($this->PageNo() > 1) {
            $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 10, 8, 25);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 10, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'R');
            $this->Ln(5);
            $this->Cell(0, 10, utf8_decode('CLIENTE XXX'), 0, 0, 'R');
            $this->Ln(20);
        }
    }

    function Footer() {
        if($this->PageNo() > 1) {
            $this->SetY(-15);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, utf8_decode('Centro Tecnológico Higiénico-Sanitario APTHISA'));
            $this->Cell(0, 5, utf8_decode('Juntos alimentamos la seguridad'), 0, 0, 'R');
            $this->Ln();
            $this->Cell(0, 5, utf8_decode('91 110 22 24 / www.apthisa.com'));
            $this->Cell(5, 5, utf8_decode('Página ').$this->PageNo().' de {nb}', 0, 0, 'R');
        }
    }

    function Cover() {
        // TODO Create image
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 50, 50, 100);
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'header.png', 50, 140, 100);
        $this->SetY(-90);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 0, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'C');
        $this->Ln(30);
        $this->Cell(0, 0, utf8_decode('CLIENTE XXX'), 0, 0, 'C');
    }
    function Avaliation() {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(0, 20, utf8_decode('Evaluación de la auditoría'), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(20);
        $this->MultiCell(0, 5, utf8_decode('La presente Auditoría Interna Higiénico-Sanitaria se ha evaluado teniendo en cuenta distintos apartados esenciales para llevar a cabo un correcto Sistema de Autocontrol. Este sistema necesario y obligatorio para las empresas alimentarias según el Reglamento (CE) 852/2004, está basado en los principios de Análisis de Peligros y Puntos de Control Críticos (APPCC).'));
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode('Dichos apartados se han puntuado atendiendo tanto a obligaciones legales como a recomendaciones técnicas que se consideran claves para garantizar la seguridad en los alimentos, de acuerdo a las referencias descritas al final del presente informe.'));
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode('El modo de puntuación considerado es el siguiente:'));
        $this->Ln(5);
        $this->SetX(60);
        $this->SetFillColor(214, 233, 198);
        $this->Cell(10, 7, 'A', 1, 0, 'C', true);
        $this->Cell(50, 7, 'Satisfactorio', 1, 0, 'C', true);
        $this->Cell(30, 7, '85 - 100', 1, 0, 'C', true);
        $this->Ln();
        $this->SetX(60);
        $this->SetFillColor(250, 235, 204);
        $this->Cell(10, 7, 'B', 1, 0, 'C', true);
        $this->Cell(50, 7, 'Aceptable', 1, 0, 'C', true);
        $this->Cell(30, 7, '65 - 84', 1, 0, 'C', true);
        $this->Ln();
        $this->SetX(60);
        $this->SetFillColor(235, 204, 209);
        $this->Cell(10, 7, 'C', 1, 0, 'C', true);
        $this->Cell(50, 7, 'Necesita mejorar', 1, 0, 'C', true);
        $this->Cell(30, 7, '0 - 64', 1, 0, 'C', true);
        $this->SetFillColor(0, 0, 0);
        $this->Ln(15);
        $this->MultiCell(0, 5, utf8_decode('Además de las puntuaciones de cada apartado, en el apartado de Detalles de la auditoría indicando las oportunidades de mejora detectadas de cada premisa durante la auditoría en cada uno de ellos. También se indican en este apartado las posibles No Conformidades que el auditor ha encontrado (puntuadas como C en cada premisa).'));
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode('Se recuerda que es posible que lo detectable en la presente auditoría pueda estar sujeto a modificaciones a corto plazo por lo que es primordial la colaboración y el trabajo constante del personal responsable para llevar a cabo un adecuado Sistema de Autocontrol.'));
        $this->Ln(5);
        $this->MultiCell(0, 5, utf8_decode('Apthisa agradece a la dirección y empleados del establecimiento su atención y colaboración durante la auditoría.'));

        $this->Ln(30);
        $this->Cell(20);
        $this->SetFont('Arial', 'U', 12);
        $this->Cell(100, 0, 'Realizado en');
        $this->Cell(100, 0, 'Elaborado por');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(20);
        $this->MultiCell(100, 0, 'CLIENTE XXX');
        $this->SetFont('Arial', '', 12);
        $this->Cell(120);
        $this->Cell(0, 0, 'Juana de los palotes');
        $this->Ln(7);
        $this->Cell(120);
        $this->Cell(0, 0, 'Auditora de APTHISA');
    }

}


$pdf = new AuditPDF();
$pdf->AliasNbPages();

$pdf->AddPage();
$pdf->Cover();

$pdf->AddPage();
$pdf->Avaliation();

$pdf->Output();