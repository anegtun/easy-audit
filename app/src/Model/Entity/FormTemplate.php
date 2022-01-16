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

    public function addField($field, $sectionId, $position) {
        if(empty($position)) {
            echo "<pre>"; print_r($field); echo "</pre>";
            $this->fields[] = $field;
        } else {
            $tmp = [];
            foreach($this->fields as $i => $f) {
                if($f->form_section_id == $sectionId && $f->position == $position) {
                    $tmp[] = $field;
                }
                $tmp[] = $f;
            }
            $this->fields = $tmp;
        }
        $this->reindexFields();
    }

    public function reindexFields() {
        $count = [];
        foreach($this->fields as $f) {
            if(empty($count[$f->form_section_id])) {
                $count[$f->form_section_id] = 1;
            }
            $f->position = $count[$f->form_section_id]++;
        }
        $this->setDirty('fields', true);
    }

}