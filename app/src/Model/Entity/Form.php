<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Form extends Entity {

    public function findSectionIndex($id) {
        if(!empty($this->sections)) {
            foreach($this->sections as $i => $s) {
                if($s->id == $id) {
                    return $i;
                }
            }
        }
        return -1;
    }

    public function swapSection($index1, $index2) {
        $tmp = $this->sections[$index1];
        $this->sections[$index1] = $this->sections[$index2];
        $this->sections[$index2] = $tmp;
        $this->reindexSections();
    }

    public function reindexSections() {
        $count = 1;
        foreach($this->sections as $s) {
            $s->position = $count++;
        }
    }

}