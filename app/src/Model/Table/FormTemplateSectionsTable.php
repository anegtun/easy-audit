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