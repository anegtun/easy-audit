<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class CustomersController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->FormTemplates = TableRegistry::getTableLocator()->get('FormTemplates');
    }

    public function index() {
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $this->set(compact('customers'));
    }

    public function detail($id=null) {
        $customer = empty($id) ? $this->Customers->newEntity() : $this->getCustomer($id);
        if(!empty($id)) {
            $templates = $this->FormTemplates->find('all');
            $templateIds = $customer->getTemplateIds();
            if(!empty($templateIds)) {
                $templates->where(['id NOT IN' => $templateIds]);
            }
            $this->set(compact('templates'));
        }
        $this->set(compact('customer'));
    }

    public function save() {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post') || $this->request->is('put')) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('Customer saved correctly.'));
            } else {
                $this->Flash->error(__('Error saving customer.'));
            }
        }
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $customer = $this->Customers->get($id);
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
        $customer->form_templates[] = $template;
        $customer->setDirty('form_templates', true);
        if ($this->Customers->save($customer)) {
            $this->Flash->success(__('Template added correctly.'));
        } else {
            $this->Flash->error(__('Error adding template.'));
        }
        return $this->redirect(['action'=>'detail', $customerId]);
    }

    public function deleteTemplate($customerId, $templateId) {
        $customer = $this->getCustomer($customerId);
        $customer->form_templates = array_filter(
            $customer->form_templates,
            function ($e) use (&$templateId) {
                return $e->id != $templateId;
            }
        );
        $customer->setDirty('form_templates', true);
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
        foreach($customer->form_templates as $t) {
            $result[] = ['id' => $t->id, 'name' => $t->name];
        }
        $this->set($result);
    }

    private function getCustomer($id) {
        return $this->Customers->get($id, [ 'contain' => ['Audits' => [ 'Customers', 'FormTemplates', 'Users' ], 'FormTemplates'] ]);
    }

}
