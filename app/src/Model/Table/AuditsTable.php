<?php
namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\Table;

class AuditsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_audits');

        $this->hasMany('AuditFieldMeasureValues')
            ->setForeignKey('audit_id');

        $this->hasMany('AuditFieldOptionsetValues')
            ->setForeignKey('audit_id');

        $this->belongsTo('Customers')
            ->setForeignKey('customer_id');

        $this->belongsToMany('FormTemplates', [
            'joinTable' => 'easy_audit_audit_forms',
            'foreignKey' => 'audit_id',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'auditor_user_id',
            'propertyName' => 'auditor'
        ]);
    }

    public function findLast($templateId, $audit) {
        $audits = $this->find('all')
            ->where([
                'customer_id' => $audit->customer_id,
                'date <' => empty($audit->date) ? Time::now() : $audit->date
            ])
            ->order(['date' => 'DESC'])
            ->contain(['AuditFieldMeasureValues', 'AuditFieldOptionsetValues', 'FormTemplates'])
            ->toArray();
        foreach($audits as $audit) {
            if(in_array($templateId, $audit->getTemplateIds())) {
                return $audit;
            }
        }
        return false;
    }

}