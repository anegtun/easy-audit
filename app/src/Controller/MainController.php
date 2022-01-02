<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

class MainController extends AppController {

    public function beforeFilter(Event $event) {
        $this->Auth->allow(['index']);
    }

    public function index() {
        if(!$this->Auth->user()) {
            return $this->redirect(['controller'=>'users', 'action'=>'login' ]);
        }
        return $this->redirect(['controller'=>'audits', 'action'=>'index' ]);
    }

}