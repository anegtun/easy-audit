<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_audits');

        $this->belongsTo('Customers')
            ->setForeignKey('customer_id');

        $this->belongsTo('FormTemplates')
            ->setForeignKey('form_template_id');

        $this->belongsTo('Users', [
            'foreignKey' => 'auditor_user_id',
            'propertyName' => 'auditor'
        ]);
    }

}