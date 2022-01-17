<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class DebugController extends AppController {

    public function index() {
        echo "USER AGENT: " . $_SERVER['HTTP_USER_AGENT'] . "<br/>";
        echo "MOBILE: " . $this->request->is('mobile') . "<br/>";
        echo "TABLET: " . $this->request->is('tablet') . "<br/>";
        die();
    }

}
