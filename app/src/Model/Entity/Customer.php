<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Customer extends Entity {

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

}