<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class FormTemplateSection extends Entity {

    public function calculateSectionScore($field_values) {
        $count = 0;
        $score = 0;
        foreach($field_values as $f) {
            if($f->form_template_fields_optionset->form_template_section_id === $this->id) {
                $count++;
                $score += $f->form_template_optionset_value->value_numeric;
            }
        }
        $this->score = $count === 0 ? 0 : round(100 * ($score / $count), 1);
    }

}