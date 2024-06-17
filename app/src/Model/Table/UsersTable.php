<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table {
    
    public function initialize(array $config): void {
        $this->setTable('easy_audit_users');

        $this->hasMany('Audits')
            ->setForeignKey('auditor_user_id')
            ->setProperty('audits');
    }

}