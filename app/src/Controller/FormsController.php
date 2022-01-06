<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\FormTemplateFieldTypes;
use App\Model\FormTypes;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class FormsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->FormTemplateFieldTypes = new FormTemplateFieldTypes();
        $this->FormTypes = new FormTypes();
        $this->FormSections = TableRegistry::getTableLocator()->get('FormSections');
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
        $this->FormTemplateFieldsOptionset = TableRegistry::getTableLocator()->get('FormTemplateFieldsOptionset');
        $this->FormTemplateOptionsets = TableRegistry::getTableLocator()->get('FormTemplateOptionsets');
    }

    public function isAuthorized($user) {
        return $user['role'] === 'admin';
    }

    public function index() {
        $form_types = $this->FormTypes->getAll();
        $forms = $this->Forms->find()->order(['name'=>'ASC']);
        $this->set(compact('forms', 'form_types'));
    }

    public function detail($id) {
        $form = $this->Forms->get($id, ['contain' => [
            'FormSections' => ['sort' => 'position']
        ]]);
        $this->set(compact('form'));
    }

    public function create() {
        $form = $this->Forms->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $form = $this->Forms->patchEntity($form, $this->request->getData());
            $form = $this->Forms->save($form);
            if ($form) {
                $this->Flash->success(__('Form created.'));
                return $this->redirect(['action'=>'index']);
                return $this->redirect(['action'=>'detail', $form->form_id]);
            }
            $this->Flash->error(__('Error saving form.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $form = $this->Forms->get($id/*, [ 'contain' => ['Audits'] ]*/);
        if(!empty($form->audits)) {
            $this->Flash->error(__('This form is used in at least one audit, so it can\'t be deleted. You can instead disable it.'));
            return $this->redirect($this->referer());
        }
        if(!$this->Forms->delete($form)) {
            $this->Flash->error(__('Error deleting form.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->success(__('Form deleted successfully.'));
        return $this->redirect(['action'=>'index']);
    }

    public function saveSection() {
        $formData = $this->request->getData();
        $formId = $formData['form_id'];
        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($formData['id'])) {
                $section = $this->FormSections->newEntity();
                $section = $this->FormSections->patchEntity($section, $formData);
                $count = $this->FormSections->find()
                    ->where(['form_id' => $formId])
                    ->count();
                if(empty($section->position) || $section->position > $count) {
                    $section->position = $count + 1;
                }
                $this->FormSections->incrementPositionAfter($formId, $section->position);
            } else {
                $section = $this->FormSections->get($formData['id']);
                unset($formData['position']);
                $section = $this->FormSections->patchEntity($section, $formData);
            }
            if ($this->FormSections->save($section)) {
                $this->Flash->success(__('Section saved.'));
            } else {
                $this->Flash->error(__('Error saving section.'));
            }
        }
        return $this->redirect(['action'=>'detail', $formId]);
    }

    public function deleteSection($id) {
        $section = $this->FormSections->get($id);
        $this->FormSections->decrementPositionAfter($section->form_id, $section->position);
        if($this->FormSections->delete($section)) {
            $this->Flash->success(__('Section deleted successfully.'));
        } else {
            $this->Flash->error(__('Error deleting section.'));
        }
        return $this->redirect(['action'=>'detail', $section->form_id]);
    }

    public function moveSectionUp($id) {
        $section = $this->FormSections->get($id);
        if($section->position > 1) {
            $this->FormSections->incrementPositionAfter($section->form_id, $section->position - 1, $section->id);
            $section->position--;
            $this->FormSections->save($section);
        }
        return $this->redirect(['action'=>'detail', $section->form_id]);
    }

    public function moveSectionDown($id) {
        $section = $this->FormSections->get($id);
        $count = $this->FormSections->find()
                ->where(['form_id' => $section->form_id])
                ->count();
        if($section->position < $count) {
            $this->FormSections->decrementPositionBefore($section->form_id, $section->position + 1, $section->id);
            $section->position++;
            $this->FormSections->save($section);
        }
        return $this->redirect(['action'=>'detail', $section->form_id]);
    }

    public function rename() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $form = $this->Forms->get($data['id']);
            $form->name = $data['name'];
            $form->public_name = $data['public_name'];
            $this->Forms->save($form);
            $this->Flash->success(__('Form renamed.'));
            return $this->redirect($this->referer());
        }
        return $this->redirect(['action'=>'index']);
    }








    public function save() {
        $field_types = $this->FormTemplateFieldTypes->getAll();
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

    public function detail2($id) {
        $template = $this->FormTemplates->get($id, [ 'contain' => [
            'Audits' => [
                'Customers',
                'Users',
                'sort' => ['date' => 'DESC']
            ],
            'Customers'
        ]]);
        $field_types = $this->FormTemplateFieldTypes->getAll();
        $optionsets = $this->FormTemplateOptionsets->findForSelect();
        $sections = $this->FormTemplateSections->find()
            ->where(['form_template_id' => $id])
            ->order('position')
            ->contain(['FormTemplateFieldsOptionset' => ['sort' => 'position']]);
        $allFields = $this->FormTemplateFieldsOptionset->find()
            ->where(['form_template_id' => $id])
            ->order('position');
        $this->set(compact('template', 'field_types', 'optionsets', 'sections', 'allFields'));
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
            if($field->type!=='select') {
                $$field->optionset_id = null;
            }
            if ($this->FormTemplateFieldsOptionset->save($field)) {
                $this->Flash->success(__('Field saved.'));
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