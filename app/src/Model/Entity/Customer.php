<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Customer extends Entity {

    public function getTemplateIds() {
        return array_map(
            function ($e) {
                return $e->id;
            },
            $this->form_templates);
    }

}