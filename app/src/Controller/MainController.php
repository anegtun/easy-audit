<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class MainController extends AppController {

    public function beforeFilter(Event $event) {
        $this->Auth->allow(array('index','login','logout'));
    }

    public function index() {
        if(!$this->Auth->user()) {
            return $this->redirect(array('action'=>'login'));
        }
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