<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditFieldValuesTable extends Table {

    public function initialize(array $config) {
        $this->setTable('easy_audit_audit_field_values');

        $this->belongsTo('FormTemplateFieldsOptionset')
            ->setForeignKey('form_template_field_id');

        $this->belongsTo('FormTemplateOptionsetValues')
            ->setForeignKey('optionset_value_id');
    }

    public function findForAudit($audit_id) {
        $values = $this->find('all')
            ->where(['audit_id' => $audit_id])
            ->contain(['FormTemplateFieldsOptionset', 'FormTemplateOptionsetValues']);
        $result = [];
        foreach($values as $v) {
            $result[$v->form_template_field_id] = $v;
        }
        return $result;
    }

    public function upsertAll($audit_id, $values, $observations) {
        $this->deleteAll(['audit_id' => $audit_id]);
        foreach($values as $k=>$v) {
            if(!empty($v)) {
                $field_value = $this->newEntity();
                $field_value->audit_id = $audit_id;
                $field_value->form_template_field_id = $k;
                $field_value->optionset_value_id = $v;
                $field_value->observations = empty($observations[$k]) ? null : $observations[$k];
                $this->save($field_value);
            }
        }
    }

}