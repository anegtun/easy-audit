<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditFieldMeasureValuesTable extends Table {

    public function initialize(array $config) {
        $this->setTable('easy_audit_audit_field_measure_values');
    }

    public function findForAudit($audit_id) {
        $values = $this->find('all')
            ->where(['audit_id' => $audit_id])
            ->order(['item' => 'ASC']);
        $result = [];
        foreach($values as $v) {
            if(empty($result[$v->form_template_id])) {
                $result[$v->form_template_id] = [];
            }
            $result[$v->form_template_id][] = $v;
        }
        return $result;
    }

    public function upsertAll($audit_id, $template_id, $values) {
        $this->deleteAll(['audit_id' => $audit_id, 'form_template_id' => $template_id]);
        foreach($values as $v) {
            if(!empty($v['item']) || !empty($v['expected']) || !empty($v['actual']) || !empty($v['threshold'])) {
                $field_value = $this->newEntity();
                $field_value->audit_id = $audit_id;
                $field_value->form_template_id = $template_id;
                $field_value->item = $v['item'];
                $field_value->expected = $v['expected'];
                $field_value->actual = $v['actual'];
                $field_value->threshold = $v['threshold'];
                $this->save($field_value);
            }
        }
    }

}