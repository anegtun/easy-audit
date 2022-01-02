<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Audit extends Entity {

    public function getTemplateIds() {
        if(empty($this->form_templates)) {
            return [];
        }
        return array_map(
            function ($e) {
                return $e->id;
            },
            $this->form_templates);
    }

    public function getFieldOptionsetValue($field) {
        foreach($this->audit_field_optionset_values as $fv) {
            if($fv->form_template_id === $field->form_template_id && $fv->form_template_field_id === $field->id) {
                return $fv;
            }
        }
        return false;
    }

    public function calculateScores() {
        $this->score_section = [];
        $this->score_templates = [];
        foreach($this->form_templates as $t) {
            $count = 0;
            $score = 0;
            foreach($t->form_template_sections as $s) {
                $count++;
                $tmp = $s->calculateSectionScore($this->audit_field_optionset_values);
                $this->score_section[$s->id] = $tmp;
                $score += $tmp;
            }
            $this->score_templates[$t->id] = $count === 0 ? 0 : round($score / $count, 0);
        }
    }

    public function getReportName() {
        $date = $this->date->i18nFormat('yyyy-MM');
        return __('Audit')." {$this->customer->name} - $date";
    }

    public function getReportFilename() {
        return $this->getReportName().'.pdf';
    }

}