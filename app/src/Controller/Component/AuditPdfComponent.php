<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Controller\Component;
use FPDF;

class AuditPDF extends FPDF {

    public $audit;

    function Header() {
        if($this->PageNo() > 1) {
            $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 10, 8, 25);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 10, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'R');
            $this->Ln(5);
            $this->Cell(0, 10, utf8_decode($this->audit->customer->name), 0, 0, 'R');
            $this->Ln(20);
        }
    }

    function Footer() {
        if($this->PageNo() > 1) {
            $this->SetY(-15);
            $this->SetFont('Arial', '', 10);
            $this->Cell(95, 5, utf8_decode('Centro Tecnológico Higiénico-Sanitario APTHISA'));
            $this->Cell(95, 5, utf8_decode('Juntos alimentamos la seguridad'), 0, 0, 'R');
            $this->Ln();
            $this->Cell(95, 5, utf8_decode('91 110 22 24 / www.apthisa.com'));
            $this->Cell(98, 5, utf8_decode('Página ').$this->PageNo().' de {nb}', 0, 0, 'R');
        }
    }

    function Title($title) {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(0, 20, utf8_decode($title), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
    }

    function Cover() {
        // TODO Create image
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 50, 50, 100);
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'header.png', 50, 140, 100);
        $this->SetY(-90);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 0, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'C');
        $this->Ln(30);
        $this->Cell(0, 0, utf8_decode($this->audit->customer->name), 0, 0, 'C');
    }

    function SelectReport($template) {
        $this->AddPage();
        $this->SelectReportIntro($template);
        $this->AddPage();
        $this->SelectReportSummary($template);
        $this->AddPage();
        $this->SelectReportDetail($template);
    }

    function SelectReportIntro($template) {
        $this->Title('Evaluación de la auditoría');
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
        $this->MultiCell(100, 0, utf8_decode($this->audit->customer->name));
        $this->SetFont('Arial', '', 12);
        $this->Cell(120);
        $this->Cell(0, 0, utf8_decode($this->audit->auditor->name));
        $this->Ln(7);
        $this->Cell(120);
        // TODO Add to user model
        $this->Cell(0, 0, 'Auditora de APTHISA');
    }

    function SelectReportSummary($template) {
        $this->Title('Resumen de puntuaciones');
        $this->Ln(20);
        $this->SetFont('Arial', '', 10);
        foreach($template->form_template_sections as $s) {
            $this->SetX(20);
            $this->Cell(150, 8, utf8_decode($s->name), 1, 0);
            $this->Cell(15, 8, round($s->score)." ", 1, 0, 'R');
            $this->Ln(8);
        }
        $this->SetX(20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(150, 8, utf8_decode('Total'), 1, 0);
        $this->Cell(15, 8, $template->score." ", 1, 0, 'R');
        $this->Ln(20);
        $this->SelectReportSummaryTotalTable($template);
    }

    function SelectReportSummaryTotalTable($template) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(90);
        $this->Cell(55, 8, utf8_decode('Total'), 1, 0, 'C');
        $this->Cell(40, 8, "{$template->score}%", 1, 0, 'C');
        $this->Ln(8);
        $this->SetX(90);
        $this->Cell(55, 8, utf8_decode('Puntuación'), 1, 0, 'C');
        if($template->score < 65) {
            $this->SetFillColor(235, 204, 209);
            $letter = 'C';
        } elseif($template->score < 85) {
            $this->SetFillColor(250, 235, 204);
            $letter = 'B';
        } else {
            $this->SetFillColor(214, 233, 198);
            $letter = 'A';
        }
        $this->Cell(40, 8, $letter, 1, 0, 'C', true);
        $this->SetFillColor(0, 0, 0);
    }

    function SelectReportDetail($template) {
        $this->Title('Detalles de auditoría');
        $this->Ln(20);

        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(237, 239, 246);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(85, 8, utf8_decode('Requisitos'), 1, 0, 'C', true);
        $this->Cell(15, 8, utf8_decode('Pt'), 1, 0, 'C', true);
        $this->Cell(85, 8, utf8_decode('Aspectos destacables'), 1, 0, 'C', true);
        $this->Ln(8);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);

        foreach($template->form_template_sections as $s) {
            $wrappedSectionName = $this->WrapString(utf8_decode($s->name), 45);
            $lineHeight = count($wrappedSectionName) * 6;
            if($this->GetY() + $lineHeight > 270) {
                $this->AddPage();
            }

            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(237, 239, 246);
            $this->SetTextColor(29, 113, 184);
            $this->WrappedStringTableCell(85, 6, $wrappedSectionName, true);
            $this->Cell(15, $lineHeight, utf8_decode($s->score), 1, 0, 'C', true);
            $this->Cell(85, $lineHeight, '', 1, 0, 'L', true);
            $this->Ln($lineHeight);
            $this->SetTextColor(0, 0, 0);

            $this->SetFont('Arial', '', 10);
            foreach($s->form_template_fields_optionset as $f) {
                $value = '';
                foreach($this->audit->audit_field_optionset_values as $fv) {
                    if($fv->form_template_id === $f->form_template_id && $fv->form_template_field_id === $f->id) {
                        $value = $fv;
                    }
                }
                $wrappedFieldText = $this->WrapString(utf8_decode($f->text), 45);
                $wrappedFieldObs = $this->WrapString(utf8_decode($value->observations), 45);
                if(count($wrappedFieldText) > count($wrappedFieldObs)) {
                    foreach($wrappedFieldText as $i => $str) {
                        if(empty($wrappedFieldObs[$i])) {
                            $wrappedFieldObs[$i] = "";
                        } 
                    }
                } else {
                    foreach($wrappedFieldObs as $i => $str) {
                        if(empty($wrappedFieldText[$i])) {
                            $wrappedFieldText[$i] = "";
                        } 
                    }
                }
                $lineHeight = count($wrappedFieldText) * 6;
                if($this->GetY() + $lineHeight > 280) {
                    $this->AddPage();
                } 
                $this->WrappedStringTableCell(85, 6, $wrappedFieldText);
                $this->Cell(15, $lineHeight, empty($fv->form_template_optionset_value->label) ? '' : $fv->form_template_optionset_value->label, 1, 0, 'C');
                $this->WrappedStringTableCell(85, 6, $wrappedFieldObs);
                $this->Ln($lineHeight);
            }
        }
    }

    function WrapString($str, $max_length) {
        $result = [];
        $current = "";
        $words = explode(" ", strip_tags($str));
        foreach($words as $w) {
            $tmp = "$current $w";
            if(strlen($tmp) > $max_length) {
                $result[] = $current;
                $current = "";
            }
            $current = "$current $w";
        }
        $result[] = $current;
        return $result;
    }

    function WrappedStringTableCell($w, $h, $wrappedStr, $fill=false) {
        $x = $this->GetX();
        $y = $this->GetY();
        foreach($wrappedStr as $i => $str) {
            $border = 'LR';
            if($i === 0) {
                $border = 'LRT';
            }
            if($i === count($wrappedStr) - 1) {
                $border = 'LRB';
            }
            if($i === 0 && $i === count($wrappedStr) - 1) {
                $border = 1;
            }
            $this->SetX($x);
            $this->Cell($w, $h, $str, $border, 0, 'L', $fill);
            $this->Ln($h);
        }
        $this->SetXY($x+$w, $y);
    }

}

class AuditPdfComponent extends Component {

    public function generate($audit) {
        $pdf = new AuditPDF();
        $pdf->AliasNbPages();
        $pdf->audit = $audit;

        $pdf->AddPage();
        $pdf->Cover();

        foreach($audit->form_templates as $t) {
            if($t->type === 'select') {
                $pdf->SelectReport($t);
            }
        }

        return $pdf->Output('S');
    }

}
