<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\FormTemplateFieldTypes;
use App\Model\FormTypes;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class FormTemplatesController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->FormTemplateFieldTypes = new FormTemplateFieldTypes();
        $this->FormTypes = new FormTypes();
        $this->FormTemplateFieldsOptionset = TableRegistry::getTableLocator()->get('FormTemplateFieldsOptionset');
        $this->FormTemplateOptionsets = TableRegistry::getTableLocator()->get('FormTemplateOptionsets');
    }

    public function isAuthorized($user) {
        return $user['role'] === 'admin';
    }

    public function detail($id) {
        $template = $this->FormTemplates->get($id, [ 'contain' => [
            'Audits' => [
                'Customers',
                'Users',
                'sort' => ['date' => 'DESC']
            ],
            'Customers',
            'Forms' => ['FormSections' => ['sort' => 'position']],
            'FormTemplateFields' => ['sort' => ['form_section_id', 'position']]
        ]]);
        $field_types = $this->FormTemplateFieldTypes->getAll();
        $optionsets = $this->FormTemplateOptionsets->findForSelect();
        $this->set(compact('template', 'field_types', 'optionsets'));
    }

    public function create() {
        $template = $this->FormTemplates->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $template = $this->FormTemplates->patchEntity($template, $this->request->getData());
            $template = $this->FormTemplates->save($template);
            if ($template) {
                $this->Flash->success(__('Template created.'));
                return $this->redirect(['action'=>'detail', $template->id]);
            }
            $this->Flash->error(__('Error saving template.'));
        }
        return $this->redirect($this->referer());
    }

    public function delete($id) {
        $template = $this->FormTemplates->get($id, [ 'contain' => ['Audits'] ]);
        if(!empty($template->audits)) {
            $this->Flash->error(__('This template is used in at least one audit, so it can\'t be deleted. You can instead disable it.'));
            return $this->redirect($this->referer());
        }
        if(!$this->FormTemplates->delete($template)) {
            $this->Flash->error(__('Error deleting template.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->success(__('Template deleted successfully.'));
        return $this->redirect(['controller'=>'Forms', 'action'=>'detail', $template->form_id]);
    }

    public function clone() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();

            $old_template = $this->FormTemplates->get($data['id']);
            if(!empty($data['name_old'])) {
                $old_template->name = $data['name_old'];
            }
            if(!empty($data['disable'])) {
                $old_template->disabled = 1;
            }
            $this->FormTemplates->save($old_template);

            $new_template = $this->FormTemplates->newEntity();
            $new_template->name = $data['name'];
            $new_template->type = $old_template->type;
            $new_template = $this->FormTemplates->save($new_template);

            $sections_id_map = $this->FormTemplateSections->clone($data['id'], $new_template->id);
            $this->FormTemplateFieldsOptionset->clone($data['id'], $new_template->id, $sections_id_map);
            
            $this->Flash->success(__('Template created.'));
            return $this->redirect(['action'=>'index']);
        }
        return $this->redirect(['action'=>'index']);
    }

    public function toggleEnabled($id) {
        $template = $this->FormTemplates->get($id);
        $template->disabled = $template->disabled ? 0 : 1;
        $this->FormTemplates->save($template);
        $this->Flash->success(__('Template enabled or disabled successfully.'));
        return $this->redirect(['action'=>'index']);
    }

    public function saveField() {
        $formData = $this->request->getData();
        $templateId = $formData['form_template_id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($formData['id'])) {
                $field = $this->FormTemplates->FormTemplateFields->newEntity();
                $field = $this->FormTemplates->FormTemplateFields->patchEntity($field, $formData);
            } else {
                $field = $this->FormTemplates->FormTemplateFields->get($formData['id']);
                unset($formData['position']);
                $field = $this->FormTemplates->FormTemplateFields->patchEntity($field, $formData);
            }
            if($field->type!=='select') {
                $field->optionset_id = null;
            }
            $template = $this->getTemplateWithFields($templateId);
            if(!empty($formData['position'])) {
                array_splice($template->fields, $formData['position'] - 1, 0, [$field]);
            } else {
                $template->fields[] = $field;
            }
            $template->reindexFields();
            $template->setDirty('fields', true);
            $this->FormTemplates->save($template);
        }
        return $this->redirect($this->referer());
    }

    public function deleteField($id) {
        $field = $this->FormTemplateFieldsOptionset->get($id);
        $this->FormTemplateFieldsOptionset->decrementPositionAfter($field->form_template_id, $field->form_section_id, $field->position);
        if($this->FormTemplateFieldsOptionset->delete($field)) {
            $this->Flash->success(__('Field deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting field.'));
        }
        return $this->redirect(['action'=>'detail', $field->form_template_id]);
    }

    public function moveFieldUp($templateId, $fieldId) {
        $template = $this->getTemplateWithFields($templateId);
        $index = $template->findFieldIndex($fieldId);
        if($index > 0) {
            $template->swapField($index, $index - 1);
        }
        if(!$this->FormTemplates->save($template)) {
            $this->Flash->error(__('Error moving field.'));
        }
        return $this->redirect($this->referer());
    }

    public function moveFieldDown($templateId, $fieldId) {
        $template = $this->getTemplateWithFields($templateId);
        $index = $template->findFieldIndex($fieldId);
        if($index < count($template->fields)) {
            $template->swapField($index, $index + 1);
        }
        if(!$this->FormTemplates->save($template)) {
            $this->Flash->error(__('Error moving field.'));
        }
        return $this->redirect($this->referer());
    }

    private function getTemplateWithFields($id) {
        return $this->FormTemplates->get($id, ['contain' => [
            'FormTemplateFields' => ['sort' => ['form_section_id', 'position']]
        ]]);
    }

}