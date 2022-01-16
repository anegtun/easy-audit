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
        $this->Audits = TableRegistry::getTableLocator()->get('Audits');
        $this->Customers = TableRegistry::getTableLocator()->get('Customers');
    }

    public function isAuthorized($user) {
        return $user['role'] === 'admin';
    }

    public function index() {
        $form_types = $this->FormTypes->getAll();
        $forms = $this->Forms->find()->order('name');
        $this->set(compact('forms', 'form_types'));
    }

    public function detail($id) {
        $form = $this->getForm($id);
        $templates = $this->getTemplates($form);
        $audits = $this->getRelatedAudits($templates);
        $customers = $this->getRelatedCustomers($templates);
        $this->set(compact('form', 'audits', 'customers'));
    }

    public function create() {
        $form = $this->Forms->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $form = $this->Forms->patchEntity($form, $this->request->getData());
            $form = $this->Forms->save($form);
            if ($form) {
                $this->Flash->success(__('Form created.'));
                return $this->redirect(['action'=>'detail', $form->id]);
            }
            $this->Flash->error(__('Error saving form.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function save() {
        $data = $this->request->getData();
        $form = $this->Forms->get($data['id']);
        if ($this->request->is('post') || $this->request->is('put')) {
            $form = $this->Forms->patchEntity($form, $this->request->getData());
            if ($this->Forms->save($form)) {
                $this->Flash->success(__('Form updated.'));
            } else {
                $this->Flash->error(__('Error saving form.'));
            }
        }
        return $this->redirect(['action'=>'detail', $form->id]);
    }

    public function delete($id) {
        $form = $this->getForm($id);
        $templates = $this->getTemplates($form);
        $audits = $this->getRelatedAudits($templates);
        if(!empty($audits)) {
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
                $section = $this->Forms->FormSections->newEntity();
                $section = $this->Forms->FormSections->patchEntity($section, $formData);
            } else {
                $section = $this->Forms->FormSections->get($formData['id']);
                unset($formData['position']);
                $section = $this->Forms->FormSections->patchEntity($section, $formData);
            }
            $form = $this->getForm($formData['form_id']);
            if(!empty($formData['position'])) {
                array_splice($form->sections, $formData['position'] - 1, 0, [$section]);
            } else {
                $form->sections[] = $section;
            }
            $form->reindexSections();
            $this->Forms->save($form);
        }
        return $this->redirect($this->referer());
    }

    public function deleteSection($id) {
        $section = $this->Forms->FormSections->get($id);
        $form = $this->getForm($section->form_id);
        $index = $form->findSectionIndex($id);
        unset($form->sections[$index]);
        $form->reindexSections();
        $this->Forms->FormSections->delete($section);
        $this->Forms->save($form);
        return $this->redirect($this->referer());
    }

    public function moveSectionUp($formId, $sectionId) {
        $form = $this->getForm($formId);
        $index = $form->findSectionIndex($sectionId);
        if($index > 0) {
            $form->swapSection($index, $index - 1);
        }
        if(!$this->Forms->save($form)) {
            $this->Flash->error(__('Error moving section.'));
        }
        return $this->redirect($this->referer());
    }

    public function moveSectionDown($formId, $sectionId) {
        $form = $this->getForm($formId);
        $index = $form->findSectionIndex($sectionId);
        if($index < count($form->sections)) {
            $form->swapSection($index, $index + 1);
        }
        if(!$this->Forms->save($form)) {
            $this->Flash->error(__('Error moving section.'));
        }
        return $this->redirect($this->referer());
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

    public function clone() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();

            $old_form = $this->Forms->get($data['id']);
            if(!empty($data['name_old'])) {
                $old_form->name = $data['name_old'];
            }
            if(!empty($data['public_name_old'])) {
                $old_form->public_name = $data['public_name_old'];
            }
            $this->Forms->save($old_form);

            $new_form = $this->Forms->newEntity();
            $new_form->name = $data['name'];
            $new_form->public_name = $data['public_name'];
            $new_form->type = $old_form->type;
            $new_form = $this->Forms->save($new_form);

            $sections_id_map = $this->Forms->FormSections->clone($data['id'], $new_form->id);

            if(!empty($data['templates'])) {
                foreach($data['templates'] as $oldTemplateId) {
                    $old_template = $this->Forms->FormTemplates->get($oldTemplateId);
                    $new_template = $this->Forms->FormTemplates->newEntity();
                    $new_template->form_id = $new_form->id;
                    $new_template->name = $old_template->name;
                    $new_template->disabled = $old_template->disabled;
                    $new_template = $this->Forms->FormTemplates->save($new_template);
                    $this->Forms->FormTemplates->FormTemplateFields->clone($old_template->id, $new_template->id, $sections_id_map);
                }
            }
            
            $this->Flash->success(__('Form created.'));
            return $this->redirect(['action'=>'detail', $new_form->id]);
        }
        return $this->redirect($this->referer());
    }

    private function getForm($id) {
        return $this->Forms->get($id, ['contain' => [
            'FormSections' => ['sort' => 'position'],
            'FormTemplates' => ['sort' => 'name']
        ]]);
    }

    private function getTemplates($form) {
        $templateIds = $form->getTemplateIds();
        if(empty($templateIds)) {
            return [];
        }
        return $this->Forms->FormTemplates->find('all')
            ->contain(['Audits', 'Customers'])
            ->where(['id IN' => $form->getTemplateIds()]);
    }

    private function getRelatedAudits($templates) {
        $auditIds = [];
        foreach($templates as $t) {
            foreach($t->audits as $a) {
                $auditIds[] = $a->id;
            }
        }
        if(empty($auditIds)) {
            return [];
        }
        return $audits = $this->Audits->find('all')
            ->contain(['Customers', 'Users'])
            ->order('date')
            ->where(['Audits.id IN' => $auditIds]);
    }

    private function getRelatedCustomers($templates) {
        foreach($templates as $t) {
            foreach($t->customers as $c) {
                $customerIds[] = $c->id;
            }
        }
        if(empty($customerIds)) {
            return [];
        }
        return $this->Customers->find('all')
            ->order('name')
            ->where(['id IN' => $customerIds]);
    }

}