<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Table;

class FormTemplateSectionsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_sections');

        $this->hasMany('FormTemplateFieldsOptionset')
            ->setForeignKey('form_template_section_id');
    }

    public function clone($source_template_id, $target_template_id) {
        $sections = $this->find()->where(['form_template_id' => $source_template_id]);
        $sections_id_map = [];
        foreach($sections as $s) {
            $new_section = $this->newEntity();
            $new_section->form_template_id = $target_template_id;
            $new_section->position = $s->position;
            $new_section->name = $s->name;
            $new_section = $this->save($new_section);
            $sections_id_map[$s->id] = $new_section->id;
        }
        return $sections_id_map;
    }

    public function decrementPositionAfter($templateId, $startPosition, $excludedId = NULL) {
        $expression = new QueryExpression('position = position - 1');
        $conditions = ['form_template_id' => $templateId, 'position >=' => $startPosition];
        if (!empty($excludedId )) {
            $conditions['id !='] = $excludedId;
        }
        return $this->updateAll(
            [$expression],
            $conditions
        );
    }

    public function decrementPositionBefore($templateId, $startPosition, $excludedId = NULL) {
        $expression = new QueryExpression('position = position - 1');
        $conditions = ['form_template_id' => $templateId, 'position <=' => $startPosition];
        if (!empty($excludedId )) {
            $conditions['id !='] = $excludedId;
        }
        return $this->updateAll(
            [$expression],
            $conditions
        );
    }

    public function incrementPositionAfter($templateId, $startPosition) {
        $expression = new QueryExpression('position = position + 1');
        return $this->updateAll(
            [$expression],
            ['form_template_id' => $templateId, 'position >=' => $startPosition]
        );
    }

}