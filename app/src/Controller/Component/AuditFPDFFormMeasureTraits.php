<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Core\Configure;
use FPDF;

trait AuditFPDFFormMeasureTraits {

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
                $diff = $m->calculateDifference();
                if($diff === null) {
                    $img = WWW_ROOT . DS . 'images' . DS . 'components' . DS . 'na.png';
                    $diff = 'N/A';
                } else {
                    $img = WWW_ROOT . DS . 'images' . DS . 'components' . DS . ($m->isInThreshold() ? 'ok.png' : 'nok.png');
                }
                $expected = $m->expected === null ? '-' : $m->expected;
                $actual = $m->actual === null ? '-' : $m->actual;
                $rows[] = ['values' => [$m->item, $m->unit, $expected, $actual, $diff, ['type'=>'img', 'path'=>$img, 'width'=>5]]];
            }
            $this->Table(
                $rows,
                ['align' => ['L','C','C','C','C','C'], 'font' => ['Arial','',10], 'height' => 7, 'width' => [70,15,25,30,25,25]]
            );
        } else {
            $this->Cell(80, 5, utf8_decode('No se han encontrado mediciones.'));
        }
    }

}