<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_forms');

        /*$this->hasMany('FormTemplateSections')
            ->setForeignKey('form_template_id');

        $this->hasMany('FormTemplateFieldsOptionset')
            ->setForeignKey('form_template_id');

        $this->belongsToMany('Customers', [
            'joinTable' => 'easy_audit_customer_forms',
            'foreignKey' => 'form_template_id',
        ]);

        $this->belongsToMany('Audits', [
            'joinTable' => 'easy_audit_audit_forms',
            'foreignKey' => 'form_template_id',
        ]);*/
    }

}