<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\FormTemplateFieldTypes;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class FormTemplatesController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->FormTemplateFieldTypes = new FormTemplateFieldTypes();
        $this->FormTemplateFields = TableRegistry::getTableLocator()->get('FormTemplateFields');
        $this->FormTemplateOptionsets = TableRegistry::getTableLocator()->get('FormTemplateOptionsets');
        $this->FormTemplateSections = TableRegistry::getTableLocator()->get('FormTemplateSections');
    }

    public function index() {
        $templates = $this->FormTemplates->find()
            ->contain(['FormTemplateSections', 'FormTemplateFields']);
        $this->set(compact('templates'));
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
        $fieldTypes = $this->FormTemplateFieldTypes->getAll();
        $optionsets = $this->FormTemplateOptionsets->findForSelect();
        $sections = $this->FormTemplateSections->find()
            ->where(['form_template_id' => $id])
            ->order(['position' => 'ASC'])
            ->contain(['FormTemplateFields' => ['sort' => ['FormTemplateFields.position' => 'ASC']]]);
        $allFields = $this->FormTemplateFields->find()
            ->where(['form_template_id' => $id])
            ->order(['position' => 'ASC']);
        $this->set(compact('template', 'fieldTypes', 'optionsets', 'sections', 'allFields'));
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
        $field = empty($formData['id']) ? $this->FormTemplateFields->newEntity() : $this->FormTemplateFields->get($formData['id']);
        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($formData['id'])) {
                $field = $this->FormTemplateFields->newEntity();
                $field = $this->FormTemplateFields->patchEntity($field, $formData);
                $count = $this->FormTemplateFields->find()
                    ->where(['form_template_id' => $templateId, 'form_template_section_id' => $sectionId])
                    ->count();
                if(empty($field->position) || $field->position > $count) {
                    $field->position = $count + 1;
                }
                $this->FormTemplateFields->incrementPositionAfter($templateId, $sectionId, $field->position);
            } else {
                $field = $this->FormTemplateFields->get($formData['id']);
                unset($formData['position']);
                $field = $this->FormTemplateFields->patchEntity($field, $formData);
            }
            if ($this->FormTemplateFields->save($field)) {
                $this->Flash->success(__('Field created.'));
            } else {
                $this->Flash->error(__('Error saving field.'));
            }
        }
        return $this->redirect(['action'=>'detail', $templateId]);
    }

    public function deleteField($id) {
        $field = $this->FormTemplateFields->get($id);
        $this->FormTemplateFields->decrementPositionAfter($field->form_template_id, $field->form_template_section_id, $field->position);
        if($this->FormTemplateFields->delete($field)) {
            $this->Flash->success(__('Field deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting field.'));
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

    public function moveFieldUp($id) {
        $field = $this->FormTemplateFields->get($id);
        if($field->position > 1) {
            $this->FormTemplateFields->incrementPositionAfter($field->form_template_id, $field->form_template_section_id, $field->position - 1, $field->id);
            $field->position--;
            $this->FormTemplateFields->save($field);
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

    public function moveFieldDown($id) {
        $field = $this->FormTemplateFields->get($id);
        $count = $this->FormTemplateFields->find()
                ->where(['form_template_id' => $field->form_template_id, 'form_template_section_id' => $field->form_template_section_id])
                ->count();
        if($field->position < $count) {
            $this->FormTemplateFields->decrementPositionBefore($field->form_template_id, $field->form_template_section_id, $field->position + 1, $field->id);
            $field->position++;
            $this->FormTemplateFields->save($field);
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

}