<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Roles;
use Cake\Event\Event;

class UsersController extends AppController {
    
    public function initialize() {
        parent::initialize();
        $this->Roles = new Roles();
    }

    public function beforeFilter(Event $event) {
        $this->Auth->allow(array('login','logout'));
    }

    public function index() {
        $users = $this->Users->find('all')->order(['username'=>'ASC']);
        $roles = $this->Roles->getAll();
        $this->set(compact('roles', 'users'));
    }

    public function detail($id = null) {
        $user = empty($id) ? $this->Users->newEntity() : $this->Users->get($id, ['contain'=>['Audits'=>['Customers', 'FormTemplates']]]);
        $roles = $this->Roles->getAll();
        $this->set(compact('roles', 'user'));
    }

    public function save() {
        $data = $this->request->getData();
        if(empty($data['password'])) {
            unset($data['password']);
        }
        $user = empty($data['id']) ? $this->Users->newEntity() : $this->Users->get($data['id']);
        if ($this->request->is('post') || $this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('User saved correctly.'));
            } else {
                $this->Flash->error(__('Error saving user.'));
            }
        }
        return $this->redirect(['action'=>'index']);
    }

    public function delete($id) {
        $user = $this->Users->get($id);
        if($this->Users->delete($user)) {
            $this->Flash->success(__('User deleted correctly.'));
        } else {
            $this->Flash->error(__('Error deleting user.'));
        }
        return $this->redirect(['action'=>'index']);
    }

    public function login() {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
        $this->viewBuilder()->setLayout('login');
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}