<?php
namespace App\Controller;

require_once(ROOT . DS . 'vendor' . DS  . 'fpdf' . DS . 'fpdf.php');

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use FPDF;


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
        $this->loadComponent('AuditInitialization');
        $this->loadComponent('AuditPdf');
        $this->loadComponent('AuditEmail');
    }

    public function index() {
        $audits = $this->Audits->find('all', ['contain'=>['Customers', 'FormTemplates', 'Users'], 'order'=>['date'=>'DESC']]);
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $templates = $this->FormTemplates->find('all', ['order'=>'name']);
        $this->set(compact('audits', 'customers', 'templates'));
    }

    public function data($id) {
        $audit = $this->Audits->get($id, ['contain' => [
            'Customers' => [ 'FormTemplates' => ['sort' => 'name'] ],
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
        $audit = $this->Audits->getComplete($id);
        $optionset_values = $this->FormTemplateOptionsetValues->findAllByOptionset();
        foreach($audit->form_templates as $t) {
            $last_audit = $this->Audits->findLast($t->id, $audit);
            if($last_audit) {
                foreach($audit->audit_field_optionset_values as $i => $newV) {
                    foreach($last_audit->audit_field_optionset_values as $oldV) {
                        if($newV->form_template_field_id === $oldV->form_template_field_id && !empty($newV->observations) && $newV->observations === $oldV->observations) {
                            $newV->observations_cloned = true;
                        }
                    }
                }
            }
        }
        $field_images = $this->AuditFile->readPhotos($id);
        $this->set(compact('audit', 'field_images', 'optionset_values'));
    }

    public function history($id) {
        $audit = $this->Audits->getComplete($id);
        $audits = $this->Audits->findHistory($audit);
        $this->set(compact('audit', 'audits'));
    }

    public function create() {
        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData();
            $audit = $this->Audits->patchEntity($this->Audits->newEntity(), $data);
            $audit->date = $this->parseDate($data['date']);
            $audit->auditor_user_id = $this->Auth->user('id');
            $audit->form_templates = $this->FormTemplates->find('all')->where(['id IN' => $data['form_template_id']])->toArray();
            $audit = $this->Audits->save($audit);

            $cloned = false;
            if(!empty($data['clone'])) {
                foreach($audit->form_templates as $t) {
                    $last_audit = $this->Audits->findLast($t->id, $audit);
                    if($last_audit) {
                        $this->AuditFieldOptionsetValues->clone($t->id, $last_audit->id, $audit->id);
                        $this->AuditFieldMeasureValues->clone($t->id, $last_audit->id, $audit->id);
                        $cloned = true;
                    }
                }
            }
            if(!$cloned) {
                foreach($audit->form_templates as $t) {
                    $this->AuditInitialization->createDefaults($t->id, $audit->id);
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
            if(!empty($data['field_img_removed'])) {
                foreach($data['field_img_removed'] as $templateId => $photos) {
                    $this->AuditFile->removePhotos($data['id'], $templateId, $photos);
                }
            }
        }
        return $this->redirect(['action'=>'fill', $data['id']]);
    }

    public function addPhoto($auditId, $templateId, $fieldId) {
        $path = $this->AuditFile->addPhoto($auditId, $templateId, $fieldId, $this->request->input());
        $this->response->body(Router::url("/$path"));
        return $this->response;
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

    public function print($id) {
        $audit = $this->Audits->getComplete($id);
        $content = $this->generateReport($audit);

        $response = $this->response
            ->withStringBody($content)
            ->withType('application/pdf');
        if(!empty($this->request->getQuery('download'))) {
            $response = $response->withDownload($audit->getReportFilename());
        }
        return $response;
    }

    public function send($id) {
        $audit = $this->Audits->getComplete($id);
        if(empty($audit->customer->emails)) {
            $this->Flash->error(__('There are no emails configured for this customer.'));
        } else {
            $content = $this->generateReport($audit);
            $this->AuditEmail->sendReport($audit, $content);
            $this->Flash->success(__('Email sent correctly.'));
        }
        return $this->redirect($this->referer());
    }

    private function generateReport($audit) {
        $audits = $this->Audits->findHistory($audit)->toList();
        $images = $this->AuditFile->readPhotos($audit->id);
        return $this->AuditPdf->generate($audit, $audits, $images);
    }

    private function parseDate($date) {
        return empty($date) ? NULL : Time::createFromFormat('d-m-Y', $date);
    }

}
