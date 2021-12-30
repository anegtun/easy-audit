<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AuditInitializationComponent extends Component {
    
    public function startup() {
        $this->AuditFieldOptionsetValues = TableRegistry::getTableLocator()->get('AuditFieldOptionsetValues');
        $this->FormTemplateFieldsOptionset = TableRegistry::getTableLocator()->get('FormTemplateFieldsOptionset');
    }

    public function createDefaults($template_id, $audit_id) {
        $fields = $this->FormTemplateFieldsOptionset->find('all')
            ->where(['form_template_id' => $template_id])
            ->contain(['FormTemplateOptionsets' => ['FormTemplateOptionsetValues']]);
        foreach($fields as $f) {
            $defaultVal = $this->findDefaultValue($f);
            if($defaultVal) {
                $value = $this->AuditFieldOptionsetValues->newEntity();
                $value->audit_id = $audit_id;
                $value->form_template_id = $f->form_template_id;
                $value->form_template_field_id = $f->id;
                $value->optionset_value_id = $defaultVal->id;
                $this->AuditFieldOptionsetValues->save($value);
            }
        }
    }

    private function findDefaultValue($field) {
        foreach($field->form_template_optionset->form_template_optionset_values as $option) {
            if($option->is_default) {
                return $option;
            }
        }
        return false;
    }

}
