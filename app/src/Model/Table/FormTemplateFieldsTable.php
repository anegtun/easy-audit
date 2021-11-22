<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Table;

class FormTemplateFieldsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_fields');
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