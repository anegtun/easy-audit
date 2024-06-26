<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Roles;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\Event\EventInterface;

class UsersController extends AppController {
    
    public function initialize(): void {
        parent::initialize();
        $this->Roles = new Roles();
    }

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login','logout', 'hash']);
    }

    /*public function isAuthorized($user) {
        if (in_array($this->request->getParam('action'), ['login','logout','hash'])) {
            return true;
        }
        return $user['role'] === 'admin';
    }*/

    public function index() {
        $users = $this->Users->find('all')->order(['username'=>'ASC']);
        $roles = $this->Roles->getAll();
        $this->set(compact('roles', 'users'));
    }

    public function detail($id = null) {
        $user = empty($id) ? $this->Users->newEntity([]) : $this->Users->get($id, ['contain' => [
            'Audits' => [ 'Customers', 'FormTemplates', 'sort' => ['date'=>'DESC'] ]
        ]]);
        $roles = $this->Roles->getAll();
        $this->set(compact('roles', 'user'));
    }

    public function save() {
        $data = $this->request->getData();
        if(empty($data['password'])) {
            unset($data['password']);
        }
        $user = empty($data['id']) ? $this->Users->newEntity([]) : $this->Users->get($data['id']);
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
        $user = $this->Users->get($id, [ 'contain' => ['Audits'] ]);
        if(!empty($user->audits)) {
            $this->Flash->error(__('This user is assigned to at least one audit, so it can\'t be deleted.'));
            return $this->redirect(['action'=>'index']);
        }
        if($this->Users->delete($user)) {
            $this->Flash->success(__('User deleted correctly.'));
        } else {
            $this->Flash->error(__('Error deleting user.'));
        }
        return $this->redirect(['action'=>'index']);
    }
    
    public function login() {
        $user = $this->Authentication->getResult();
        if ($user->isValid()) {
            return $this->redirect(['controller'=>'Main', 'action'=>'index']);
        }

        if ($this->request->is('post')) {
            $this->Flash->error(__('Invalid username or password, try again'));
        }
        $this->viewBuilder()->setLayout('login');
    }

    public function logout() {
        $this->Authentication->logout();
        return $this->redirect(['action'=>'login']);
    }

    public function hash() {
        $password = $this->request->getQuery('password');
        $hash = (new DefaultPasswordHasher)->hash($password);
        return $this->response->withStringBody("$password => $hash");
    }

}