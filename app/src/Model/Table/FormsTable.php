<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormsTable extends Table {
    
    public function initialize(array $config): void {
        $this->setTable('easy_audit_forms');

        $this->hasMany('FormSections')
            ->setForeignKey('form_id')
            ->setProperty('sections');

        $this->hasMany('FormTemplates')
            ->setForeignKey('form_id')
            ->setProperty('templates');
    }

}