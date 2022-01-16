<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Audit extends Entity {

    public function getFormIds() {
        if(empty($this->templates)) {
            return [];
        }
        return array_map(
            function ($e) {
                return $e->form->id;
            },
            $this->templates);
    }

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

    public function getFieldValue($field) {
        foreach($this->field_values as $fv) {
            if($fv->form_template_id === $field->form_template_id && $fv->form_template_field_id === $field->id) {
                return $fv;
            }
        }
        return false;
    }

    public function calculateScores() {
        $this->score_section = [];
        $this->score_form = [];
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
            $this->score_form[$t->form->id] = $weigth === 0 ? 0 : round($score / $weigth, 0);
        }
    }

    public function sortMeasureValues() {
        usort($this->measure_values, function($a, $b) {
            preg_match_all('/^(\d+)/', $a->item, $match_a);
            preg_match_all('/^(\d+)/', $b->item, $match_b);
            if(empty($match_a[0]) && !empty($match_b[0])) {
                return 1;
            }
            if(!empty($match_a[0]) && empty($match_b[0])) {
                return -1;
            }
            if(!empty($match_a[0]) && !empty($match_b[0])) {
                return $match_a[0][0] - $match_b[0][0];
            }
            return strcmp($a->item, $b->item);
        });
    }

    public function getReportName() {
        $date = $this->date->i18nFormat('yyyy-MM');
        return __('Audit')." {$this->customer->name} - $date";
    }

    public function getReportFilename() {
        return $this->getReportName().'.pdf';
    }

}