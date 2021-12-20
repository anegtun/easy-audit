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
        $audit = $this->Audits->get($id, [ 'contain' => [
            'Customers',
            'FormTemplates' => ['FormTemplateSections', 'FormTemplateFields'],
            'Users'
        ] ]);
        $optionset_values = $this->FormTemplateOptionsetValues->findAllByOptionset();
        $field_values = $this->AuditFieldValues->findForAudit($id);
        $users = $this->Users->find('all');
        foreach($audit->form_templates as $t) {
            foreach($t->form_template_sections as $s) {
                $s->score = $this->calculateSectionScore($s, $field_values);
            }
        }
        $this->set(compact('audit', 'field_values', 'optionset_values', 'users'));
    }

    public function create() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->patchEntity($this->Audits->newEntity(), $data);
            $audit->date = $this->parseDate($data['date']);
            $audit->auditor_user_id = $this->Auth->user('id');
            $audit->form_templates = $this->FormTemplates->find('all')->where(['id IN' => $data['form_template_id']])->toArray();
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
            $audit->date = $this->parseDate($data['date']);
            $audit->auditor_user_id = $data['auditor_user_id'];
            $this->Audits->save($audit);
            $this->AuditFieldValues->upsertAll($data['id'], $data['field_values']);
        }
        return $this->redirect(['action'=>'detail', $audit->id]);
    }

    private function parseDate($date) {
        return empty($date) ? NULL : Time::createFromFormat('d-m-Y', $date);
    }

    private function calculateSectionScore($section, $field_values) {
        $count = 0;
        $score = 0;
        foreach($field_values as $f) {
            if($f->form_template_field->form_template_section_id === $section->id) {
                $count++;
                $score += $f->form_template_optionset_value->value_numeric;
            }
        }
        return $count === 0 ? 0 : round(100 * ($score / $count), 1);
    }

}
