<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class AuditsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->AuditFieldMeasureValues = TableRegistry::getTableLocator()->get('AuditFieldMeasureValues');
        $this->AuditFieldOptionsetValues = TableRegistry::getTableLocator()->get('AuditFieldOptionsetValues');
        $this->Customers = TableRegistry::getTableLocator()->get('Customers');
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
        $this->FormTemplateFieldsOptionset = TableRegistry::getTableLocator()->get('FormTemplateFieldsOptionset');
        $this->FormTemplateSections = TableRegistry::getTableLocator()->get('FormTemplateSections');
        $this->FormTemplateOptionsetValues = TableRegistry::getTableLocator()->get('FormTemplateOptionsetValues');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->loadComponent('AuditFile');
    }

    public function index() {
        $audits = $this->Audits->find('all', ['contain'=>['Customers', 'FormTemplates', 'Users'], 'order'=>['date'=>'DESC']]);
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $templates = $this->FormTemplates->find('all', ['order'=>'name']);
        $this->set(compact('audits', 'customers', 'templates'));
    }

    public function data($id) {
        $audit = $this->Audits->get($id, ['contain' => [
            'Customers' => [ 'FormTemplates' => ['sort' => [ 'name' => 'ASC' ]] ],
            'FormTemplates',
            'Users'
        ]]);
        $auditTemplateIds = $audit->getTemplateIds();
        foreach($audit->customer->form_templates as $i=>$template) {
            if(in_array($template->id, $auditTemplateIds)) {
                unset($audit->customer->form_templates[$i]);
            }
        }
        $users = $this->Users->find('all');
        $this->set(compact('audit', 'users'));
    }

    public function fill($id) {
        $audit = $this->Audits->get($id, [ 'contain' => [
            'Customers',
            'FormTemplates' => ['FormTemplateSections', 'FormTemplateFieldsOptionset', 'sort' => [ 'name' => 'ASC' ]]
        ] ]);
        $optionset_values = $this->FormTemplateOptionsetValues->findAllByOptionset();
        $field_values = $this->AuditFieldOptionsetValues->findForAudit($id);
        $field_measure_values = $this->AuditFieldMeasureValues->findForAudit($id);
        foreach($audit->form_templates as $t) {
            foreach($t->form_template_sections as $s) {
                $s->calculateSectionScore($field_values);
            }
        }
        $field_images = $this->AuditFile->readPhotos($id);
        $this->set(compact('audit', 'field_images', 'field_values', 'field_measure_values', 'optionset_values'));
    }

    public function create() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->patchEntity($this->Audits->newEntity(), $data);
            $audit->date = $this->parseDate($data['date']);
            $audit->auditor_user_id = $this->Auth->user('id');
            $audit->form_templates = $this->FormTemplates->find('all')->where(['id IN' => $data['form_template_id']])->toArray();
            $audit = $this->Audits->save($audit);

            if(!empty($data['clone'])) {
                foreach($audit->form_templates as $t) {
                    $last_audit = $this->Audits->findLast($t->id, $audit->date);
                    if($last_audit) {
                        $this->AuditFieldOptionsetValues->clone($t->id, $last_audit->id, $audit->id);
                        $this->AuditFieldMeasureValues->clone($t->id, $last_audit->id, $audit->id);
                    }
                }
            }

            if ($audit) {
                $this->Flash->success(__('Audit created.'));
            } else {
                $this->Flash->error(__('Error creating audit.'));
            }
        }
        return $this->redirect(['action'=>'index']);
    }

    public function updateData() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->get($data['id']);
            $audit->date = $this->parseDate($data['date']);
            $audit->auditor_user_id = $data['auditor_user_id'];
            $this->Audits->save($audit);
            $this->Flash->success(__('Audit data updated.'));
        }
        return $this->redirect(['action'=>'data', $data['id']]);
    }

    public function addTemplate() {
        $data = $this->request->getData();
        $auditId = $data['audit_id'];
        $audit = $this->Audits->get($auditId, [ 'contain' => ['FormTemplates'] ]);
        $audit->form_templates[] = $this->FormTemplates->get($data['form_template_id']);
        $audit->setDirty('form_templates', true);
        if ($this->Audits->save($audit)) {
            $this->Flash->success(__('Template added correctly.'));
        } else {
            $this->Flash->error(__('Error adding template.'));
        }
        return $this->redirect(['action'=>'data', $auditId]);
    }

    public function deleteTemplate($auditId, $templateId) {
        $audit = $this->Audits->get($auditId, [ 'contain' => ['FormTemplates'] ]);
        $audit->form_templates = array_filter(
            $audit->form_templates,
            function ($e) use (&$templateId) {
                return $e->id != $templateId;
            }
        );
        $audit->setDirty('form_templates', true);
        if ($this->Audits->save($audit)) {
            $this->Flash->success(__('Template removed correctly.'));
        } else {
            $this->Flash->error(__('Error removing template.'));
        }
        return $this->redirect(['action'=>'data', $auditId]);
    }

    public function update() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            if(!empty($data['field_values'])) {
                foreach($data['field_values'] as $templateId => $field_values) {
                    $field_observations = $data['field_observations'][$templateId];
                    $this->AuditFieldOptionsetValues->upsertAll($data['id'], $templateId, $field_values, $field_observations);
                }
            }
            if(!empty($data['audit_measure'])) {
                foreach($data['audit_measure'] as $templateId => $audit_measures) {
                    $this->AuditFieldMeasureValues->upsertAll($data['id'], $templateId, $audit_measures);
                }
            }
            if(!empty($data['field_photo'])) {
                foreach($data['field_photo'] as $templateId => $photos) {
                    $this->AuditFile->addPhotos($data['id'], $templateId, $photos);
                }
            }
            if(!empty($data['field_img_removed'])) {
                foreach($data['field_img_removed'] as $templateId => $photos) {
                    $this->AuditFile->removePhotos($data['id'], $templateId, $photos);
                }
            }
        }
        return $this->redirect(['action'=>'fill', $data['id']]);
    }

    public function delete($id) {
        $audit = $this->Audits->get($id);
        if($this->Audits->delete($audit)) {
            $this->AuditFile->removeAllPhotos($id);
            $this->Flash->success(__('Audit deleted correctly.'));
        } else {
            $this->Flash->error(__('Error deleting audit.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    private function parseDate($date) {
        return empty($date) ? NULL : Time::createFromFormat('d-m-Y', $date);
    }

}
