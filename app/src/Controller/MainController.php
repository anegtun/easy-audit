<?php
namespace App\Controller;

use Cake\Event\EventInterface;

class MainController extends AppController {

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index']);
    }

    public function index() {
        $user = $this->Authentication->getResult();
        if (!$user->isValid()) {
            return $this->redirect(['controller'=>'users', 'action'=>'login']);
        }
    }
}