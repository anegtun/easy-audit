<?php
namespace App\Controller\Component;

trait AuditFPDFFormSimpleTraits {

    function SimpleReport($template) {
        $this->AddPage();
        $this->H1($template->form->public_name);
        $this->SimpleReportDetails($template);
    }

    private function SimpleReportDetails($template) {
        foreach($template->form->sections as $s) {
            $this->H2($s->name);
            foreach($template->fields as $f) {
                if($f->form_section_id === $s->id) {
                    $value = $this->audit->getFieldValue($f);
                    $photos = empty($this->photos[$template->id][$f->id]) ? [] : $this->photos[$template->id][$f->id];
                    $this->SetFont('Arial', 'BI', 12);
                    $this->Cell(0, 7, utf8_decode(strip_tags($f->text)));
                    $this->Ln();
                    $this->SetFont('Arial', '', 12);
                    if(!empty($value->value)) {
                        $this->MultiCell(0, 5, utf8_decode($value->value));
                    } elseif(!empty($photos)) {
                        $this->Photos($photos);
                    } else {
                        $this->MultiCell(0, 5, '-');
                    }
                    $this->Ln();
                }
            }
        }
    }

}