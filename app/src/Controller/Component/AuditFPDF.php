<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use Cake\Core\Configure;
use FPDF;

class AuditFPDF extends FPDF {

    public $audit;

    public $history;

    public $photos;

    public $graph_color;

    const MAX_PHOTO_HEIGHT = 90;

    const MAX_PHOTO_WIDTH = 80;

    use AuditFPDFCommonTraits;

    use AuditFPDFFormMeasureTraits;

    use AuditFPDFFormChecklistTraits;

    use AuditFPDFFormSimpleTraits;

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

    function Intro() {
        $select_template = false;
        foreach($this->audit->templates as $t) {
            if($t->form->type === 'checklist') {
                $select_template = $t;
            }
        }

        if(!empty($select_template)) {
            $this->H1($select_template->form->public_name);
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
        } else {
            $this->Ln(10);
        }

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

    private function getAuditDate() {
        return $this->audit->date->i18nFormat('dd MMMM yyyy');
    }

    private function getAuditShortDate() {
        $date = $this->audit->date->i18nFormat('MMMM yyyy');
        return strtoupper(substr($date,0,1)) . substr($date,1);
    }
}