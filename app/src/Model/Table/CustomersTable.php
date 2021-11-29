<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CustomersTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_customers');
        $this->belongsToMany('FormTemplates', [
            'joinTable' => 'easy_audit_customer_forms',
            'foreignKey' => 'customer_id',
        ]);
    }

}