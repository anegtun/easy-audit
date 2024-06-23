<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AuditInitializationComponent extends Component {
    
    public function startup() {
        $this->AuditFieldValues = TableRegistry::getTableLocator()->get('AuditFieldValues');
        $this->FormTemplateFields = TableRegistry::getTableLocator()->get('FormTemplateFields');
    }

    public function createDefaults($template_id, $audit_id) {
        $fields = $this->FormTemplateFields->find('all')
            ->where(['form_template_id' => $template_id])
            ->contain(['FormOptionsets' => ['FormOptionsetValues']]);
        foreach($fields as $f) {
            $defaultVal = $this->findDefaultValue($f);
            if($defaultVal) {
                $value = $this->AuditFieldValues->newEntity([
                    'audit_id' => $audit_id,
                    'form_template_id' => $f->form_template_id,
                    'form_template_field_id' => $f->id,
                    'optionset_value_id' => $defaultVal->id
                ]);
                $this->AuditFieldValues->save($value);
            }
        }
    }

    private function findDefaultValue($field) {
        if(!empty($field->optionset)) {
            foreach($field->optionset->values as $option) {
                if($option->is_default) {
                    return $option;
                }
            }
        }
        return false;
    }

}
