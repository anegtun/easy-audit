<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplatesTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_templates');
    }

}