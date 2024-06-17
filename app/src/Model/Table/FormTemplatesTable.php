<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplatesTable extends Table {
    
    public function initialize(array $config): void {
        $this->setTable('easy_audit_form_templates');

        $this->belongsTo('Forms')
            ->setForeignKey('form_id')
            ->setProperty('form');

        $this->hasMany('FormTemplateFields')
            ->setForeignKey('form_template_id')
            ->setProperty('fields');

        $this->belongsToMany('Customers', [
            'joinTable' => 'easy_audit_customer_forms',
            'foreignKey' => 'form_template_id',
        ])->setProperty('customers');

        $this->belongsToMany('Audits', [
            'joinTable' => 'easy_audit_audit_forms',
            'foreignKey' => 'form_template_id',
        ])->setProperty('audits');
    }

}