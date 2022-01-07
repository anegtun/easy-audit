<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Core\Configure;
use FPDF;

trait AuditFPDFFormSimpleTraits {

    function SimpleReport($template) {
        $this->AddPage();
        $this->H1($template->form->public_name);
        /*$this->SelectReportSummaryTable($template);
        $this->Ln(10);
        $this->SelectReportSummaryGraph($template);
        $this->Ln(20);
        $this->SelectReportSummaryResult($template);
        $this->AddPage();
        $this->H2('Detalles de auditoría');
        $this->SelectReportDetail($template);*/
    }

    /*private function SelectReportSummaryTable($template) {
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
    }*/

}