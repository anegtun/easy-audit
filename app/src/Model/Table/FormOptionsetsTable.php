<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormOptionsetsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_optionsets');

        $this->hasMany('FormTemplateFields')
            ->setForeignKey('optionset_id');

        $this->hasMany('FormOptionsetValues')
            ->setForeignKey('optionset_id')
            ->setProperty('values');
    }

    public function findForSelect() {
        return $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ]);
    }

}