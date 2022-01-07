<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditMeasureValuesTable extends Table {

    public function initialize(array $config) {
        $this->setTable('easy_audit_audit_measure_values');

        $this->belongsTo('Audits')
            ->setForeignKey('audit_id')
            ->setProperty('audit');
    }

    public function clone($template_id, $source_audit_id, $target_audit_id) {
        $values = $this->find()->where(['form_template_id' => $template_id, 'audit_id' => $source_audit_id]);
        foreach($values as $v) {
            $new_value = $this->newEntity();
            $new_value->audit_id = $target_audit_id;
            $new_value->form_template_id = $v->form_template_id;
            $new_value->item = $v->item;
            $new_value->unit = $v->unit;
            $new_value->expected = $v->expected;
            $new_value->actual = $v->actual;
            $new_value->threshold = $v->threshold;
            $new_value = $this->save($new_value);
        }
        return true;
    }

    public function upsertAll($audit_id, $template_id, $values) {
        $this->deleteAll(['audit_id' => $audit_id, 'form_template_id' => $template_id]);
        foreach($values as $v) {
            if(!empty($v['item']) || !empty($v['expected']) || !empty($v['actual']) || !empty($v['threshold'])) {
                $field_value = $this->newEntity();
                $field_value->audit_id = $audit_id;
                $field_value->form_template_id = $template_id;
                $field_value->item = $v['item'];
                $field_value->unit = $v['unit'];
                $field_value->expected = $v['expected'];
                $field_value->actual = $v['actual'];
                $field_value->threshold = $v['threshold'];
                $this->save($field_value);
            }
        }
    }

}