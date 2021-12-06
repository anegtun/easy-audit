<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_users');

        $this->hasMany('Audits')
            ->setForeignKey('user_id');
    }

}