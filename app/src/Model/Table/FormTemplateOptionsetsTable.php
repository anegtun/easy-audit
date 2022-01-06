<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplateOptionsetsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_optionsets');

        $this->hasMany('FormTemplateFields')
            ->setForeignKey('optionset_id');

        $this->hasMany('FormTemplateOptionsetValues')
            ->setForeignKey('optionset_id');
    }

    public function findForSelect() {
        return $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ]);
    }

}