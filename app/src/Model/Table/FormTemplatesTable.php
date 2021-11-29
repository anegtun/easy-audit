<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplatesTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_templates');
        $this->hasMany('FormTemplateSections')
            ->setForeignKey('form_template_id');
        $this->hasMany('FormTemplateFields')
            ->setForeignKey('form_template_id');
        $this->belongsToMany('Customers', [
            'joinTable' => 'easy_audit_customer_forms',
            'foreignKey' => 'form_template_id',
        ]);
    }

}