<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplateSectionsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_sections');
    }

}