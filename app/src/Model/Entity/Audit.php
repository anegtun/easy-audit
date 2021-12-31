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

}