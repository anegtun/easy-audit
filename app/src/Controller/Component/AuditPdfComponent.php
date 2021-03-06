<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use App\Controller\Component\AuditFPDF;

class AuditPdfComponent extends Component {

    public function generate($audit, $audits, $images) {
        $pdf = new AuditFPDF();
        $pdf->audit = $audit;
        $pdf->history = $audits;
        $pdf->photos = $images;
        $pdf->graph_color = sscanf(Configure::read('easy-audit.report.graph-color'), "#%02x%02x%02x");

        $pdf->AliasNbPages();
        $pdf->SetTitle(utf8_decode($audit->getReportName()));
        $pdf->AddPage();
        $pdf->Cover();
        $pdf->AddPage();
        $pdf->Intro();

        foreach($audit->templates as $t) {
            switch($t->form->type) {
                case 'checklist':
                    $pdf->ChecklistReport($t);
                    break;
                case 'measure':
                    $pdf->MeasureReport($t);
                    break;
                case 'simple':
                    $pdf->SimpleReport($t);
                    break;
            }
        }

        return $pdf->Output('S');
    }

}
