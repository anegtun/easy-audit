<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplateFieldsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_fields');
    }

}