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
            if($t->form->type === 'select') {
                $pdf->SelectReport($t);
            } elseif($t->form->type === 'measure') {
                $pdf->MeasureReport($t);
            }
        }

        return $pdf->Output('S');
    }

}
