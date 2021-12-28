<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\FormTemplateTypes;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class FormTemplatesController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->FormTemplateTypes = new FormTemplateTypes();
        $this->FormTemplateFieldsOptionset = TableRegistry::getTableLocator()->get('FormTemplateFieldsOptionset');
        $this->FormTemplateOptionsets = TableRegistry::getTableLocator()->get('FormTemplateOptionsets');
        $this->FormTemplateSections = TableRegistry::getTableLocator()->get('FormTemplateSections');
    }

    public function index() {
        $template_types = $this->FormTemplateTypes->getAll();
        $templates = $this->FormTemplates->find();
        $this->set(compact('templates', 'template_types'));
    }

    public function save() {
        $template = $this->FormTemplates->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $template = $this->FormTemplates->patchEntity($template, $this->request->getData());
            if ($this->FormTemplates->save($template)) {
                $this->Flash->success(__('Template created.'));
                return $this->redirect(['action'=>'index']);
            }
            $this->Flash->error(__('Error saving template.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function clone() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $old_template = $this->FormTemplates->get($data['id']);
            if(!empty($data['name_old'])) {
                $old_template->name = $data['name_old'];
                $this->FormTemplates->save($old_template);
            }

            $new_template = $this->FormTemplates->newEntity();
            $new_template->name = $data['name'];
            $new_template->type = $old_template->type;
            $new_template = $this->FormTemplates->save($new_template);

            $sections = $this->FormTemplateSections->find()->where(['form_template_id' => $data['id']]);
            $sections_id_map = [];
            foreach($sections as $s) {
                $new_section = $this->FormTemplateSections->newEntity();
                $new_section->form_template_id = $new_template->id;
                $new_section->position = $s->position;
                $new_section->name = $s->name;
                $new_section = $this->FormTemplateSections->save($new_section);
                $sections_id_map[$s->id] = $new_section->id;
            }

            $allFields = $this->FormTemplateFieldsOptionset->find()->where(['form_template_id' => $data['id']]);
            foreach($allFields as $f) {
                $new_field = $this->FormTemplateFieldsOptionset->newEntity();
                $new_field->form_template_id = $new_template->id;
                $new_field->form_template_section_id = $sections_id_map[$f->form_template_section_id];
                $new_field->optionset_id = $f->optionset_id;
                $new_field->position = $f->position;
                $new_field->text = $f->text;
                $this->FormTemplateFieldsOptionset->save($new_field);
            }

            $this->Flash->success(__('Template created.'));
            return $this->redirect(['action'=>'index']);
        }
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $template = $this->FormTemplates->get($id);
        if($this->FormTemplates->delete($template)) {
            $this->Flash->success(__('Template deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting template.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function detail($id) {
        $template = $this->FormTemplates->get($id, [ 'contain' => ['Customers'] ]);
        $optionsets = $this->FormTemplateOptionsets->findForSelect();
        $sections = $this->FormTemplateSections->find()
            ->where(['form_template_id' => $id])
            ->order(['position' => 'ASC'])
            ->contain(['FormTemplateFieldsOptionset' => ['sort' => ['FormTemplateFieldsOptionset.position' => 'ASC']]]);
        $allFields = $this->FormTemplateFieldsOptionset->find()
            ->where(['form_template_id' => $id])
            ->order(['position' => 'ASC']);
        $this->set(compact('template', 'optionsets', 'sections', 'allFields'));
    }

    public function saveSection() {
        $formData = $this->request->getData();
        $templateId = $formData['form_template_id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($formData['id'])) {
                $section = $this->FormTemplateSections->newEntity();
                $section = $this->FormTemplateSections->patchEntity($section, $formData);
                $count = $this->FormTemplateSections->find()
                    ->where(['form_template_id' => $templateId])
                    ->count();
                if(empty($section->position) || $section->position > $count) {
                    $section->position = $count + 1;
                }
                $this->FormTemplateSections->incrementPositionAfter($templateId, $section->position);
            } else {
                $section = $this->FormTemplateSections->get($formData['id']);
                unset($formData['position']);
                $section = $this->FormTemplateSections->patchEntity($section, $formData);
            }
            if ($this->FormTemplateSections->save($section)) {
                $this->Flash->success(__('Section created.'));
            } else {
                $this->Flash->error(__('Error saving section.'));
            }
        }
        return $this->redirect(['action'=>'detail', $templateId]);
    }

    public function deleteSection($id) {
        $section = $this->FormTemplateSections->get($id);
        $this->FormTemplateSections->decrementPositionAfter($section->form_template_id, $section->position);
        if($this->FormTemplateSections->delete($section)) {
            $this->Flash->success(__('Section deleted successfully.'));
        } else {
            $this->Flash->error(__('Section deleting field.'));
        }
        return $this->redirect(['action'=>'detail', $section->form_template_id]);
    }

    public function moveSectionUp($id) {
        $section = $this->FormTemplateSections->get($id);
        if($section->position > 1) {
            $this->FormTemplateSections->incrementPositionAfter($section->form_template_id, $section->position - 1, $section->id);
            $section->position--;
            $this->FormTemplateSections->save($section);
        }
        return $this->redirect(['action'=>'detail', $section->form_template_id]);
    }

    public function moveSectionDown($id) {
        $section = $this->FormTemplateSections->get($id);
        $count = $this->FormTemplateSections->find()
                ->where(['form_template_id' => $section->form_template_id])
                ->count();
        if($section->position < $count) {
            $this->FormTemplateSections->decrementPositionBefore($section->form_template_id, $section->position + 1, $section->id);
            $section->position++;
            $this->FormTemplateSections->save($section);
        }
        return $this->redirect(['action'=>'detail', $section->form_template_id]);
    }

    public function saveField() {
        $formData = $this->request->getData();
        $templateId = $formData['form_template_id'];
        $sectionId = $formData['form_template_section_id'];
        $field = empty($formData['id']) ? $this->FormTemplateFieldsOptionset->newEntity() : $this->FormTemplateFieldsOptionset->get($formData['id']);
        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($formData['id'])) {
                $field = $this->FormTemplateFieldsOptionset->newEntity();
                $field = $this->FormTemplateFieldsOptionset->patchEntity($field, $formData);
                $count = $this->FormTemplateFieldsOptionset->find()
                    ->where(['form_template_id' => $templateId, 'form_template_section_id' => $sectionId])
                    ->count();
                if(empty($field->position) || $field->position > $count) {
                    $field->position = $count + 1;
                }
                $this->FormTemplateFieldsOptionset->incrementPositionAfter($templateId, $sectionId, $field->position);
            } else {
                $field = $this->FormTemplateFieldsOptionset->get($formData['id']);
                unset($formData['position']);
                $field = $this->FormTemplateFieldsOptionset->patchEntity($field, $formData);
            }
            if ($this->FormTemplateFieldsOptionset->save($field)) {
                $this->Flash->success(__('Field created.'));
            } else {
                $this->Flash->error(__('Error saving field.'));
            }
        }
        return $this->redirect(['action'=>'detail', $templateId]);
    }

    public function deleteField($id) {
        $field = $this->FormTemplateFieldsOptionset->get($id);
        $this->FormTemplateFieldsOptionset->decrementPositionAfter($field->form_template_id, $field->form_template_section_id, $field->position);
        if($this->FormTemplateFieldsOptionset->delete($field)) {
            $this->Flash->success(__('Field deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting field.'));
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

    public function moveFieldUp($id) {
        $field = $this->FormTemplateFieldsOptionset->get($id);
        if($field->position > 1) {
            $this->FormTemplateFieldsOptionset->incrementPositionAfter($field->form_template_id, $field->form_template_section_id, $field->position - 1, $field->id);
            $field->position--;
            $this->FormTemplateFieldsOptionset->save($field);
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

    public function moveFieldDown($id) {
        $field = $this->FormTemplateFieldsOptionset->get($id);
        $count = $this->FormTemplateFieldsOptionset->find()
                ->where(['form_template_id' => $field->form_template_id, 'form_template_section_id' => $field->form_template_section_id])
                ->count();
        if($field->position < $count) {
            $this->FormTemplateFieldsOptionset->decrementPositionBefore($field->form_template_id, $field->form_template_section_id, $field->position + 1, $field->id);
            $field->position++;
            $this->FormTemplateFieldsOptionset->save($field);
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

}