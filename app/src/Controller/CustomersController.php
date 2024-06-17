<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class CustomersController extends AppController {
    
    public function initialize(): void {
        parent::initialize();
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
        $this->loadComponent('EmailParser');
    }

    public function isAuthorized($user) {
        if (in_array($this->request->getParam('action'), ['save','delete'])) {
            return $user['role'] === 'admin';
        }
        return true;
    }

    public function index() {
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $this->set(compact('customers'));
    }

    public function detail($id=null) {
        $customer = empty($id) ? $this->Customers->newEntity() : $this->getCustomer($id);
        if(!empty($id)) {
            $templates = $this->FormTemplates->find('all')
                ->contain(['Forms'])
                ->order(['Forms.name', 'FormTemplates.name'])
                ->where(['disabled'=>0]);
            $templateIds = $customer->getTemplateIds();
            if(!empty($templateIds)) {
                $templates->where(['FormTemplates.id NOT IN' => $templateIds]);
            }
            $this->set(compact('templates'));
        }
        $this->set(compact('customer'));
    }

    public function save() {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            $customer->emails = implode(', ', $this->EmailParser->parse($customer->emails));
            $customer = $this->Customers->save($customer);
            if ($customer) {
                $this->Flash->success(__('Customer saved correctly.'));
                return $this->redirect(['action'=>'detail', $customer->id]);
            } else {
                $this->Flash->error(__('Error saving customer.'));
            }
        }
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $customer = $this->Customers->get($id, [ 'contain' => ['Audits'] ]);
        if(!empty($customer->audits)) {
            $this->Flash->error(__('This customer has at least one audit, so it can\'t be deleted.'));
            return $this->redirect(['action'=>'index']);
        }
        if($this->Customers->delete($customer)) {
            $this->Flash->success(__('Customer deleted correctly.'));
        } else {
            $this->Flash->error(__('Error deleting customer.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function saveTemplate() {
        $data = $this->request->getData();
        $customerId = $data['customer_id'];
        $template = $this->FormTemplates->get($data['form_template_id']);
        $customer = $this->getCustomer($customerId);
        $customer->templates[] = $template;
        $customer->setDirty('templates', true);
        if ($this->Customers->save($customer)) {
            $this->Flash->success(__('Template added correctly.'));
        } else {
            $this->Flash->error(__('Error adding template.'));
        }
        return $this->redirect(['action'=>'detail', $customerId]);
    }

    public function deleteTemplate($customerId, $templateId) {
        $customer = $this->getCustomer($customerId);
        $customer->templates = array_filter(
            $customer->templates,
            function ($e) use (&$templateId) {
                return $e->id != $templateId;
            }
        );
        $customer->setDirty('templates', true);
        if ($this->Customers->save($customer)) {
            $this->Flash->success(__('Template removed correctly.'));
        } else {
            $this->Flash->error(__('Error removing template.'));
        }
        return $this->redirect(['action'=>'detail', $customerId]);
    }

    public function templates($id) {
        $customer = $this->getCustomer($id);
        $result = [];
        foreach($customer->templates as $t) {
            if(!$t->disabled) {
                $result[] = ['id' => $t->id, 'name' => $t->name, 'form_name' => $t->form->name];
            }
        }
        $this->set($result);
    }

    private function getCustomer($id) {
        return $this->Customers->get($id, ['contain' => [
            'Audits' => [ 'Customers', 'FormTemplates', 'Users', 'sort' => ['date'=>'DESC'] ],
            'FormTemplates' => [
                'Forms',
                'sort' => [ 'Forms.name', 'FormTemplates.name' ]
            ]
        ]]);
    }

}
