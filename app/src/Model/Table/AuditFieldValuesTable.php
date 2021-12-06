<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditFieldValuesTable extends Table {

    public function initialize(array $config) {
        $this->setTable('easy_audit_audit_field_values');
    }

    public function findForAudit($audit_id) {
        $values = $this->find('all')->where(['audit_id' => $audit_id]);
        $result = [];
        foreach($values as $v) {
            $result[$v->form_template_field_id] = $v->optionset_value_id;
        }
        return $result;
    }

    public function upsertAll($audit_id, $values) {
        $this->deleteAll(['audit_id' => $audit_id]);
        foreach($values as $k=>$v) {
            if(!empty($v)) {
                $field_value = $this->newEntity();
                $field_value->audit_id = $audit_id;
                $field_value->form_template_field_id = $k;
                $field_value->optionset_value_id = $v;
                $this->save($field_value);
            }
        }
    }

}