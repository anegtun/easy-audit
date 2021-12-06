<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class AuditsController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->Customers = TableRegistry::getTableLocator()->get('Customers');
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
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
        $users = $this->Users->find('all');
        $this->set(compact('audit', 'users'));
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
            if ($this->Audits->save($audit)) {
                $this->Flash->success(__('Audit updated.'));
            } else {
                $this->Flash->error(__('Error updating audit.'));
            }
        }
        return $this->redirect(['action'=>'detail', $audit->id]);
    }

}
