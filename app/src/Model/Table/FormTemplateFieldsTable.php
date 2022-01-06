<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Table;

class FormTemplateFieldsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_fields');

        $this->belongsTo('FormTemplates')
            ->setForeignKey('form_template_id');

        $this->belongsTo('FormTemplateOptionsets')
            ->setForeignKey('optionset_id');
    }

    public function clone($source_template_id, $target_template_id, $sections_id_map) {
        $fields = $this->find()->where(['form_template_id' => $source_template_id]);
        foreach($fields as $f) {
            $new_field = $this->newEntity();
            $new_field->form_template_id = $target_template_id;
            $new_field->form_template_section_id = $sections_id_map[$f->form_template_section_id];
            $new_field->optionset_id = $f->optionset_id;
            $new_field->position = $f->position;
            $new_field->text = $f->text;
            $this->save($new_field);
        }
        return true;
    }

    public function decrementPositionAfter($templateId, $sectionId, $startPosition, $excludedId = NULL) {
        $updateExpr = new QueryExpression('position = position - 1');
        $conditions = ['form_template_id' => $templateId, 'form_template_section_id' => $sectionId, 'position >=' => $startPosition];
        if (!empty($excludedId )) {
            $conditions['id !='] = $excludedId;
        }
        return $this->updateAll(
            [$updateExpr],
            $conditions
        );
    }

    public function decrementPositionBefore($templateId, $sectionId, $startPosition, $excludedId = NULL) {
        $updateExpr = new QueryExpression('position = position - 1');
        $conditions = ['form_template_id' => $templateId, 'form_template_section_id' => $sectionId, 'position <=' => $startPosition];
        if (!empty($excludedId )) {
            $conditions['id !='] = $excludedId;
        }
        return $this->updateAll(
            [$updateExpr],
            $conditions
        );
    }

    public function incrementPositionAfter($templateId, $sectionId, $startPosition, $excludedId = NULL) {
        $updateExpr = new QueryExpression('position = position + 1');
        $conditions = ['form_template_id' => $templateId, 'form_template_section_id' => $sectionId, 'position >=' => $startPosition];
        if (!empty($excludedId )) {
            $conditions['id !='] = $excludedId;
        }
        return $this->updateAll(
            [$updateExpr],
            $conditions
        );
    }

}