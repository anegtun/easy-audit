<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Table;

class FormSectionsTable extends Table {
    
    public function initialize(array $config) {
        $this->setTable('easy_audit_form_sections');

        $this->belongsTo('Forms')
            ->setForeignKey('form_id')
            ->setProperty('form');
    }

    public function clone($source_form_id, $target_form_id) {
        $sections = $this->find()->where(['form_id' => $source_form_id]);
        $sections_id_map = [];
        foreach($sections as $s) {
            $new_section = $this->newEntity();
            $new_section->form_id = $target_form_id;
            $new_section->position = $s->position;
            $new_section->name = $s->name;
            $new_section->weigth = $s->weigth;
            $new_section = $this->save($new_section);
            $sections_id_map[$s->id] = $new_section->id;
        }
        return $sections_id_map;
    }

}