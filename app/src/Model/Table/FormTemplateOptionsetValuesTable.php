<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FormTemplateOptionsetValuesTable extends Table {

    public function initialize(array $config) {
        $this->setTable('easy_audit_form_template_optionset_values');
    }

    public function findAllByOptionset() {
        $all_values = $this->find('all');
        $result = [];
        foreach ($all_values as $o) {
            if(empty($result[$o->optionset_id])) {
                $result[$o->optionset_id] = [];
            }
            $result[$o->optionset_id][] = $o;
        }
        return $result;
    }

}