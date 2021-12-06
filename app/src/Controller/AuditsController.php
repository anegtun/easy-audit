<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class AuditsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->AuditFieldValues = TableRegistry::getTableLocator()->get('AuditFieldValues');
        $this->Customers = TableRegistry::getTableLocator()->get('Customers');
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
        $this->FormTemplateFields = TableRegistry::getTableLocator()->get('FormTemplateFields');
        $this->FormTemplateSections = TableRegistry::getTableLocator()->get('FormTemplateSections');
        $this->FormTemplateOptionsetValues = TableRegistry::getTableLocator()->get('FormTemplateOptionsetValues');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function index() {
        $audits = $this->Audits->find('all', ['contain'=>['Customers', 'FormTemplates', 'Users'], 'order'=>'date']);
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $templates = $this->FormTemplates->find('all', ['order'=>'name']);
        $this->set(compact('audits', 'customers', 'templates'));
    }

    public function detail($id) {
        $audit = $this->Audits->get($id, [ 'contain'=>['Customers', 'FormTemplates', 'Users'] ]);
        $template = $this->FormTemplates->get($audit->form_template->id);
        $template_sections = $this->FormTemplateSections->find()
            ->where(['form_template_id' => $template->id])
            ->order(['position' => 'ASC'])
            ->contain(['FormTemplateFields' => ['sort' => ['FormTemplateFields.position' => 'ASC']]]);
        $template_fields = $this->FormTemplateFields->find()
            ->where(['form_template_id' => $template->id])
            ->order(['position' => 'ASC']);
        $optionset_values = $this->FormTemplateOptionsetValues->findAllByOptionset();
        $field_values = $this->AuditFieldValues->findForAudit($id);
        $users = $this->Users->find('all');
        $this->set(compact('audit', 'field_values', 'optionset_values', 'template', 'template_sections', 'template_fields', 'users'));
    }

    public function create() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->patchEntity($this->Audits->newEntity(), $data);
            $audit->date = empty($data['date']) ? NULL : Time::createFromFormat('d-m-Y', $data['date']);
            $audit->auditor_user_id = $this->Auth->user('id');
            if ($this->Audits->save($audit)) {
                $this->Flash->success(__('Audit created.'));
            } else {
                $this->Flash->error(__('Error creating audit.'));
            }
        }
        return $this->redirect(['action'=>'index']);
    }

    public function update() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->get($data['id']);
            $audit->date = empty($data['date']) ? NULL : Time::createFromFormat('d-m-Y', $data['date']);
            $audit->auditor_user_id = $data['auditor_user_id'];
            $this->Audits->save($audit);
            $this->AuditFieldValues->upsertAll($data['id'], $data['field_values']);
        }
        return $this->redirect(['action'=>'detail', $audit->id]);
    }

}
