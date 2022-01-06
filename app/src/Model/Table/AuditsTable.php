<?php
namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\Table;

class AuditsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_audits');

        $this->hasMany('AuditFieldMeasureValues')
            ->setForeignKey('audit_id')
            ->setProperty('measure_values');

        $this->hasMany('AuditFieldOptionsetValues')
            ->setForeignKey('audit_id')
            ->setProperty('field_values');

        $this->belongsTo('Customers')
            ->setForeignKey('customer_id');

        $this->belongsToMany('FormTemplates', [
            'joinTable' => 'easy_audit_audit_forms',
            'foreignKey' => 'audit_id',
        ])
        ->setProperty('templates');

        $this->belongsTo('Users', [
            'foreignKey' => 'auditor_user_id',
            'propertyName' => 'auditor'
        ]);
    }

    public function getComplete($id) {
        $audit = $this->get($id, [ 'contain' => [
            'AuditFieldMeasureValues' => [ 'sort' => 'item' ],
            'AuditFieldOptionsetValues' => [
                'FormTemplateFields' => [ 'FormSections' ],
                'FormOptionsetValues'
            ],
            'Customers',
            'FormTemplates' => [
                'sort' => 'FormTemplates.name',
                'Forms' => [
                    'FormSections' => [ 'sort' => 'FormSections.position' ]
                ],
                'FormTemplateFields' => [ 'sort' => 'FormTemplateFields.position' ]
            ],
            'Users'
        ]]);
        $audit->calculateScores();
        return $audit;
    }

    public function findHistory($audit) {
        $audits = $this->find('all')
            ->where([
                'customer_id' => $audit->customer_id,
                'date <= ' => $audit->date
            ])
            ->order('date')
            ->contain([
                'AuditFieldMeasureValues' => [ 'sort' => 'item' ],
                'AuditFieldOptionsetValues' => [
                    'FormTemplateFields' => [ 'FormSections' ],
                    'FormOptionsetValues'
                ],
                'FormTemplates' => [
                    'sort' => 'FormTemplates.name',
                    'Forms' => [
                        'FormSections' => [ 'sort' => 'FormSections.position' ]
                    ],
                    'FormTemplateFields' => [ 'sort' => 'FormTemplateFields.position' ]
                ],
            ]);
        foreach($audits as $a) {
            $a->calculateScores();
        }
        return $audits;
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