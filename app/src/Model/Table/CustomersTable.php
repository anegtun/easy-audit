<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CustomersTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_customers');
    }

}