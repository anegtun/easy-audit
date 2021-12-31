<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Controller\Component;
use FPDF;

class AuditPDF extends FPDF {

    public $audit;

    public $history;

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

    function Cover() {
        // TODO Create image
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 50, 50, 100);
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'header.png', 50, 140, 100);
        $this->SetY(-95);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 0, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'C');
        $this->Ln(25);
        $this->SetFont('Arial', 'B', 20);
        $this->MultiCell(0, 10, utf8_decode($this->audit->customer->name), 0, 'C');
    }

    function MeasureReport($template) {
        $this->AddPage();
        $this->Title($template->name);
        $this->SubTitle('Resultados');

        $rows = [
            [
                'align' => ['C','C','C','C','C','C'],
                'bg' => [237,239,246],
                'color' => [29,113,184],
                'font' => ['Arial','B',12],
                'values' => ['Equipo a verificar', 'Dato', 'M. equipo', 'M. verificada', 'Diferencia', 'Resultado']
            ]
        ];
        foreach($this->audit->audit_field_measure_values as $m) {
            $img = WWW_ROOT . DS . 'images' . DS . 'components' . DS . ($m->isInThreshold() ? 'ok.png' : 'nok.png');
            $rows[] = ['values' => [$m->item, $m->unit, $m->expected, $m->actual, $m->calculateDifference(), ['type'=>'img', 'path'=>$img, 'width'=>5]]];
        }
        $this->Table(
            $rows,
            ['align' => ['L','C','C','C','C','C'], 'font' => ['Arial','',10], 'height' => 7, 'width' => [70,15,25,30,25,25]]
        );
    }

    function SelectReport($template) {
        $this->AddPage();
        $this->Title($template->name);
        $this->SubTitle('Evaluación de la auditoría');
        $this->SelectReportIntro($template);
        $this->AddPage();
        $this->SubTitle('Resumen de puntuaciones');
        $this->SelectReportSummary($template);
        $this->AddPage();
        $this->SubTitle('Detalles de auditoría');
        $this->SelectReportDetail($template);
    }

    private function SelectReportIntro($template) {
        $this->Paragraph('La presente Auditoría Interna Higiénico-Sanitaria se ha evaluado teniendo en cuenta distintos apartados esenciales para llevar a cabo un correcto Sistema de Autocontrol. Este sistema necesario y obligatorio para las empresas alimentarias según el Reglamento (CE) 852/2004, está basado en los principios de Análisis de Peligros y Puntos de Control Críticos (APPCC).');
        $this->Paragraph('Dichos apartados se han puntuado atendiendo tanto a obligaciones legales como a recomendaciones técnicas que se consideran claves para garantizar la seguridad en los alimentos, de acuerdo a las referencias descritas al final del presente informe.');
        $this->Paragraph('El modo de puntuación considerado es el siguiente:');
        $this->Ln(5);
        $this->Table(
            [
                ['bg' => [214, 233, 198], 'values' => ['A', 'Satisfactorio', '85 - 100']],
                ['bg' => [250, 235, 204], 'values' => ['B', 'Aceptable', '65 - 84']],
                ['bg' => [235, 204, 209], 'values' => ['C', 'Necesita mejorar', '0 - 64']]
            ],
            ['height' => 7, 'width' => [10, 50, 30], 'marginLeft' => 60]
        );
        $this->Ln(10);
        $this->Paragraph('Además de las puntuaciones de cada apartado, en el apartado de Detalles de la auditoría indicando las oportunidades de mejora detectadas de cada premisa durante la auditoría en cada uno de ellos. También se indican en este apartado las posibles No Conformidades que el auditor ha encontrado (puntuadas como C en cada premisa).');
        $this->Paragraph('Se recuerda que es posible que lo detectable en la presente auditoría pueda estar sujeto a modificaciones a corto plazo por lo que es primordial la colaboración y el trabajo constante del personal responsable para llevar a cabo un adecuado Sistema de Autocontrol.');
        $this->Paragraph('Apthisa agradece a la dirección y empleados del establecimiento su atención y colaboración durante la auditoría.');

        $this->Ln(15);
        $this->Cell(20);
        $this->SetFont('Arial', 'U', 12);
        $this->Cell(100, 0, 'Realizado en');
        $this->Cell(100, 0, 'Elaborado por');
        $this->Ln(8);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(20);
        $y = $this->GetY();
        $this->MultiCell(80, 5, utf8_decode($this->audit->customer->name), 0, 'L');
        $this->SetY($y + 2);
        $this->SetFont('Arial', '', 12);
        $this->Cell(120);
        $this->Cell(0, 0, utf8_decode($this->audit->auditor->name));
        $this->Ln(7);
        $this->Cell(120);
        $this->Cell(0, 0, utf8_decode($this->audit->auditor->position));
    }

    private function SelectReportSummary($template) {
        $rows = [];
        $headerRow = ['values' => [''], 'bg' => [237,239,246], 'color'=>[29,113,184],  'font' => ['Arial','B',10]];
        foreach($this->history as $h) {
            $headerRow['values'][] = strtoupper($h->date->i18nFormat('MMM yy'));
        }
        $rows[] = $headerRow;
        foreach($template->form_template_sections as $s) {
            $row = ['values' => [$s->name]];
            foreach($this->history as $h) {
                $row['values'][] = $h->score_section[$s->id];
            }
            $rows[] = $row;
        }
        $totalRow = ['values' => ['Total'], 'font' => ['Arial', 'B', 10]];
        foreach($this->history as $h) {
            $totalRow['values'][] = $h->score_templates[$template->id];
        }
        $rows[] = $totalRow;

        $tableConfig = [
            'align' => ['L'],
            'font' => ['Arial','',10],
            'height' => 8,
            'marginLeft' => 20,
            'width' => [165 - count($this->history) * 15]
        ];
        foreach($this->history as $h) {
            $tableConfig['align'][] = 'R';
            $tableConfig['width'][] = 15;
        }
        $this->Table($rows, $tableConfig);
        $this->Ln(20);

        $letter = 'A';
        if($this->audit->score_templates[$template->id] < 65) {
            $letter = 'C';
        } elseif($this->audit->score_templates[$template->id] < 85) {
            $letter = 'B';
        }
        $this->Table(
            [
                ['values' => ['Total', "{$this->audit->score_templates[$template->id]}%"]],
                ['values' => ['Puntuación', $letter]]
            ],
            ['font' => ['Arial', 'B', 12], 'height' => 8, 'marginLeft' => 90, 'width' => [55, 40]]
        );
    }

    private function SelectReportDetail($template) {
        $this->Table(
            [ ['bg' => [237,239,246], 'color'=>[29,113,184], 'values' => ['Requisitos', 'Pt', 'Aspectos destacables']] ],
            ['font' => ['Arial', 'B', 10], 'height' => 8, 'width' => [85,15,85]]
        );

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
            $this->Cell(85, $lineHeight, '', 1, 0, 'C', true);
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
                $observations = empty($value->observations) ? '' : $value->observations;
                $valueLabel = empty($value->form_template_optionset_value->label) ? '' : $value->form_template_optionset_value->label;
                $wrappedFieldText = $this->WrapString(utf8_decode($f->text), 45);
                $wrappedFieldObs = $this->WrapString(utf8_decode($observations), 45);
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
                $this->Cell(15, $lineHeight, $valueLabel, 1, 0, 'C');
                $this->WrappedStringTableCell(85, 6, $wrappedFieldObs);
                $this->Ln($lineHeight);
            }
        }
    }

    private function Title($title) {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(0, 20, utf8_decode($title), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(20);
    }

    private function SubTitle($title) {
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 15, utf8_decode($title), 0, 0, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Ln(20);
    }

    private function Paragraph($text) {
        $this->MultiCell(0, 5, utf8_decode($text));
        $this->Ln(5);
    }

    private function Table($rows, $config) {
        foreach($rows as $row) {
            if(!empty($config['marginLeft'])) {
                $this->SetX($config['marginLeft']);
            }
            $hasBgColor = !empty($row['bg']);
            if($hasBgColor) {
                $this->SetFillColor($row['bg'][0], $row['bg'][1], $row['bg'][2]);
            }
            $hasTextColor = !empty($row['color']);
            if($hasTextColor) {
                $this->SetTextColor($row['color'][0], $row['color'][1], $row['color'][2]);
            }
            if(!empty($config['font'])) {
                $this->SetFont($config['font'][0], $config['font'][1], $config['font'][2]);
            }
            if(!empty($row['font'])) {
                $this->SetFont($row['font'][0], $row['font'][1], $row['font'][2]);
            }
            foreach($config['width'] as $i => $w) {
                $align = 'C';
                if(!empty($config['align'][$i])) {
                    $align = $config['align'][$i];
                }
                if(!empty($row['align'][$i])) {
                    $align = $row['align'][$i];
                }
                if(is_array($row['values'][$i])) {
                    if($row['values'][$i]['type'] === 'img') {
                        $x = $this->GetX();
                        $y = $this->GetY();
                        $this->Image($row['values'][$i]['path'], $x+($w/2)-($row['values'][$i]['width']/2), $y+1, $row['values'][$i]['width']);
                        $this->Cell($w, $config['height'], '', 1);
                    }
                } else {
                    $this->Cell($w, $config['height'], utf8_decode($row['values'][$i]), 1, 0, $align, $hasBgColor);
                }
            }
            if($hasBgColor) {
                $this->SetFillColor(255, 255, 255);
            }
            if($hasTextColor) {
                $this->SetTextColor(0, 0, 0);
            }
            $this->Ln();
        }
    }

    private function WrapString($str, $max_length) {
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

    private function WrappedStringTableCell($w, $h, $wrappedStr, $fill=false) {
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

    public function generate($audit, $audits) {
        $pdf = new AuditPDF();
        $pdf->AliasNbPages();
        $pdf->audit = $audit;
        $pdf->history = $audits;

        $pdf->AddPage();
        $pdf->Cover();

        foreach($audit->form_templates as $t) {
            if($t->type === 'select') {
                $pdf->SelectReport($t);
            } elseif($t->type === 'measure') {
                $pdf->MeasureReport($t);
            }
        }

        return $pdf->Output('S');
    }

}
