<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Audit extends Entity {

    public function getTemplateIds() {
        if(empty($this->templates)) {
            return [];
        }
        return array_map(
            function ($e) {
                return $e->id;
            },
            $this->templates);
    }

    public function getFieldOptionsetValue($field) {
        foreach($this->field_values as $fv) {
            if($fv->form_template_id === $field->form_template_id && $fv->form_template_field_id === $field->id) {
                return $fv;
            }
        }
        return false;
    }

    public function calculateScores() {
        $this->score_section = [];
        $this->score_templates = [];
        foreach($this->templates as $t) {
            $score = 0;
            $weigth = 0;
            if(!empty($t->form->sections)) {
                foreach($t->form->sections as $s) {
                    $tmp = $s->calculateSectionScore($this->field_values);
                    $this->score_section[$s->id] = $tmp;
                    $score += $tmp * $s->weigth;
                    $weigth += $s->weigth;
                }
            }
            $this->score_templates[$t->id] = $weigth === 0 ? 0 : round($score / $weigth, 0);
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