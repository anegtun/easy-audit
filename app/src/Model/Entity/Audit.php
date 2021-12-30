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

    public function getSection($section) {
        foreach($this->form_templates as $t) {
            if($t->id === $section->form_template_id) {
                foreach($t->form_template_sections as $s) {
                    if($s->id === $section->id) {
                        return $s;
                    }
                }
            }
        }
        return false;
    }

    public function getTemplate($template) {
        foreach($this->form_templates as $t) {
            if($t->id === $template->id) {
                return $t;
            }
        }
        return false;
    }

    public function calculateScores() {
        $values = $this->audit_field_optionset_values;
        foreach($this->form_templates as $t) {
            $count = 0;
            $score = 0;
            foreach($t->form_template_sections as $s) {
                $count++;
                $score += $s->calculateSectionScore($values);
            }
            $t->score = $count === 0 ? 0 : round($score / $count, 0);
        }
    }

}