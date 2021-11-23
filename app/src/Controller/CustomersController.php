<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class CustomersController extends AppController {

    public function index() {
        $customers = $this->Customers->find('all', ['order'=>'name']);
        $this->set(compact('customers'));
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

}
