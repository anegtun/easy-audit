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

    use AuditFPDFFormSelectTraits;

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

    private function getAuditDate() {
        return $this->audit->date->i18nFormat('dd MMMM yyyy');
    }

    private function getAuditShortDate() {
        $date = $this->audit->date->i18nFormat('MMMM yyyy');
        return strtoupper(substr($date,0,1)) . substr($date,1);
    }
}