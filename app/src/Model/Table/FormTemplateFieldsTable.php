<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Table;

class FormTemplateFieldsTable extends Table {
    
    public function initialize(array $config): void {
        $this->setTable('easy_audit_form_template_fields');

        $this->belongsTo('FormSections')
            ->setForeignKey('form_section_id')
            ->setProperty('section');

        $this->belongsTo('FormTemplates')
            ->setForeignKey('form_template_id')
            ->setProperty('template');

        $this->belongsTo('FormOptionsets')
            ->setForeignKey('optionset_id')
            ->setProperty('optionset');
    }

    public function clone($source_template_id, $target_template_id, $sections_id_map = null) {
        $fields = $this->find()->where(['form_template_id' => $source_template_id]);
        foreach($fields as $f) {
            $new_field = $this->newEntity([
                'form_template_id' => $target_template_id,
                'form_section_id' => empty($sections_id_map) ? $f->form_section_id : $sections_id_map[$f->form_section_id],
                'optionset_id' => $f->optionset_id,
                'position' => $f->position,
                'text' => $f->text,
                'type' => $f->type
            ]);
            $this->save($new_field);
        }
        return true;
    }

}