<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class FormTemplate extends Entity {

    public function findFieldIndex($id) {
        if(!empty($this->fields)) {
            foreach($this->fields as $i => $f) {
                if($f->id == $id) {
                    return $i;
                }
            }
        }
        return -1;
    }

    public function swapField($index1, $index2) {
        $tmp = $this->fields[$index1];
        $this->fields[$index1] = $this->fields[$index2];
        $this->fields[$index2] = $tmp;
        $this->reindexFields();
    }

    public function reindexFields() {
        usort($this->fields, function ($a,$b) {
            return strcmp($a->form_section_id, $b->form_section_id);
        });

        $count = 1;
        $currentSection = '';
        foreach($this->fields as $f) {
            if($currentSection !== $f->form_section_id) {
                $currentSection = $f->form_section_id;
                $count = 1;
            }
            $f->position = $count++;
        }
        $this->setDirty('fields', true);
    }

}