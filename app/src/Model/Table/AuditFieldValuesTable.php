<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class AuditFieldValuesTable extends Table {

    public function initialize(array $config): void {
        $this->setTable('easy_audit_audit_field_values');

        $this->belongsTo('Audits')
            ->setForeignKey('audit_id');

        $this->belongsTo('FormTemplateFields')
            ->setForeignKey('form_template_field_id')
            ->setProperty('field');

        $this->belongsTo('FormOptionsetValues')
            ->setForeignKey('optionset_value_id')
            ->setProperty('optionset_value');
    }

    public function clone($template_id, $source_audit_id, $target_audit_id) {
        $values = $this->find()->where(['form_template_id' => $template_id, 'audit_id' => $source_audit_id]);
        foreach($values as $v) {
            $new_value = $this->newEntity([
                'audit_id' => $target_audit_id,
                'form_template_id' => $v->form_template_id,
                'form_template_field_id' => $v->form_template_field_id,
                'optionset_value_id' => $v->optionset_value_id,
                'observations' => $v->observations
            ]);
            $new_value = $this->save($new_value);
        }
        return true;
    }

    public function upsertAll($audit_id, $template_id, $values, $observations) {
        $this->deleteAll(['form_template_id' => $template_id, 'audit_id' => $audit_id]);
        foreach($values as $k=>$v) {
            if(!empty($v)) {
                $field = $this->FormTemplateFields->get($k);
                $field_value = $this->newEntity([
                    'audit_id' => $audit_id,
                    'form_template_id' => $template_id,
                    'form_template_field_id' => $k,
                    'observations' => empty($observations[$k]) ? null : $observations[$k]
                ]);
                if($field->type === 'select') {
                    $field_value->optionset_value_id = $v;
                } else {
                    $field_value->value = $v;
                }
                $this->save($field_value);
            }
        }
    }

}