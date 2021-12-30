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
        $values = $this->audit_field_optionset_values;
        foreach($this->form_templates as $t) {
            foreach($t->form_template_sections as $s) {
                $s->calculateSectionScore($values);
            }
        }
    }

}