<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Controller\Component;
use Cake\Core\Configure;
use FPDF;

class AuditPDF extends FPDF {

    public $audit;

    public $history;

    public $photos;

    public $graph_color;

    const MAX_PHOTO_HEIGHT = 90;

    const MAX_PHOTO_WIDTH = 80;

    function Header() {
        $this->SetTextColor(0, 0, 0);
        if($this->PageNo() > 1) {
            $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'main.png', 10, 8, 25);
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'R');
            $this->Ln(5);
            $this->Cell(0, 5, utf8_decode($this->audit->customer->name), 0, 0, 'R');
            $this->Ln(5);
            $this->Cell(0, 5, utf8_decode($this->getAuditShortDate()), 0, 0, 'R');
            $this->Ln(15);
        }
    }

    function Footer() {
        $this->SetTextColor(0, 0, 0);
        if($this->PageNo() > 1) {
            $this->SetY(-15);
            $this->SetFont('Arial', '', 10);
            $this->Cell(95, 5, utf8_decode(Configure::read('easy-audit.company.name-full')));
            $this->Cell(95, 5, utf8_decode(Configure::read('easy-audit.company.motto')), 0, 0, 'R');
            $this->Ln();
            $this->Cell(95, 5, utf8_decode(Configure::read('easy-audit.company.phone').' / '. Configure::read('easy-audit.company.website')));
            $this->Cell(98, 5, utf8_decode('Página ').$this->PageNo().' de {nb}', 0, 0, 'R');
        }
    }

    function Cover() {
        $this->Image(WWW_ROOT . DS . 'images' . DS . 'logo' . DS . 'report-cover.png', 50, 30, 110);
        $this->SetY(-110);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 0, utf8_decode('Informe Auditoría Higiénico-Sanitaria'), 0, 0, 'C');
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 25);
        $this->MultiCell(0, 10, utf8_decode($this->audit->customer->name), 0, 'C');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 10, utf8_decode($this->getAuditDate()), 0, 0, 'C');
    }

    function MeasureReport($template) {
        $this->AddPage();
        $this->H1($template->form->public_name);
        $this->H2('Resultados');

        if(!empty($this->audit->measure_values)) {
            $rows = [
                [
                    'align' => ['C','C','C','C','C','C'],
                    'bg' => [237,239,246],
                    'color' => [29,113,184],
                    'font' => ['Arial','B',12],
                    'values' => ['Equipo a verificar', 'Dato', 'M. equipo', 'M. verificada', 'Diferencia', 'Resultado']
                ]
            ];
            foreach($this->audit->measure_values as $m) {
                $img = WWW_ROOT . DS . 'images' . DS . 'components' . DS . ($m->isInThreshold() ? 'ok.png' : 'nok.png');
                $rows[] = ['values' => [$m->item, $m->unit, $m->expected, $m->actual, $m->calculateDifference(), ['type'=>'img', 'path'=>$img, 'width'=>5]]];
            }
            $this->Table(
                $rows,
                ['align' => ['L','C','C','C','C','C'], 'font' => ['Arial','',10], 'height' => 7, 'width' => [70,15,25,30,25,25]]
            );
        } else {
            $this->Cell(80, 5, utf8_decode('No se han encontrado mediciones.'));
        }
    }

    function SelectReport($template) {
        $this->AddPage();
        $this->H1($template->form->public_name);
        $this->SelectReportIntro($template);
        $this->AddPage();
        $this->H2('Resumen de puntuaciones');
        $this->SelectReportSummaryTable($template);
        $this->Ln(10);
        $this->SelectReportSummaryGraph($template);
        $this->Ln(20);
        $this->SelectReportSummaryResult($template);
        $this->AddPage();
        $this->H2('Detalles de auditoría');
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
        $this->Paragraph('Además de las puntuaciones de cada apartado, en el apartado Detalles de la auditoría se detallan los aspectos que han mostrado desviaciones en la auditoría, los cuales se han puntuado como una B o C, acompañándonos de una una observación y/o imagen según corresponda. Se recomienda que las desviaciones puntuadas con C, se las de asistencia inmediata para su subsanación inmediata o mejora.');
        $this->Paragraph('Se recuerda que los resultados del presente informe sólo corresponden con las oportunidades de mejora detectadas en el momento de la auditoría, pudiendo variar con el tiempo.');
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
        $this->SetDefaultFont();
        $this->Cell(120);
        $this->Cell(0, 0, utf8_decode($this->audit->auditor->name));
        $this->Ln(7);
        $this->Cell(120);
        $this->Cell(0, 0, utf8_decode($this->audit->auditor->position));
    }

    private function SelectReportSummaryTable($template) {
        $history_to_show = count($this->history) > 6 ? array_slice($this->history, -6) : $this->history;
        $sectionNameMaxLength = 105 - count($history_to_show) * 10;
        $sectionNameMaxWidth = 175 - count($history_to_show) * 15;
        $rows = [];
        $headerRow = ['values' => [''], 'bg' => [237,239,246], 'color'=>[29,113,184],  'font' => ['Arial','B',10]];
        foreach($history_to_show as $h) {
            $headerRow['values'][] = strtoupper($h->date->i18nFormat('MMM yy'));
        }
        $rows[] = $headerRow;
        foreach($template->form->sections as $s) {
            $sectionName = $s->name;
            if(strlen($sectionName) > $sectionNameMaxLength) {
                $sectionName = substr($s->name, 0, 110 - count($history_to_show) * 10).'...';
            }
            $sectionName .= " ($s->weigth)";
            $row = ['values' => [$sectionName]];
            foreach($history_to_show as $h) {
                $row['values'][] = $h->score_section[$s->id];
            }
            $rows[] = $row;
        }
        $totalRow = ['values' => ['Total'], 'font' => ['Arial','B',10]];
        foreach($history_to_show as $h) {
            $totalRow['values'][] = $h->score_templates[$template->id];
        }
        $rows[] = $totalRow;

        $tableConfig = [
            'align' => ['L'],
            'font' => ['Arial','',10],
            'height' => 8,
            'marginLeft' => 20,
            'width' => [$sectionNameMaxWidth]
        ];
        foreach($history_to_show as $h) {
            $tableConfig['align'][] = 'R';
            $tableConfig['width'][] = 15;
        }
        $this->Table($rows, $tableConfig);
    }

    private function SelectReportSummaryGraph($template) {
        $history_to_show = count($this->history) > 12 ? array_slice($this->history, -12) : $this->history;
        $maxHeight = 40;
        $maxWidth = 175;
        $marginLeft = 20;
        $colWidth = min([20, $maxWidth / count($history_to_show) / 2]);
        $gap = ($maxWidth - $colWidth * count($history_to_show)) / (2 * count($history_to_show));
        $y = $this->GetY();
        $this->SetFillColor($this->graph_color[0], $this->graph_color[1], $this->graph_color[2]);
        foreach($history_to_show as $i => $h) {
            $x = $marginLeft + $gap + $i * ($colWidth + 2 * $gap);
            $score = $h->score_templates[$template->id];
            $height = $maxHeight * $score / 100;
            $this->SetXY($x, $y);
            $this->Cell($colWidth, 10, $score, 0, 0, 'C');
            $this->Ln();
            $this->SetX($x);
            $this->Cell($colWidth, $maxHeight - $height);
            $this->Ln();
            $this->SetX($x);
            $this->Cell($colWidth, $height, '', 0, 0, '', true);
        }
        $this->SetFillColor(255, 255, 255);
        $this->Ln();
        $this->SetX($marginLeft);
        $this->Cell($maxWidth, 5, '', 'T');
        $this->Ln();
        $this->SetX($marginLeft);
        $this->SetFont('Arial', 'B', count($history_to_show) > 6 ? 8 : 10);
        foreach($history_to_show as $i => $h) {
            $this->Cell($colWidth + 2 * $gap, 3, strtoupper($h->date->i18nFormat('MMM yy')), 0, 0, 'C');
        }
        $this->SetFont('Arial', '', 12);
    }

    private function SelectReportSummaryResult($template) {
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
            ['font' => ['Arial','B',12], 'height' => 8, 'marginLeft' => 90, 'width' => [55,40]]
        );
    }

    private function SelectReportDetail($template) {
        $data = [];
        foreach($template->form->sections as $s) {
            $section = ['title' => $s->name, 'fields' => []];
            foreach($template->fields as $f) {
                if($f->form_section_id === $s->id) {
                    $value = $this->audit->getFieldValue($f);
                    $photos = empty($this->photos[$template->id][$f->id]) ? [] : $this->photos[$template->id][$f->id];
                    if($value && (empty($value->optionset_value->is_default) || !empty($value->observations) || !empty($photos))) {
                        $section['fields'][] = [
                            'warn' => $value->optionset_value->color === 'danger',
                            'text' => $f->text,
                            'result' => empty($value->optionset_value->label) ? '' : $value->optionset_value->label,
                            'observations' => empty($value->observations) ? '-' : $value->observations,
                            'photos' => $photos
                        ];
                    }
                }
            }
            $data[] = $section;
        }
        $hasData = false;
        foreach($data as $e) {
            if(!empty($e['fields'])) {
                $hasData = true;
                $this->H3($e['title']);
                foreach($e['fields'] as $f) {
                    $this->SetFont('Arial', 'BI', 12);
                    $this->MultiCell(0, 5, utf8_decode(strip_tags($f['text'])));
                    $this->Ln(2);
                    $this->SetFont('Arial', 'U', 12);
                    $this->Cell(35, 5, utf8_decode('Puntuación'));
                    $this->Cell(35, 5, utf8_decode('Observaciones'));
                    $this->Ln(7);
                    $this->SetFont('Arial', '', 12);
                    if($f['warn']) {
                        $this->SetFont('Arial', 'B', 14);
                        $this->SetTextColor(169, 68, 66);
                    }
                    $this->Cell(35, 5, utf8_decode($f['result']));
                    $this->SetFont('Arial', '', 12);
                    $this->SetTextColor(0, 0, 0);
                    $this->MultiCell(0, 5, utf8_decode(print_r($f['observations'], true)));
                    if(!empty($f['photos'])) {
                        $this->Ln(5);
                        $y = $this->GetY();
                        $rowMaxH = 0;
                        foreach($f['photos'] as $i => $photo) {
                            $path = WWW_ROOT . DS . $photo;
                            $sizes = $this->CalculateImageSize($path);
                            $newX = $this->GetX() + $sizes->trgW + 10;
                            if($newX > 210) {
                                $this->Ln($rowMaxH + 10);
                                $rowMaxH = 0;
                                $newX = $this->GetX() + $sizes->trgW + 10;
                            }
                            $newY = $this->GetY() + $sizes->trgH + 10;
                            if($newY > 290) {
                                $this->AddPage();
                            }
                            $this->Image(WWW_ROOT . DS . $photo, $this->GetX(), $this->GetY(), $sizes->trgW, $sizes->trgH);
                            $this->SetX($newX);
                            $rowMaxH = max($rowMaxH, $sizes->trgH);
                        }
                        $this->Ln($rowMaxH + 10);
                    }
                    $this->Ln(10);
                }
            }
        }
        if(!$hasData) {
            $this->Cell(80, 5, utf8_decode('No se han encontrado evidencias.'));
        }
    }

    private function CalculateImageSize($path) {
        list($photoW, $photoH) = getimagesize($path);
        $largest = max($photoW, $photoH);
        $ratioW = $photoW > self::MAX_PHOTO_WIDTH ? self::MAX_PHOTO_WIDTH / $photoW : 1;
        $ratioH = $photoH > self::MAX_PHOTO_HEIGHT ? self::MAX_PHOTO_HEIGHT / $photoH : 1;
        $ratio = min($ratioW, $ratioH);
        return (object) [
            'srcW' => $photoW,
            'srcH' => $photoH,
            'trgW' => $photoW * $ratio,
            'trgH' => $photoH * $ratio,
        ];
    }

    private function H1($str) {
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(0, 20, utf8_decode($str), 0, 0, 'C');
        $this->SetDefaultFont();
        $this->Ln(20);
    }

    private function H2($str) {
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 15, utf8_decode($str), 0, 0, 'C');
        $this->SetDefaultFont();
        $this->Ln(20);
    }

    private function H3($str) {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(29, 113, 184);
        $this->Cell(0, 15, utf8_decode($str));
        $this->SetDefaultFont();
        $this->Ln(20);
    }

    private function Paragraph($text) {
        $this->MultiCell(0, 5, utf8_decode($text));
        $this->Ln(5);
    }

    private function SetDefaultFont() {
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
    }

    private function getAuditDate() {
        return $this->audit->date->i18nFormat('dd MMMM yyyy');
    }

    private function getAuditShortDate() {
        $date = $this->audit->date->i18nFormat('MMMM yyyy');
        return strtoupper(substr($date,0,1)) . substr($date,1);
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
}

class AuditPdfComponent extends Component {

    public function generate($audit, $audits, $images) {
        $pdf = new AuditPDF();
        $pdf->audit = $audit;
        $pdf->history = $audits;
        $pdf->photos = $images;
        $pdf->graph_color = sscanf(Configure::read('easy-audit.report.graph-color'), "#%02x%02x%02x");

        $pdf->AliasNbPages();
        $pdf->SetTitle(utf8_decode($audit->getReportName()));
        $pdf->AddPage();
        $pdf->Cover();

        foreach($audit->templates as $t) {
            if($t->form->type === 'select') {
                $pdf->SelectReport($t);
            } elseif($t->form->type === 'measure') {
                $pdf->MeasureReport($t);
            }
        }

        return $pdf->Output('S');
    }

}
