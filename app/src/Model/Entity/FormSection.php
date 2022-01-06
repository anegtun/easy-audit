<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class FormSection extends Entity {

    public function calculateSectionScore($field_values) {
        $count = 0;
        $score = 0;
        foreach($field_values as $f) {
            if($f->field->form_section_id === $this->id) {
                $count++;
                $score += $f->optionset_value->value_numeric;
            }
        }
        return $count === 0 ? 0 : round(100 * ($score / $count), 1);
    }

}